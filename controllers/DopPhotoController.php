<?php

namespace app\controllers;

use app\models\DopPhoto;
use app\models\DopPhotoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Service;
use yii\web\UploadedFile;
use Yii;
/**
 * DopPhotoController implements the CRUD actions for DopPhoto model.
 */
class DopPhotoController extends Controller
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
     * Lists all DopPhoto models.
     *
     * @return string
     */
    public function actionIndex()
    {
if (Yii::$app->user->isGuest || Yii::$app->user->identity->role !== 'admin') {
    return $this->redirect(['/site/login']);
}

        $searchModel = new DopPhotoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DopPhoto model.
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
     * Creates a new DopPhoto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($service_id = null)
    {
        $model = new DopPhoto();
        $service = Service::find()->select(['name', 'id'])->indexBy('id')->column();

            // Устанавливаем service_id из URL параметра
    if ($service_id) {
        $model->service_id = $service_id;
    }
    
  if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
    if ($model->load(Yii::$app->request->post())) {
        $model->photo = UploadedFile::getInstance($model, 'photo');
        $file_name = '/images/' . Yii::$app->security->generateRandomString(50) . '.' . $model->photo->extension;
        $model->photo->saveAs(Yii::getAlias('@webroot') . $file_name);
        $model->photo = $file_name;

        if ($model->save(false)) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['success' => true];
        }
    }

    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    return ['success' => false];
}

 $service = $service_id ? [] : Service::find()->select(['name', 'id'])->indexBy('id')->column();
        return $this->render('create', [
            'model' => $model,
            'service' => $service,
        ]);
    }

    /**
     * Updates an existing DopPhoto model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $service = Service::find()->select(['name', 'id'])->indexBy('id')->column();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
                        if ($model->load($this->request->post())) {
                $model->photo = UploadedFile::getInstance($model, 'photo');
                $file_name = '/images/' . \Yii::$app->getSecurity()->generateRandomString(50) . '.' . $model->photo->extension;
                $model->photo->saveAs(\Yii::$app->basePath . '/web/' . $file_name);
                $model->photo = $file_name;
    
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', 'Фото успешно обновлено!');
                    return $this->redirect(['view', 'id' => $model->id]);
                    // return $this->redirect(['view', 'id' => $model->id]);

                } else {
                    Yii::$app->session->setFlash('error', 'Не удалось обновить фото');
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('update', [
            'model' => $model,
            'service' => $service,
        ]);
    }

    /**
     * Deletes an existing DopPhoto model.
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
     * Finds the DopPhoto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return DopPhoto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DopPhoto::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
