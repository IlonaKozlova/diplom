<?php

namespace app\controllers;

use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\web\UploadedFile;


/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
            if (Yii::$app->user->isGuest || Yii::$app->user->identity->role !== 'admin') {
        return $this->redirect(['/site/login']);
    }
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $master = User::find()->select(['first_name', 'last_name', 'middle_name', 'id'])->indexBy('id')->column();

        $dataProvider->pagination = [
            'pageSize' => 10,
        ];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'master' => $master,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
public function actionCreate()
{
    $model = new User();

    if (\Yii::$app->request->isAjax && $model->load(\Yii::$app->request->post())) {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return ActiveForm::validate($model);
    }

    if ($this->request->isPost) {
        if ($model->load($this->request->post())) {
            
            $uploadedFile = UploadedFile::getInstance($model, 'profile_photo');
            
            // Обрабатываем загрузку фото, если оно есть
            if ($uploadedFile !== null && $uploadedFile->tempName !== null) {
                $file_name = '/images/' . \Yii::$app->getSecurity()->generateRandomString(5) . '.' . $uploadedFile->extension;
                if ($uploadedFile->saveAs(\Yii::$app->basePath . '/web/' . $file_name)) {
                    $model->profile_photo = $file_name;
                } else {
                    $model->profile_photo = '/images/standard.png';
                }
            } else {
                // Если фото не загружено, используем стандартное
                $model->profile_photo = '/images/standard.png';
            }

            if ($model->save()) {
                // Авторизуем пользователя после успешной регистрации
                if (Yii::$app->user->login($model)) {
                    \Yii::$app->session->setFlash('success', 'Регистрация прошла успешно!');
                    return $this->redirect(['/site/index']);
                } else {
                    \Yii::$app->session->setFlash('warning', 'Регистрация прошла успешно, но авторизация не удалась.');
                    return $this->redirect(['/site/login']);
                }
            } else {
                // Если сохранение не удалось, показываем ошибки
                // \Yii::$app->session->setFlash('error', 'Ошибка при сохранении: ' . implode(' ', $model->getFirstErrors()));
            }
        }
    } else {
        // Для GET-запроса устанавливаем стандартное фото
        $model->profile_photo = '/images/standard.png';
    }

    return $this->render('create', [
        'model' => $model,
    ]);
}

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
public function actionUpdate($id)
{
    $model = $this->findModel($id);

    if ($this->request->isPost && $model->load($this->request->post())) {
        
        $uploadedFile = UploadedFile::getInstance($model, 'profile_photo');
        
        if ($uploadedFile) {
            $file_name = '/images/' . \Yii::$app->getSecurity()->generateRandomString(5) . '.' . $uploadedFile->extension;
            $uploadedFile->saveAs(\Yii::$app->basePath . '/web/' . $file_name);
            $model->profile_photo = $file_name;
        }
        
        if ($model->save(false)) {
            return $this->redirect(['/user/index']);
        } else {
            // Добавьте вывод ошибок для отладки
            Yii::error($model->errors);
            Yii::$app->session->setFlash('error', 'Ошибка сохранения: ' . print_r($model->errors, true));
        }
    }

    return $this->render('update', [
        'model' => $model,
    ]);
}

    /**
     * Deletes an existing User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

public function actionProfil($id = null)
{
    // Если ID не передан, используем ID текущего пользователя
    if ($id === null) {
        $id = Yii::$app->user->id;
    }

    $model = User::findOne($id);
    if (!$model) {
        throw new NotFoundHttpException('Пользователь не найден');
    }

    if ($this->request->isPost && $model->load($this->request->post())) {
        $model->profile_photo = UploadedFile::getInstance($model, 'profile_photo');
        if ($model->profile_photo) {
            $file_name = '/images/' . \Yii::$app->getSecurity()->generateRandomString(5) . '.' . $model->profile_photo->extension;
            $model->profile_photo->saveAs(\Yii::$app->basePath . '/web/' . $file_name);
            $model->profile_photo = $file_name;
        } else {
            // Если фото не загружено, используем стандартное
            $model->profile_photo = '/images/standard.png';
        }

        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Данные успешно обновлены!');
            return $this->refresh();
        }
    }

    return $this->render('profil', [
        'model' => $model,
        'user' => $model,        
    ]);
}

    public function actionMasters()
    {
        
        $masters = User::find()->where(['role' => 'master'])->all();

        return $this->render('masters', [
            'masters' => $masters
        ]);
        
    }
}
