<?php

namespace app\controllers;

use app\models\MasterWorkingDays;
use app\models\MasterWorkingDaysSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use Yii;
/**
 * MasterWorkingDaysController implements the CRUD actions for MasterWorkingDays model.
 */
class MasterWorkingDaysController extends Controller
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
     * Lists all MasterWorkingDays models.
     *
     * @return string
     */
    public function actionIndex()
    {
    if (Yii::$app->user->isGuest || Yii::$app->user->identity->role !== 'admin') {
        return $this->redirect(['/site/login']);
    }

        $searchModel = new MasterWorkingDaysSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MasterWorkingDays model.
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
     * Creates a new MasterWorkingDays model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
public function actionCreate()
{
    if (Yii::$app->user->isGuest || Yii::$app->user->identity->role !== 'admin') {
        return $this->redirect(['/site/login']);
    }

    $model = new MasterWorkingDays();
    $mast = User::find()->select(['first_name', 'middle_name', 'id'])->where(['role' => 'master'])
        ->indexBy('id')->column();

    if ($this->request->isPost) {
        if ($model->load($this->request->post())) {
            // Проверка существования расписания для мастера
            $exists = MasterWorkingDays::find()->where(['master_id' => $model->master_id])->exists();
            
            if ($exists) {
                // Если расписание уже существует показываем ошибку
                Yii::$app->session->setFlash('error', 
                    'Расписание мастера уже существует. Обновите его, если нужно.');
            } else {
                // Если расписания нет сохраняем
                if ($model->save()) {
                    return $this->redirect(['/master-working-days/index']);
                }
            }
        }
    } else {
        $model->loadDefaultValues();
    }

    return $this->render('create', [
        'model' => $model,
        'mast' => $mast,
    ]);
}

    /**
     * Updates an existing MasterWorkingDays model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

if (Yii::$app->user->isGuest || Yii::$app->user->identity->role !== 'admin') {
    return $this->redirect(['/site/login']);
}

        $model = $this->findModel($id);

$mast = User::find()->select(['first_name', 'middle_name', 'id'])->where(['role' => 'master'])
->indexBy('id')->column();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['/master-working-days/index']);
        }

        return $this->render('update', [
            'model' => $model,
				'mast' => $mast,
        ]);
    }

    /**
     * Deletes an existing MasterWorkingDays model.
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
     * Finds the MasterWorkingDays model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return MasterWorkingDays the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MasterWorkingDays::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
