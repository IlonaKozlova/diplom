<?php

use app\models\DopPhoto;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

// Если dataProvider не передан из контроллера, создаем его
if (!isset($dataProvider)) {
    $query = DopPhoto::find()->with('service');
    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'pagination' => [
            'pageSize' => 20,
        ],
    ]);
}

$this->title = 'Фотографии услуг';
?>
<div class="dop-photo-index">

<div class="custom-header">
  <div class="header-content">
    <h1 class="header-title"><?= Html::encode($this->title) ?></h1>
    <div class="header-subline">
      <div class="subline-dots"></div>
    </div>
  </div>
</div>

<div class="services-container">
  <?php 
  $groupedPhotos = [];
  foreach ($dataProvider->getModels() as $model) {
      $serviceId = $model->service_id;
      if (!isset($groupedPhotos[$serviceId])) {
          $groupedPhotos[$serviceId] = [
              'service_name' => $model->service->name ?? 'Без названия',
              'photos' => []
          ];
      }
      $groupedPhotos[$serviceId]['photos'][] = $model;
  }
  
  foreach ($groupedPhotos as $serviceId => $serviceData): ?>
    <div class="service-card">
      <div class="service-header">
    <h2 class="service-title">
        <?= Html::encode($serviceData['service_name']) ?>
        <span class="badge"><?= count($serviceData['photos']) ?> фото</span>
    </h2>

    <!-- Скрытый input file -->
    <input type="file" id="photo-input-<?= $serviceId ?>" class="photo-input" data-service-id="<?= $serviceId ?>" style="display:none" accept="image/*">

    <!-- Кнопка для открытия проводника -->
    <button class="btn-add-photo open-file-dialog" data-service-id="<?= $serviceId ?>">
        <i class="bi bi-plus-circle"></i> Добавить фото
    </button>
</div>

      
      <div class="photos-grid">
        <?php foreach ($serviceData['photos'] as $model): ?>
          <div class="photo-card">
            <div class="photo-preview">
              <img src="<?= Html::encode($model->photo) ?>" alt="Фото услуги" class="photo-image">
              <div class="photo-delete-btn">
                <?= Html::a('<i class="bi bi-trash"></i>', ['delete', 'id' => $model->id], [
                    'class' => 'btn-delete-round',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить это фото?',
                        'method' => 'post',
                    ],
                ]) ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>

</div>

<!-- Подключаем Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<script>
    document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.open-file-dialog').forEach(button => {
        button.addEventListener('click', function () {
            const serviceId = this.dataset.serviceId;
            const input = document.getElementById('photo-input-' + serviceId);
            if (input) {
                input.click();
            }
        });
    });

    document.querySelectorAll('.photo-input').forEach(input => {
        input.addEventListener('change', function () {
            const serviceId = this.dataset.serviceId;
            const file = this.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('DopPhoto[photo]', file);
            formData.append('DopPhoto[service_id]', serviceId);

            fetch('/dop-photo/create', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      location.reload(); // перезагрузить, чтобы отобразить новое фото
                  } else {
                      alert('Ошибка при загрузке фото');
                  }
              }).catch(() => {
                  alert('Ошибка соединения с сервером');
              });
        });
    });
});

</script>
<style>
/* Base styles */
body {
  font-family: 'Geologica', sans-serif;
  background: linear-gradient(45deg, #FFD6E5 0%, #FFFFFF 100%);
  color: #333;
}

/* Modern header */
.custom-header {
  text-align: center;
  margin: 0 auto 10px;
  padding: 15px 20px 20px;
  position: relative;
}

.header-content {
  position: relative;
  z-index: 2;
}

.header-title {
  font-size: 2.2rem;
  color: #E94C89;
  margin: 0 0 15px;
  font-weight: 700;
  text-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.header-subline {
  width: 120px;
  height: 6px;
  margin: 0 auto;
  position: relative;
}

.subline-dots {
  position: absolute;
  width: 100%;
  height: 100%;
  background: radial-gradient(circle, #9c80f7 30%, transparent 30%);
  background-size: 12px 100%;
  background-position: 0 0;
  animation: dotsFlow 3s linear infinite;
}

@keyframes dotsFlow {
  0% { background-position-x: 0; }
  100% { background-position-x: 24px; }
}

/* Service card */
.services-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.service-card {
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 8px 25px rgba(156, 128, 247, 0.1);
  margin-bottom: 40px;
  overflow: hidden;
  transition: transform 0.3s, box-shadow 0.3s;
  border: 1px solid rgba(233, 76, 137, 0.1);
}

.service-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 30px rgba(156, 128, 247, 0.15);
}

.service-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 30px;
  background: linear-gradient(135deg, rgba(255,214,229,0.3) 0%, rgba(255,255,255,0.8) 100%);
  border-bottom: 1px solid rgba(233, 76, 137, 0.1);
}

.service-title {
  margin: 0;
  font-size: 1.4rem;
  color: #6a3093;
  display: flex;
  align-items: center;
  gap: 12px;
  font-weight: 600;
}

.badge {
  background: #9c80f7;
  color: white;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 0.85rem;
  font-weight: 500;
}

.btn-add-photo {
  color: #fff;
  background: #E94C89;
  border: none;
  padding: 8px 16px;
  border-radius: 8px;
  font-size: 0.9rem;
  transition: all 0.2s;
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 8px;
  box-shadow: 0 2px 8px rgba(233, 76, 137, 0.3);
}

.btn-add-photo:hover {
  background: #d43d7a;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(233, 76, 137, 0.4);
}

/* Photos grid */
.photos-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 25px;
  padding: 30px;
}

.photo-card {
  background: #fff;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 5px 15px rgba(156, 128, 247, 0.1);
  transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
  border: 1px solid rgba(233, 76, 137, 0.1);
}

.photo-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 25px rgba(156, 128, 247, 0.15);
}

.photo-preview {
  position: relative;
  height: 200px;
  overflow: hidden;
}

.photo-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s cubic-bezier(0.215, 0.61, 0.355, 1);
}

.photo-preview:hover .photo-image {
  transform: scale(1.05);
}

.photo-delete-btn {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  opacity: 0;
  transition: all 0.3s;
}

.photo-card:hover .photo-delete-btn {
  opacity: 1;
}

.btn-delete-round {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 50px;
  height: 50px;
  background: #ff3b30;
  border-radius: 50%;
  color: white;
  font-size: 1.2rem;
  transition: all 0.3s;
  text-decoration: none;
  box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.btn-delete-round:hover {
  background: #d32f2f;
  transform: scale(1.1);
}

/* Responsive */
@media (max-width: 768px) {
  .header-title {
    font-size: 1.8rem;
  }
  
  .service-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
    padding: 15px;
  }
  
  .photos-grid {
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 15px;
    padding: 15px;
  }
  
  .photo-preview {
    height: 160px;
  }
  
  .btn-add-photo {
    width: 100%;
    justify-content: center;
  }
}
</style>