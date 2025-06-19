<?php

namespace app\controllers;

use app\models\Zapis;
use app\models\ZapisService;
use app\models\Service;
use app\models\User;
use app\models\ZapisSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use app\models\TimeSlots;
use app\models\ZapisSlots;
use app\models\MasterWorkingDays;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\MasterNotWorking;

/**
 * ZapisController implements the CRUD actions for Zapis model.
 */
class ZapisController extends Controller
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
     * Lists all Zapis models.
     *
     * @return string
     */
    public function actionIndex()
    {
        // Обработка отмены записи (AJAX-запрос)
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            $id = Yii::$app->request->post('id');
            $cancelReason = Yii::$app->request->post('cancel_reason');
            
            if (empty($cancelReason)) {
                return ['success' => false, 'error' => 'Укажите причину отмены'];
            }
            
            $zapis = Zapis::findOne($id);
            if (!$zapis) {
                return ['success' => false, 'error' => 'Запись не найдена'];
            }
            
            if ($zapis->status !== 'Новый') {
                return ['success' => false, 'error' => 'Можно отменять только новые записи'];
            }
            
            $zapis->status = 'Отменён';
            $zapis->cancel_reason = $cancelReason;
            
            if ($zapis->save(false)) {
                return ['success' => true];
            }
            
            return ['success' => false, 'error' => 'Не удалось сохранить изменения'];
        }

        // Обычный GET-запрос - отображение страницы
        $searchModel = new ZapisSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $dataProvider->pagination = [
            'pageSize' => 10,
        ];
        
        $query = Zapis::find()
            ->joinWith(['zapisSlots.timeSlot']) 
            ->orderBy(['date' => SORT_ASC]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'zapisList' => $query->all(),
        ]);
    }

    /**
     * Displays a single Zapis model.
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
     * Creates a new Zapis model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Zapis();
        $model->date = date('Y-m-d'); 
        $master = User::find()->where(['role' => 'master'])->select(["CONCAT(last_name, ' ', first_name, ' ', middle_name) as full_name", 'id'])->indexBy('id')->column();
        $services = Service::find()->select(['name', 'id'])->indexBy('id')->column();
        
        
        date_default_timezone_set('Europe/Moscow');
        $timeslots = []; // Изначально пустой список слотов

        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role === 'client') {
            $model->client_id = Yii::$app->user->id;
        }

        if ($this->request->isPost && $model->load($this->request->post())) {
            // Проверка рабочих дней мастера
            $workingDays = MasterWorkingDays::find()->where(['master_id' => $model->master_id])->one();

            if ($workingDays) {
                $dayOfWeek = date('N', strtotime($model->date));
                $dayMap = [
                    1 => 'monday',
                    2 => 'tuesday',
                    3 => 'wednesday',
                    4 => 'thursday',
                    5 => 'friday',
                    6 => 'saturday',
                    7 => 'sunday'
                ];
                $dayColumn = $dayMap[$dayOfWeek];
                
                if (!$workingDays->$dayColumn) {
                    Yii::$app->session->setFlash('error', 'Мастер не работает в выбранный день недели');
                    return $this->refresh();
                }
            }

            // Проверка доступности времени с учетом продолжительности услуги
            $service = Service::findOne($model->services);
            if (!$service) {
                Yii::$app->session->setFlash('error', 'Услуга не найдена');
                return $this->refresh();
            }
            
            $duration = $service->duration_slots; // Длительность услуги в минутах
            $slot = TimeSlots::findOne($model->time_slots_id);
            if (!$slot) {
                Yii::$app->session->setFlash('error', 'Временной слот не найден');
                return $this->refresh();
            }
            
            $startTime = $slot->start_time;
            $endTime = date('H:i', strtotime("$startTime + $duration minutes"));
            
            // Проверка пересечений с другими записями
$conflictingRecords = Zapis::find()
    ->alias('z')
    ->innerJoin('zapis_slots zs', 'zs.zapis_id = z.id')
    ->innerJoin('zapis_service zsv', 'zsv.zapis_id = z.id')
    ->innerJoin('service s', 's.id = zsv.service_id')
    ->innerJoin('time_slots ts', 'zs.time_slots_id = ts.id')
    ->where(['z.master_id' => $model->master_id, 'z.date' => $model->date])
    ->andWhere(['in', 'z.status', ['Новый', 'Завершён']])
    ->andWhere("ts.start_time < :endTime", [':endTime' => $endTime])
    ->andWhere("ADDTIME(ts.start_time, SEC_TO_TIME(s.duration_slots * 60)) > :startTime", [':startTime' => $startTime])
    ->exists();
            
            if ($conflictingRecords) {
                Yii::$app->session->setFlash('error', 'Выбранное время недоступно из-за наложения с другими записями');
                return $this->refresh();
            }
            
            // Проверка "прошедшего времени" для сегодняшней даты
            if ($model->date == date('Y-m-d')) {
                $selectedTime = strtotime($slot->start_time);
                $currentTime = time();
                if ($selectedTime < $currentTime) {
                    Yii::$app->session->setFlash('error', 'Нельзя записаться на прошедшее время');
                    return $this->refresh();
                }
            }

            if ($model->save()) {
                if (!empty($model->services)) {
                    $zapisService = new ZapisService();
                    $zapisService->zapis_id = $model->id;
                    $zapisService->service_id = $model->services;
                    if (!$zapisService->save()) {
                        Yii::$app->session->setFlash('error', 'Ошибка сохранения услуги');
                        return $this->refresh();
                    }
                }
            
                $zapisSlot = new ZapisSlots();
                $zapisSlot->zapis_id = $model->id;
                $zapisSlot->time_slots_id = $model->time_slots_id;
            
                if (!$zapisSlot->save(false)) {
                    Yii::$app->session->setFlash('error', 'Ошибка сохранения временного слота');
                    return $this->refresh();
                }
            
                Yii::$app->session->setFlash('success', 'Запись успешно создана!');
                if (Yii::$app->user->identity->role == 'admin') { 
                    return $this->redirect(['/zapis-slots/index']);
                } else {
                    return $this->redirect(['/zapis/index']);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'master' => $master,
            'services' => $services,
            'timeslots' => $timeslots, 
        ]);
    }

    
    public function actionGetMasterWorkingDays($masterId)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $workingDays = MasterWorkingDays::find()->where(['master_id' => $masterId])->one();
        if (!$workingDays) {
            return ['workingDays' => []];
        }
        
        $daysMap = [
            'monday' => 'Пн',
            'tuesday' => 'Вт',
            'wednesday' => 'Ср',
            'thursday' => 'Чт',
            'friday' => 'Пт',
            'saturday' => 'Сб',
            'sunday' => 'Вс'
        ];
        
        $result = [];
        foreach ($daysMap as $field => $name) {
            if ($workingDays->$field) {
                $result[] = $name;
            }
        }
        
        return ['workingDays' => $result];
    }

    /**
     * Updates an existing Zapis model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $currentMasterId = $model->master_id;

        // Получаем текущие услуги
        $currentServices = ArrayHelper::getColumn(
            $model->zapisServices,
            'service_id'
        );
        $model->services = $currentServices;

        // Получаем текущий временной слот
        $currentZapisSlot = $model->zapisSlots[0] ?? null;
        $initialTimeSlot = $currentZapisSlot ? $currentZapisSlot->time_slots_id : null;
        $initialTimeSlotText = $currentZapisSlot && $currentZapisSlot->timeSlot ? 
            date('H:i', strtotime($currentZapisSlot->timeSlot->start_time)) : null;

        $model->time_slots_id = $initialTimeSlot;

        $master = User::find()->where(['role' => 'master'])->select(["CONCAT(last_name, ' ', first_name, ' ', middle_name) as full_name", 'id'])->indexBy('id')->column();
        $services = Service::find()->select(['name', 'id'])->indexBy('id')->column();
        $timeslots = TimeSlots::find()->select(["DATE_FORMAT(start_time, '%H:%i') as start_time", 'id'])->indexBy('id')->column();

        // Рабочие дни
        $workingDaysInfo = 'Информация о рабочих днях недоступна';
        $workingDays = MasterWorkingDays::find()->where(['master_id' => $currentMasterId])->one();

        if ($workingDays) {
            $dayMap = [
                'monday' => 'Пн',
                'tuesday' => 'Вт',
                'wednesday' => 'Ср',
                'thursday' => 'Чт',
                'friday' => 'Пт',
                'saturday' => 'Сб',
                'sunday' => 'Вс'
            ];

            $days = [];
            foreach ($dayMap as $enDay => $ruDay) {
                if ($workingDays->$enDay) {
                    $days[] = $ruDay;
                }
            }

            $workingDaysInfo = !empty($days)
                ? 'Работает в: ' . implode(', ', $days)
                : 'Мастер не работает ни в один день недели';
        }

        if ($this->request->isPost) {
            $model->load($this->request->post());

            if (is_string($model->services)) {
                $model->services = explode(',', $model->services);
            }

            if ($workingDays) {
                $dayOfWeek = date('N', strtotime($model->date));
                $dayColumn = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'][$dayOfWeek - 1];

                if (!$workingDays->$dayColumn) {
                    Yii::$app->session->setFlash('error', 'Мастер не работает в выбранный день');
                    return $this->refresh();
                }
            }

            if ($model->status == 'Отменён' && empty($model->cancel_reason)) {
                Yii::$app->session->setFlash('error', 'Укажите причину отмены');
                return $this->refresh();
            }

            $transaction = Yii::$app->db->beginTransaction();
            try {
                
                if ($model->save()) {
                    $zapisSlot = ZapisSlots::findOne(['zapis_id' => $model->id]) ?? new ZapisSlots();
                    $zapisSlot->zapis_id = $model->id;
                    $zapisSlot->time_slots_id = $model->time_slots_id;
                    $zapisSlot->save(false);

                    ZapisService::deleteAll(['zapis_id' => $model->id]);
                    if (!empty($model->services)) {
                        foreach ((array)$model->services as $serviceId) {
                            $zapisService = new ZapisService([
                                'zapis_id' => $model->id,
                                'service_id' => $serviceId
                            ]);
                            if (!$zapisService->save()) {
                                throw new \Exception('Ошибка сохранения услуги: ' . print_r($zapisService->errors, true));
                            }
                        }
                    }

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Запись успешно обновлена!');
                    return $this->redirect(['/zapis-slots']);
                } else {
                    throw new \Exception('Ошибка сохранения записи: ' . print_r($model->errors, true));
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
                Yii::error($e->getMessage(), 'zapis');
                return $this->refresh();
            }
        }

        return $this->render('update', [
            'model' => $model,
            'master' => $master, 
            'services' => $services, 
            'timeslots' => $timeslots,
            'workingDaysInfo' => $workingDaysInfo,
            'initialTimeSlot' => $initialTimeSlot,
            'initialTimeSlotText' => $initialTimeSlotText,
        ]);
    }

    public function actionGetAvailableTimes($masterId, $date, $serviceId)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        try {
            // Проверка рабочего дня
            $workingDay = MasterWorkingDays::find()->where(['master_id' => $masterId])->one();
            if (!$workingDay) {
                return ['status' => 'error', 'message' => 'Мастер не найден'];
            }

            $dayOfWeek = date('N', strtotime($date));
            $dayMap = [
                1 => 'monday',
                2 => 'tuesday',
                3 => 'wednesday',
                4 => 'thursday',
                5 => 'friday',
                6 => 'saturday',
                7 => 'sunday'
            ];
            $dayColumn = $dayMap[$dayOfWeek];
            if (!$workingDay->$dayColumn) {
                return ['status' => 'error', 'message' => 'Мастер не работает в этот день'];
            }

            // Получение услуги
            $service = Service::findOne($serviceId);
            if (!$service) {
                return ['status' => 'error', 'message' => 'Услуга не найдена'];
            }
            $duration = $service->duration_slots;

            // Получение всех слотов
            $allSlots = TimeSlots::find()->orderBy('start_time')->all();
            
            // Занятые слоты (исключая отмененные)
            $occupiedSlots = Zapis::find()
                ->alias('z')
                ->innerJoinWith('zapisSlots zs')
                ->where([
                    'z.master_id' => $masterId,
                    'z.date' => $date,
                    'z.status' => ['Новый', 'Завершён']
                ])
                ->select(['zs.time_slots_id'])
                ->column();

            // Нерабочее время мастера
            $notWorkingSlots = MasterNotWorking::find()
                ->where(['master_id' => $masterId, 'date' => $date])
                ->select(['time_slot_id'])
                ->column();

            $busySlots = array_unique(array_merge($occupiedSlots, $notWorkingSlots));
            $availableSlots = [];

            // Проверка доступности с учетом длительности
            foreach ($allSlots as $slot) {
                $startTime = $slot->start_time;
                $endTime = date('H:i', strtotime("$startTime + $duration minutes"));
                $isAvailable = true;

                // Проверка наложения
                foreach ($allSlots as $checkSlot) {
                    if (in_array($checkSlot->id, $busySlots)) {
                        $checkService = Service::find()
                            ->innerJoin('zapis_service', 'zapis_service.service_id = service.id')
                            ->innerJoin('zapis', 'zapis.id = zapis_service.zapis_id')
                            ->innerJoin('zapis_slots', 'zapis_slots.zapis_id = zapis.id')
                            ->where(['zapis_slots.time_slots_id' => $checkSlot->id])
                            ->one();

                        $checkDuration = $checkService ? $checkService->duration_slots : 15;
                        $checkStart = $checkSlot->start_time;
                        $checkEnd = date('H:i', strtotime("$checkStart + $checkDuration minutes"));
                        
                        if ($startTime < $checkEnd && $endTime > $checkStart) {
                            $isAvailable = false;
                            break;
                        }
                    }
                }

                // Проверка "прошедшего времени" для сегодня
                if ($isAvailable && $date == date('Y-m-d')) {
                    $slotTime = strtotime($startTime);
                    $currentTime = strtotime(date('H:i'));
                    if ($slotTime < $currentTime) {
                        $isAvailable = false;
                    }
                }

                if ($isAvailable) {
                    $availableSlots[$slot->id] = date('H:i', strtotime($slot->start_time));
                }
            }

            return ['status' => 'success', 'availableSlots' => $availableSlots];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Ошибка сервера: ' . $e->getMessage()];
        }
    }

    /**
     * Deletes an existing Zapis model.
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
     * Finds the Zapis model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Zapis the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Zapis::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}