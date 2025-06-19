<?php

namespace app\controllers;

use app\models\MasterNotWorking;
use app\models\MasterNotWorkingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use app\models\Service;
use app\models\TimeSlots;

/**
 * MasterNotWorkingController implements the CRUD actions for MasterNotWorking model.
 */
class MasterNotWorkingController extends Controller
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
     * Lists all MasterNotWorking models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MasterNotWorkingSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MasterNotWorking model.
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
     * Creates a new MasterNotWorking model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new MasterNotWorking();
        $model->date = date('Y-m-d');
        $master = User::find()->where(['role' => 'master'])->select(["CONCAT(last_name, ' ', first_name, ' ', middle_name) as full_name", 'id'])->indexBy('id')->column();        $services = Service::find()->select(['name', 'id'])->indexBy('id')->column();
               // Получаем текущее время
        // date_default_timezone_set('Europe/Moscow');
        $currentTime = date('H:i:s');
        $timeslots = TimeSlots::find()->select(['start_time', 'id'])->indexBy('id')->column();
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['/zapis-slots/index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'master' => $master,
            'timeslots' => $timeslots,
        ]);
    }

    /**
     * Updates an existing MasterNotWorking model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $master = User::find()->where(['role' => 'master'])->select(["CONCAT(last_name, ' ', first_name, ' ', middle_name) as full_name", 'id'])->indexBy('id')->column();        $services = Service::find()->select(['name', 'id'])->indexBy('id')->column();
               // Получаем текущее время
        // date_default_timezone_set('Europe/Moscow');
        $currentTime = date('H:i:s');
        $timeslots = TimeSlots::find()->select(['start_time', 'id'])->indexBy('id')->column();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'master' => $master,
            'timeslots' => $timeslots,
        ]);
    }

    /**
     * Deletes an existing MasterNotWorking model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['/zapis-slots/index']);
    }

    /**
     * Finds the MasterNotWorking model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return MasterNotWorking the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MasterNotWorking::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
