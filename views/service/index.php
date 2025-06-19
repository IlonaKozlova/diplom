<?php
use app\models\Service;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\ServiceSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Наши услуги';
?>
<div class="service-index">

<div class="custom-header">
  <div class="header-content">
    <h1 class="header-title"><?= Html::encode($this->title) ?></h1>
    <div class="header-subline">
      <div class="subline-dots"></div>
    </div>
  </div>
</div>

<div class="services-filter">
    <?php
    $sort = $dataProvider->getSort();
    $attributes = [
        'price' => 'По цене',
        'name' => 'По названию',
    ];
    foreach ($attributes as $attribute => $label) {
        $direction = $sort->attributeOrders[$attribute] ?? SORT_ASC;
        echo Html::a(
            "$label " . ($direction === SORT_ASC ? '↑' : '↓'),
            [$sort->createUrl($attribute)],
            ['class' => 'filter-btn']
        );
    }
    ?>

      <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'): ?>              
        <a href="<?= Url::to(['/service/create']) ?>" class="add-button">
            <i class="bi bi-plus"></i>
        </a>
      <?php endif; ?>
</div>

<div class="services-grid">
    <?php 
    $models = $dataProvider->getModels();
    foreach ($models as $service): ?>
        <div class="service-card">
            <!-- <div class="service-image-container">
                <?php if ($service->photo): ?>
                    <img src="<?= Html::encode($service->photo) ?>" alt="<?= Html::encode($service->name) ?>" class="service-image">
                <?php endif; ?>
            </div> -->
            
            <div class="service-content">
                <h3 class="service-name"><?= Html::encode($service->name) ?></h3>
                <p class="service-description"><?= Html::encode($service->description) ?></p>
                <span class="service-price"><?= Html::encode($service->price) ?> руб.</span>
                <div class="service-footer">
                    
                    
                    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'client'): ?>
                      <?= Html::a('Подробнее', ['/service/view', 'id' => $service->id], ['class' => 'service-btn secondary-btn']) ?>
                        <!-- <?= Html::a('Записаться', ['/zapis/create', 'service_id' => $service->id], ['class' => 'service-btn primary-btn']) ?> -->
                    <?php else: ?>
                        <?= Html::a('Подробнее', ['/service/view', 'id' => $service->id], ['class' => 'service-btn secondary-btn']) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

</div>

<style>
.add-button {
    background: #ff76aa;
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    text-decoration: none;
    transition: all 0.3s;
    box-shadow: 0 2px 8px rgba(255, 118, 170, 0.3);
}

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

/* Services filter */
.services-filter {
  display: flex;
  justify-content: center;
  gap: 15px;
  margin-bottom: 30px;
  flex-wrap: wrap;
}

.filter-btn {
  padding: 8px 16px;
  border-radius: 8px;
  background: white;
  color: #6a3093;
  border: 1px solid rgba(156, 128, 247, 0.3);
  text-decoration: none;
  font-size: 0.9rem;
  transition: all 0.2s;
}

.filter-btn:hover {
  background: #f8f5ff;
  box-shadow: 0 2px 8px rgba(156, 128, 247, 0.2);
}

/* Services grid */
.services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 30px;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

/* Service card */
.service-card {
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 8px 25px rgba(156, 128, 247, 0.1);
  overflow: hidden;
  transition: transform 0.3s, box-shadow 0.3s;
  border: 1px solid rgba(233, 76, 137, 0.1);
  display: flex;
  flex-direction: column;
}

.service-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 30px rgba(156, 128, 247, 0.15);
}

.service-image-container {
  height: 200px;
  overflow: hidden;
}

.service-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.service-card:hover .service-image {
  transform: scale(1.05);
}

.service-content {
  padding: 20px;
  flex-grow: 1;
  display: flex;
  flex-direction: column;
}

.service-name {
  margin: 0 0 10px;
  font-size: 1.3rem;
  color: #6a3093;
  font-weight: 600;
}

.service-description {
  margin: 0 0 15px;
  color: #666;
  flex-grow: 1;
}

.service-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 15px;
}

.service-price {
  font-weight: 700;
  color: #E94C89;
  font-size: 1.1rem;
}

.service-btn {
  padding: 8px 16px;
  border-radius: 8px;
  text-decoration: none;
  font-size: 0.9rem;
  transition: all 0.2s;
}

.primary-btn {
  background: #E94C89;
  color: white;
  box-shadow: 0 2px 8px rgba(233, 76, 137, 0.3);
}

.primary-btn:hover {
  background: #d43d7a;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(233, 76, 137, 0.4);
  color: white;
}

.secondary-btn {
  background: white;
  color: #6a3093;
  border: 1px solid rgba(156, 128, 247, 0.3);
}

.secondary-btn:hover {
  background: #f8f5ff;
  box-shadow: 0 2px 8px rgba(156, 128, 247, 0.2);
  color: #6a3093;
}

/* Responsive */
@media (max-width: 768px) {
  .header-title {
    font-size: 1.8rem;
  }
  
  .services-grid {
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
  }
  
  .service-image-container {
    height: 180px;
  }
}

@media (max-width: 480px) {
  .services-grid {
    grid-template-columns: 1fr;
  }
  
  .service-footer {
    flex-direction: column;
    gap: 10px;
    align-items: flex-start;
  }
  
  .service-btn {
    width: 100%;
    text-align: center;
  }
}
</style>