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

class AdminController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function beforeAction($action)
    {
        // Всегда сначала вызываем родительский метод
        if (!parent::beforeAction($action)) {
            return false;
        }

        // Проверка авторизации
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login'])->send();
        }

        // Проверка роли администратора
        if (Yii::$app->user->identity->role !== 'admin') {
            Yii::$app->session->setFlash('error', 'Доступ запрещен: требуются права администратора');
            return $this->goHome();
        }

        return true;
    }
}