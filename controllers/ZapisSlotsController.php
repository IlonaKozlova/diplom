<?php

namespace app\controllers;

use app\models\ZapisSlots;
use app\models\ZapisSlotsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use app\models\User;
use app\models\Zapis;
use app\models\Service;
use app\models\ZapisService;
use app\models\TimeSlots;
use app\models\ZapisSearch;
use app\models\MasterNotWorking;

/**
 * ZapisSlotsController implements the CRUD actions for ZapisSlots model.
 */
class ZapisSlotsController extends Controller
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
     * Lists all ZapisSlots models.
     *
     * @return string
     */
    public function actionIndex()
{
    if (Yii::$app->user->isGuest || Yii::$app->user->identity->role !== 'admin') {
        return $this->redirect(['/site/login']);
    }
    // Установка часового пояса
    date_default_timezone_set('Europe/Moscow');

    // Получение текущей даты или из параметра запроса
    $currentDate = Yii::$app->request->get('date', date('Y-m-d'));
        $dayOfWeek = date('N', strtotime($currentDate)); // 1-понедельник, 7-воскресенье

    // Оптимизированные запросы к базе данных для расписания
    $allMasters = User::find()->where(['role' => 'master'])->with([
            'zapis' => function($query) use ($currentDate) {
                $query->andWhere(['date' => $currentDate])->with(['client', 'zapisServices.service']);
            },
            'workingDays',
            'breaks' => function($query) use ($currentDate) {
                $query->andWhere(['date' => $currentDate])->with('timeSlot');
            }
        ])->indexBy('id')->all();

    // Фильтруем мастеров и собираем информацию
    $masters = [];
    $workingDaysInfo = [];
    
    foreach ($allMasters as $master) {
        $isWorkingDay = false;
        $workingDayComment = '';
        $isNotWorking = false; // Убрали проверку notWorkingDays, так как связи нет

        if ($master->workingDays && $master->workingDays->is_working) {
            $dayMap = [
                1 => 'monday',
                2 => 'tuesday',
                3 => 'wednesday',
                4 => 'thursday',
                5 => 'friday',
                6 => 'saturday',
                7 => 'sunday'
            ];
            
            $dayField = $dayMap[$dayOfWeek];
            $isWorkingDay = $master->workingDays->{$dayField} == '1';
            $workingDayComment = $master->workingDays->comment;
        }

        // Добавляем только работающих мастеров
        if ($isWorkingDay && !$isNotWorking) {
            $masters[$master->id] = $master;
            $workingDaysInfo[$master->id] = [
                'isWorkingDay' => true,
                'workingDayComment' => $workingDayComment,
                'isNotWorking' => false
            ];
        }
    }

    // Оптимизированный запрос для клиентов
    $clients = User::find()->where(['role' => 'client'])->andWhere(['id' => Zapis::find()->select('client_id')->where(['date' => $currentDate])->distinct()])->indexBy('id')->all();

    // Оптимизированный запрос для услуг
    $services = Service::find()->where(['id' => ZapisService::find()->select('service_id')->joinWith('zapis')->where(['zapis.date' => $currentDate])->distinct()])->indexBy('id')->all();

    // Дополнительные данные
    $zapisi = Zapis::find()->where(['date' => $currentDate])->with('client')->all();

    $timeSlots = TimeSlots::find()->orderBy('start_time')->all();

    // Форматирование даты
    $currentDay = ['ВС', 'ПН', 'ВТ', 'СР', 'ЧТ', 'ПТ', 'СБ'][date('w', strtotime($currentDate))];
    $todayDate = date('d.m', strtotime($currentDate));

    // Поиск и пагинация для GridView
    $searchModel = new ZapisSearch();
    $dataProvider = $searchModel->search($this->request->queryParams);
    $dataProvider->pagination = ['pageSize' => 10];

    
    return $this->render('index', [
        // Данные для расписания
        'currentDate' => $currentDate,
        'currentDay' => $currentDay,
        'todayDate' => $todayDate,
        'masters' => $masters,
        'timeSlots' => $timeSlots,
        'services' => $services,
        'clients' => $clients,
        'zapisi' => $zapisi,
        'workingDaysInfo' => $workingDaysInfo,
        
        // Данные для GridView
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]);
}
 

    /**
     * Displays a single ZapisSlots model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
if (Yii::$app->user->isGuest || 
    (Yii::$app->user->identity->role !== 'admin' && Yii::$app->user->identity->role !== 'master')) {
    return $this->redirect(['/site/login']);
}

     date_default_timezone_set('Europe/Moscow');

    // Получаем текущую дату
    $currentDate = Yii::$app->request->get('date', date('Y-m-d'));
    $currentUser = Yii::$app->user->identity;
    
    // Определяем ID мастера
    $selectedMasterId = Yii::$app->request->get('master_id');
    if (!$selectedMasterId && $currentUser && $currentUser->role == 'master') {
        $selectedMasterId = $currentUser->id;
    }
    $selectedMasterId = $selectedMasterId ?: $id;

    // Находим мастера
    $master = User::find()->where(['id' => $selectedMasterId, 'role' => 'master'])->with([
        'zapis' => function($query) use ($currentDate) {
            $query->andWhere(['date' => $currentDate])
                  ->with(['timeSlot', 'client', 'zapisServices.service']);
        },
        'workingDays',
        'breaks' => function($query) use ($currentDate) {
            $query->andWhere(['date' => $currentDate])->with('timeSlot');
        }
    ])->one();


 
    $model = MasterNotWorking::find()->where(['master_id' => $selectedMasterId, 'date' => $currentDate])->with(['timeSlot'])->one();
    if (!$model) {
        $model = new MasterNotWorking(); 
    }

    // Рабочий день
    $isWorkingDay = false;
    $workingDayComment = '';
    if ($master->workingDays && $master->workingDays->is_working) {
        $dayOfWeek = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'][date('w', strtotime($currentDate))];
        $isWorkingDay = $master->workingDays->{$dayOfWeek} == '1';
        $workingDayComment = $master->workingDays->comment;
    }

    // Для админа получаем список всех мастеров
    $masters = [];
    if ($currentUser && $currentUser->role === 'admin') {
        $masters = User::find()->where(['role' => 'master'])->all();
    }

    // Получаем клиентов и услуги
    $clients = User::find()->where(['role' => 'client'])->andWhere(['id' => Zapis::find()->select('client_id')->where(['date' => $currentDate])->distinct()])->indexBy('id')->all();

    $services = Service::find()->where(['id' => ZapisService::find()->select('service_id')->joinWith('zapis')->where(['zapis.date' => $currentDate])->distinct()])->indexBy('id')->all();

    $zapisi = Zapis::find()->where(['date' => $currentDate, 'master_id' => $selectedMasterId])->with('client')->all();

    $timeSlots = TimeSlots::find()->orderBy('start_time')->all();
    $zapisSlots = ZapisSlots::find()->with('timeSlots')->orderBy('time_slots_id')->one();

    $currentDay = ['ВС', 'ПН', 'ВТ', 'СР', 'ЧТ', 'ПТ', 'СБ'][date('w', strtotime($currentDate))];
    $todayDate = date('d.m', strtotime($currentDate));
//     echo '<pre>Master: '; print_r($master); echo '</pre>';
// die();

    return $this->render('view', [
        'model' => $model,
        'currentDate' => $currentDate,
        'currentDay' => $currentDay,
        'todayDate' => $todayDate,
        'master' => $master, 
        'masters' => $masters,
        'timeSlots' => $timeSlots,
        'services' => $services,
        'clients' => $clients,
        'zapisSlots' => $zapisSlots,
        'zapisi' => $zapisi,
        'selectedMasterId' => $selectedMasterId,
        'isWorkingDay' => $isWorkingDay,
        'workingDayComment' => $workingDayComment,
    ]);
    }

    /**
     * Creates a new ZapisSlots model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ZapisSlots();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ZapisSlots model.
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
     * Deletes an existing ZapisSlots model.
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
     * Finds the ZapisSlots model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ZapisSlots the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ZapisSlots::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
