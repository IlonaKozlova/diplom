<?php

use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Управление пользователями';
?>
<div class="user-index">

<div class="custom-header">
  <div class="header-content">
    <h1 class="header-title"><?= Html::encode($this->title) ?></h1>
    <div class="header-subline">
      <div class="subline-dots"></div>
    </div>
  </div>
</div>

<div class="card-container">
    <!-- <div class="card-actions">
        <?= Html::a('<i class="bi bi-plus-circle me-2"></i> Добавить пользователя', ['create'], [
            'class' => 'btn-add',
            'style' => 'background-color: #E94C89;'
        ]) ?>
    </div> -->

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?= $dataProvider->sort->link('role', ['label' => 'Роль']) ?></th>
                            <th>ФИО</th>
                            <!-- <th>Имя</th> -->
                            <th>Телефон</th>
                            <th>Email</th>
                            <th><?= $dataProvider->sort->link('birth_date', ['label' => 'Дата рождения']) ?></th>
                            <th class="text-center">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dataProvider->getModels() as $index => $model): ?>
                        <tr>
                            <td><?= $index + 1 + ($dataProvider->pagination->page * $dataProvider->pagination->pageSize) ?></td>
                            <td>
                                <?= match($model->role) {
                                    'admin' => '<span class="badge" style="background-color:rgb(255, 140, 140);">Админ</span>',
                                    'master' => '<span class="badge" style="background-color:rgb(255, 138, 222);">Мастер</span>',
                                    'client' => '<span class="badge" style="background-color:rgb(84, 181, 255);">Клиент</span>',
                                    default => $model->role
                                } ?>
                            </td>
                            <td><?= $model->last_name . ' ' . $model->first_name . ' ' . $model->middle_name ?></td>
                            <td>
                                <a href="tel:<?= Html::encode($model->phone) ?>" class="text-link">
                                    <i class="bi bi-telephone me-1"></i><?= Html::encode($model->phone) ?>
                                </a>
                            </td>
                            <td>
                                <a href="mailto:<?= Html::encode($model->email) ?>" class="text-link">
                                    <i class="bi bi-envelope me-1"></i><?= Html::encode($model->email) ?>
                                </a>
                            </td>
                            <td><?= Yii::$app->formatter->asDate($model->birth_date, 'php:d.m.Y') ?></td>
                            <td class="text-center">
                                <div class="action-buttons">
                                    <a href="<?= Url::to(['update', 'id' => $model->id]) ?>" 
                                       class="btn-action btn-edit"
                                       title="Редактировать">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="card-footer">
                <div class="footer-info">
                    Показано <?= $dataProvider->getCount() ?> из <?= $dataProvider->getTotalCount() ?> пользователей
                </div>
                
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <?= \yii\widgets\LinkPager::widget([
                            'pagination' => $dataProvider->pagination,
                            'options' => ['class' => 'pagination'],
                            'linkOptions' => ['class' => 'page-link'],
                            'firstPageLabel' => '<i class="bi bi-chevron-double-left"></i>',
                            'lastPageLabel' => '<i class="bi bi-chevron-double-right"></i>',
                            'prevPageLabel' => '<i class="bi bi-chevron-left"></i>',
                            'nextPageLabel' => '<i class="bi bi-chevron-right"></i>',
                            'activePageCssClass' => 'active',
                            'disabledListItemSubTagOptions' => ['class' => 'page-link'],
                            'disabledPageCssClass' => 'disabled',
                        ]) ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

</div>

<!-- Подключаем Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

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
  padding: 30px 20px 20px;
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

/* Card container */

.card-actions {
  margin-bottom: 20px;
  text-align: right;
}

.btn-add {
  color: #fff;
  background: #E94C89;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  font-size: 1rem;
  transition: all 0.2s;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  box-shadow: 0 2px 8px rgba(233, 76, 137, 0.3);
}

.btn-add:hover {
  background: #d43d7a;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(233, 76, 137, 0.4);
}

/* Card styles */
.card {
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 8px 25px rgba(156, 128, 247, 0.1);
  margin-bottom: 40px;
  overflow: hidden;
  transition: transform 0.3s, box-shadow 0.3s;
  border: 1px solid rgba(233, 76, 137, 0.1);
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 30px rgba(156, 128, 247, 0.15);
}

.card-body {
  padding: 0;
}

/* Table styles */
.table {
  width: 100%;
  border-collapse: collapse;
}

.table th {
  font-weight: 600;
  font-size: 0.9rem;
  color: #6a3093;
  padding: 15px;
  text-align: left;
  border-bottom: 2px solid rgba(233, 76, 137, 0.1);
  background-color: rgba(255, 214, 229, 0.2);
}

.table td {
  padding: 15px;
  border-bottom: 1px solid rgba(233, 76, 137, 0.1);
  color: #555;
}

.table tr:hover {
  background-color: rgba(255, 214, 229, 0.1);
}

/* Badge styles */
.badge {
  color: white;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 0.85rem;
  font-weight: 500;
  display: inline-block;
}

/* Link styles */
.text-link {
  color:rgb(25, 0, 150);
  text-decoration: none;
  transition: color 0.2s;
}

.text-link:hover {
  color: #E94C89;
}

/* Action buttons */
.action-buttons {
  display: flex;
  justify-content: center;
  gap: 10px;
}

.btn-action {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s;
}

.btn-edit {
  background-color: rgba(233, 76, 137, 0.1);
  color: #E94C89;
}

.btn-edit:hover {
  background-color: #E94C89;
  color: white;
  transform: scale(1.1);
}

/* .btn-delete {
  background-color: rgba(233, 76, 137, 0.1);
  color: #E94C89;
}

.btn-delete:hover {
  background-color: #E94C89;
  color: white;
  transform: scale(1.1);
} */

/* Card footer */
.card-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  border-top: 1px solid rgba(233, 76, 137, 0.1);
}

.footer-info {
  color: #777;
  font-size: 0.9rem;
}

/* Pagination */
.pagination {
  display: flex;
  gap: 5px;
}

.page-link {
  padding: 8px 12px;
  border-radius: 8px !important;
  color: #6a3093;
  border: 1px solid rgba(106, 48, 147, 0.2);
  transition: all 0.2s;
}

.page-link:hover {
  background-color: rgba(255, 138, 222, 0.6);
  color: #6a3093;
}

.page-item.active .page-link {
  background-color: #6a3093;
  border-color: #6a3093;
  color: white;
}

.page-item.disabled .page-link {
  color: #aaa;
  background-color: #f8f9fa;
}

/* Sort links */
a.sort-link {
  color: #6a3093;
  text-decoration: none;
  display: flex;
  align-items: center;
}

a.sort-link:hover {
  color: #E94C89;
}

.sort-icon {
  margin-left: 5px;
  font-size: 0.8rem;
}

/* Responsive */
@media (max-width: 768px) {
  .header-title {
    font-size: 1.8rem;
  }
  
  .card-actions {
    text-align: center;
  }
  
  .table {
    display: block;
    overflow-x: auto;
  }
  
  .card-footer {
    flex-direction: column;
    gap: 15px;
    text-align: center;
  }
  
  .pagination {
    flex-wrap: wrap;
    justify-content: center;
  }
}
</style>