<?php

use yii\helpers\Html;
use yii\helpers\Url;
/** @var yii\web\View $this */
/** @var app\models\Service $model */

$this->title = $model->name;
\yii\web\YiiAsset::register($this);
?>
<div class="service-view">

    <style>
        /* Общие стили */
        .service-view {
            padding: 25px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            max-width: 800px;
            margin: 20px auto;
            color: #333;
            line-height: 1.6;
        }
        
        h1 {
            font-size: 2rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            text-align: center;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .service-content {
            margin-top: 25px;
            font-size: 1rem;
        }
        
        .service-field {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .service-field:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .service-price {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
        }
        
        /* .service-duration {
            color: #6c757d;
        } */
        
        .service-comment {
            font-style: italic;
            background: linear-gradient(45deg,rgb(255, 146, 186) 0%,rgb(58, 255, 255) 100%);
            padding: 15px;
            color: white;
            border-radius: 6px;
            margin-top: 10px;
        }
        
        .service-actions {
            margin: 30px 0 10px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .btn {
            border-radius: 6px;
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        /* Стили для карусели */
        .photo-carousel {
            margin-bottom: 30px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            /* max-width: 600px; */
            margin-left: auto;
            margin-right: auto;
        }
        
        .carousel-inner {
            border-radius: 8px;
            background:rgba(163, 248, 245, 0.21);
        }
        
        .carousel-inner img {
            width: 100%;
            height: 450px;
            object-fit: cover; /*  contain  */
            padding: 10px;
            margin: 0 auto;
            display: block;
        }
        
        .carousel-control-prev, 
        .carousel-control-next {
            width: 40px;
            height: 40px;
            background: rgba(0,0,0,0.1);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.8;
        }
        
        .carousel-control-prev {
            left: 15px;
        }
        
        .carousel-control-next {
            right: 15px;
        }
        
        .carousel-control-prev:hover, 
        .carousel-control-next:hover {
            background: rgba(255, 12, 12, 0.2);
        }
        
        .carousel-indicators {
            bottom: -25px;
        }
        
        .carousel-indicators button {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #adb5bd;
            border: none;
            margin: 0 4px;
            opacity: 0.7;
        }
        
        .carousel-indicators button.active {
            background-color:#9c80f7;
            opacity: 1;
        }
        
        /* Убрал блок no-photos, так как теперь ничего не показываем при отсутствии фото */
        
        .photo-counter {
            position: absolute;
            right: 15px;
            bottom: 15px;
            background: rgba(0,0,0,0.5);
            color: white;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 12px;
            z-index: 10;
        }
        
        /* Мобильная адаптация */
        @media (max-width: 768px) {
            .service-view {
                padding: 15px;
                margin: 10px;
            }
            
            h1 {
                font-size: 1.6rem;
                margin-bottom: 1.2rem;
            }
            
            .service-actions {
                flex-direction: column;
                gap: 8px;
            }
            
            .btn {
                width: 100%;
                margin: 0;
            }
            
            .carousel-inner img {
                height: 250px;
            }
        }
    </style>

    <h1><?= Html::encode($this->title) ?></h1>
    
    <!-- Карусель с фотографиями - показываем только если есть фото -->
    <?php 
    $photos = [];
    if ($model->photo) {
        $photos[] = $model->photo;
    }
    
    // Получаем дополнительные фото из таблицы dop_photo по service_id
    $dopPhotos = \app\models\DopPhoto::find()
        ->where(['service_id' => $model->id])
        ->all();
        
    foreach ($dopPhotos as $dopPhoto) {
        if ($dopPhoto->photo) {
            $photos[] = $dopPhoto->photo;
        }
    }
    
    // Показываем карусель только если есть хотя бы одно фото
    if (!empty($photos)): ?>
    <div id="serviceCarousel" class="carousel slide photo-carousel" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <?php foreach ($photos as $i => $photo): ?>
                <button type="button" data-bs-target="#serviceCarousel" data-bs-slide-to="<?= $i ?>" <?= $i === 0 ? 'class="active"' : '' ?>></button>
            <?php endforeach; ?>
        </div>
        <div class="carousel-inner">
            <?php foreach ($photos as $i => $photo): ?>
                <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                    <?= Html::img($photo, ['class' => 'd-block w-100', 'alt' => 'Фото услуги']) ?>
                    <div class="photo-counter"><?= ($i+1) . '/' . count($photos) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#serviceCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Предыдущая</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#serviceCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Следующая</span>
        </button>
    </div>
    <?php endif; ?>

    <div class="service-content">
        <?php if (!empty($model->description)): ?>
        <div class="service-field">
            <?= nl2br(Html::encode($model->description)) ?>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($model->price)): ?>
        <div class="service-field service-price" style="color: #E94C89;">
            <?= Yii::$app->formatter->asCurrency($model->price, 'RUB') ?>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($model->duration_slots)): ?>
        <div class="service-field service-duration">Продолжительность ~
            <?php
                $hours = floor($model->duration_slots / 60);
                $minutes = $model->duration_slots % 60;
                $result = [];
                if ($hours > 0) $result[] = $hours . ' ч';
                if ($minutes > 0) $result[] = $minutes . ' мин';
                echo implode(' ', $result) ?: '0 мин';
            ?>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($model->comment)): ?>
        <div class="service-comment">
            <?= nl2br(Html::encode($model->comment)) ?>
        </div>
        <?php endif; ?>
    </div>

<?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'): ?>    
    <div class="service-actions">
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        
        <!-- Кнопка для загрузки фото -->
        <label class="btn btn-success ">
             Добавить фото
            <input type="file" id="photo-upload" class="d-none" accept="image/*">
        </label>

        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить эту услугу?',
                'method' => 'post',
            ],
        ]) ?>
    </div>

  <script>
    document.getElementById('photo-upload').addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('DopPhoto[photo]', file);
        formData.append('DopPhoto[service_id]', <?= $model->id ?>);

        fetch('<?= Url::to(['/dop-photo/create']) ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Перезагружаем страницу после успешной загрузки
            } else {
                alert(data.error || 'Ошибка при загрузке фото');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Произошла ошибка при загрузке файла');
        });
    });
    </script>
<?php endif; ?>
</div>