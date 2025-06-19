<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $masters app\models\User[] */

$this->title = 'Наши мастера';
?>

<div class="user-masters container py-4">
    <div class="custom-header">
        <div class="header-content">
            <h1 class="header-title"><?= Html::encode($this->title) ?></h1>
            <div class="header-subline">
                <div class="subline-dots"></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <?php foreach ($masters as $master): ?>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm rounded-3 border-0 master-card">
                <!-- Внутренняя рамка для фото -->
                <div class="m-1 mb-0 rounded-3 photo-frame">
                    <a href="/user/view?id=<?= $master->id ?>" class="d-block p-2 photo-link">
                        <img src="<?= $master->profile_photo ?>" 
                             class="card-img-top img-thumbnail d-block mx-auto master-photo"
                             style="max-width: 100%; height: auto; object-fit: cover;"
                             alt="Фото профиля">
                    </a>
                </div>
                
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-center fs-5 master-name">
                        <?= Html::encode($master->last_name.' '.$master->first_name.' '.$master->middle_name) ?>
                    </h5>
                    
                    <?php if (!empty($master->description)): ?>
                    <p class="card-text text-muted mb-3 master-description">
                        <?php 
                        $desc = Html::encode($master->description);
                        echo mb_strlen($desc) > 200 ? mb_substr($desc, 0, 200).'...' : $desc;
                        ?>
                    </p>
                    <?php endif; ?>
                    
                    <a href="/user/view?id=<?= $master->id ?>" 
                       class="btn btn-primary w-100 mt-auto py-2 master-btn">
                        Подробнее
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="btn-arrow">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
/* Modern header */
.custom-header {
  text-align: center;
  margin-bottom: 2rem;
  padding: 0 20px;
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

/* Card styles */
.master-card {
  transition: all 0.3s ease;
  border: none;
}

.master-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(106, 48, 147, 0.15) !important;
}

.photo-frame {
  transition: all 0.3s ease;
  background: white;
  overflow: hidden;
}

.master-card:hover .photo-frame {
  transform: scale(0.98);
}

.photo-link {
  transition: all 0.3s ease;
}

.master-card:hover .photo-link {
  transform: scale(1.02);
}

.master-photo {
  transition: all 0.5s ease;
}

.master-card:hover .master-photo {
  transform: scale(1.05);
}

.master-name {
  color: #6a3093;
  font-weight: 600;
  transition: color 0.3s ease;
}

.master-card:hover .master-name {
  color: #E94C89;
}

.master-description {
  transition: color 0.3s ease;
}

.master-card:hover .master-description {
  color: #555;
}

.master-btn {
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
  border: none;
  background: linear-gradient(45deg,rgb(251, 78, 141) 0%,rgb(32, 255, 255) 100%);
  transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  background-size: 200% 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.master-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgb(76, 194, 233);
  background-position: 100% 0;
  background:rgb(63, 150, 250);
}

.btn-arrow {
  width: 18px;
  height: 18px;
  transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.master-btn:hover .btn-arrow {
  transform: translateX(4px);
}

/* Responsive */
@media (max-width: 768px) {
  .header-title {
    font-size: 1.8rem;
  }
}

@media (max-width: 576px) {
  .header-title {
    font-size: 1.6rem;
  }
}
</style>