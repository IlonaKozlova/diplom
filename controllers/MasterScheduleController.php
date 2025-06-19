<?php

namespace app\controllers;

use app\models\MasterSchedule;
use app\models\User;
use app\models\Service;
use app\models\Zapis;

use app\models\MasterScheduleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * MasterScheduleController implements the CRUD actions for MasterSchedule model.
 */
class MasterScheduleController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all MasterSchedule models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MasterScheduleSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->pagination->pageSize = 7;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MasterSchedule model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MasterSchedule model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new MasterSchedule();

        $currentUser = Yii::$app->user->identity;
        if ($currentUser->role === 'master') {
                $model->master_id = $currentUser->id;
                $master = [$currentUser->id => $currentUser->login]; // Текущий клиент
            } else {
                // Для админа/мастера показываем всех клиентов
                $master = User::find()->where(['role' => 'master'])->select(['login', 'id'])->indexBy('id')->column();
            }  

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'master' => $master,

        ]);
    }

    /**
     * Updates an existing MasterSchedule model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MasterSchedule model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MasterSchedule model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return MasterSchedule the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MasterSchedule::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionNow()
    {
        $searchModel = new MasterScheduleSearch();
        $currentDay = ['ВС', 'ПН', 'ВТ', 'СР', 'ЧТ', 'ПТ', 'СБ'][date('w')];
        $todayDate = date('d.m');
        $fullDate = $currentDay . ', ' . $todayDate;
    
        $masters = User::find()->where(['role' => 'master'])->all();
        $loggedInUser = Yii::$app->user->identity;
    
        // Определяем выбранного мастера
        if ($loggedInUser->role === 'master') {
            $selectedMasterId = $loggedInUser->id;
        } else {
            $selectedMasterId = Yii::$app->request->get('master_id') ?? ($masters[0]->id ?? null);
        }
    
        $selectedMaster = User::findOne($selectedMasterId);
    
        // Генерация временных слотов для выбранного мастера
        $timeSlots = $this->generateTimeSlots($selectedMaster);
    
        return $this->render('now', [
            'searchModel' => $searchModel,
            'currentDay' => $currentDay,
            'todayDate' => $todayDate,
            'fullDate' => $fullDate,
            'selectedMaster' => $selectedMaster,
            'selectedMasterId' => $selectedMasterId,
            'masters' => $masters,
            'timeSlots' => $timeSlots,
            'services' => Service::find()->all(),
        ]);
    }
    

    private function generateTimeSlots($master)
    {
        $slots = [];
        $currentDate = date('Y-m-d');
        $dayOfWeek = date('w'); // 0 = воскресенье, 1 = понедельник, ...
    
        // Получаем рабочие часы мастера на текущий день
        $workingData = \app\models\MasterWorkingDays::find()
            ->where(['master_id' => $master->id])
            ->one();
    
        if (!$workingData || !$workingData->is_working) {
            return []; // Мастер не работает в этот день
        }
    
        // Определим текущий день недели
        $weekDays = [
            '0' => 'sunday',
            '1' => 'monday',
            '2' => 'tuesday',
            '3' => 'wednesday',
            '4' => 'thursday',
            '5' => 'friday',
            '6' => 'saturday',
        ];
    
        $dayName = $weekDays[$dayOfWeek];
        $timeRange = $workingData->$dayName;
    
        if (empty($timeRange)) {
            return []; // Мастер не работает в этот день
        }
    
        // Разбиваем время начала и конца
        list($start, $end) = explode('-', $timeRange);
        $startTime = strtotime($start);
        $endTime = strtotime($end);
    
        // Получаем перерывы мастера
        $breaks = ZapisSlots::find()
            ->where([
                'master_id' => $master->id,
                'date' => $currentDate,
                'type' => 'break'
            ])
            ->all();
    
        // Получаем все записи с интервалами
        $appointments = \app\models\Zapis::find()
            ->where(['master_id' => $master->id, 'date' => $currentDate])
            ->with(['zapisServices.service', 'zapisSlots', 'client'])
            ->all();
    
        // Генерируем слоты по 5 минут
        for ($time = $startTime; $time < $endTime; $time = strtotime('+5 minutes', $time)) {
            $timeFormatted = date('H:i:s', $time);
            $slot = [
                'time' => date('H:i', $time),
                'type' => 'available'
            ];
    
            // Проверка на перерыв
            foreach ($breaks as $break) {
                $breakStart = strtotime($break->start_time);
                $breakEnd = strtotime($break->end_time);
                if ($time >= $breakStart && $time < $breakEnd) {
                    $slot['type'] = 'break';
                    break;
                }
            }
    
            // Проверка на запись
            foreach ($appointments as $appointment) {
                foreach ($appointment->zapisSlots as $zSlot) {
                    $appointmentStart = strtotime($zSlot->start_time);
                    $appointmentEnd = strtotime($zSlot->end_time);
                    if ($time >= $appointmentStart && $time < $appointmentEnd) {
                        $slot['type'] = 'appointment';
                        $slot['service'] = implode(', ', array_map(fn($zs) => $zs->service->name, $appointment->zapisServices));
                        $slot['client'] = $appointment->client->getFullName();
                        $slot['price'] = $appointment->total_price;
                        break 2;
                    }
                }
            }
    
            $slots[$timeFormatted] = $slot;
        }
    
        return $slots;
    }
    
    
    
}
