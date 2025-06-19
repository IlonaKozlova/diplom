<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = $model->getFullName();
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <style>
        .user-view {
            max-width: 800px;
            margin: 20px auto;
            padding: 25px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            color: #333;
            line-height: 1.6;
        }
        
        .user-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .user-photo {
            width: 400px;
            height: 400px;
            border-radius: 10%;
            object-fit: cover;
            border: 5px solid #f8f9fa;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-right: 30px;
        }
        
        .user-title {
            font-size: 2rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        
        .user-description {
            margin: 25px 0;
            padding: 15px;
            background:rgba(163, 238, 255, 0.48);
            border-radius: 8px;
            /* border-left: 4px solid #9c80f7; */
            font-size: 1.1rem;
            line-height: 1.7;
        }
        
        .user-actions {
            margin-top: 30px;
            display: flex;
            gap: 10px;
        }
        
        .btn {
            border-radius: 6px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            font-size: 1rem;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .btn-primary {
            background-color: #E94C89;
            border-color: #E94C89;
        }
        
        @media (max-width: 768px) {
            .user-description {
        padding: 15px;
        margin:  15px; /* 0 - сверху/снизу, 15px - слева/справа */
    }

            .user-photo {
        width: 350px;
        height: 450px;
        border-radius: 0; /* Убираем скругление на мобильных */
        /* остальные свойства */
    }
            .user-view {
            padding: 0; 
            margin: 0 auto;
            padding-bottom: 2px; 
            
        }
            .user-header {
                flex-direction: column;
                text-align: center;
                margin: 0;
                border-bottom: 0;
                padding: 0;
            }
            
            .user-photo {
                width: 350px;
                height: 450px;
                margin-right: 0;
                margin-bottom: 20px;
            }
            
            .user-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>

    <div class="user-header">
        <?php if ($model->profile_photo): ?>
            <?= Html::img($model->profile_photo, ['class' => 'user-photo', 'alt' => 'Profile Photo']) ?>
        <?php else: ?>
            <div class="user-photo" style="background: #eee; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-user" style="font-size: 70px; color: #999;"></i>
            </div>
        <?php endif; ?>
        
        <div>
            <h1 class="user-title"><?= Html::encode($model->getFullName()) ?></h1>
        </div>
    </div>

    <?php if (!empty($model->description)): ?>
        <div class="user-description"><?= nl2br(Html::encode($model->description)) ?></div>
    <?php endif; ?>

    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'): ?>    
        <div class="user-actions">
            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить этого пользователя?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    <?php endif; ?>
</div>