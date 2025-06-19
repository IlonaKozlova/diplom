<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\models\MasterSchedule;
use app\models\Service;
use app\models\ZapisSlots;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var string $currentDay */
/** @var string $todayDate */
/** @var string $fullDate */
/** @var array $timeSlots */
/** @var array $masters */
/** @var int $selectedMasterId */
/** @var app\models\User $selectedMaster */

$this->title = 'Расписание мастера';
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="zapis-slots-view">

    <div class="container-fluid px-3 px-md-4 pt-3 pb-5">
        <!-- Панель управления расписанием -->
        <div class="bg-white rounded-3 shadow-sm p-3 mb-4">
            <div class="row g-3 align-items-center">
                <div class="col-md-5 order-2 order-md-1">
                    <div class="d-flex align-items-center">
                        <!-- Кнопка "Предыдущий день" -->
                        <a href="<?= Url::to(['view', 'id' => $selectedMasterId, 'date' => date('Y-m-d', strtotime($currentDate . ' -1 day'))]) ?>" 
                           class="btn btn-sm btn-outline-secondary  me-2">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                        
                        <div>
                            <h3 class="mb-0 fw-medium  me-2"><?= $currentDay ?>, <?= $todayDate ?></h3>
                        </div>
                        
                             <!-- Кнопка "Следующий день" -->
                        <a href="<?= Url::to(['view', 'id' => $selectedMasterId, 'date' => date('Y-m-d', strtotime($currentDate . ' +1 day'))]) ?>" 
                           class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="scheduler-container flex-grow-1">
            <!-- Расписание -->
            <div class="bg-white rounded-3 shadow-sm p-3">
                <div class="row g-0">
                    <div class="col-1"><div class="text-end pe-2">&nbsp;</div></div>
                    <div class="col-11">
                        <div class="mb-2 pb-2 border-bottom">
                            <div class="d-flex align-items-center ps-2">
<img src="<?= $master && $master->profile_photo ? $master->profile_photo : 'https://via.placeholder.com/45' ?>" class="rounded-circle border border-primary" style="width: 60px; height: 60px; object-fit: cover;">                                <div class="ms-2">
   <h4 class="mb-0 fw-semibold">
    <?= $master ? Html::encode($master->last_name . ' ' . $master->first_name . ' ' . $master->middle_name) : 'Неизвестный мастер' ?>
</h4>

<?= $isWorkingDay ?? false ? 'Рабочий день' : 'Выходной день' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
$hasRecords = !empty($zapisi);
                ?>

                <?php if (!$hasRecords): ?>
                    <div class="alert alert-info text-center m-4 py-3">
                        <i class="bi bi-calendar-x me-2"></i> Нет записей
                    </div>
                <?php else: ?>
                    <!-- Time Grid -->
                    <div class="d-flex position-relative time-grid">
                        <!-- Time Column -->
                        <div class="time-column border-end">
                            <?php for ($hour = 9; $hour <= 22; $hour++): ?>
                                <div class="time-slot position-relative">
                                    <div class="time-label fw-medium small text-secondary"><?= $hour ?><sup>00</sup></div>
                                </div>
                                <?php if ($hour < 22): ?>
                                    <div class="time-slot position-relative">
                                        <div class="time-half small text-secondary" style="top: -10px;">30</div>
                                    </div>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>

                        <!-- Appointments for master -->
                        <div class="stylist-column position-relative" style="width: calc(100% - 60px);">
                            <div class="appointment-container" style="top: 0; height: auto;">
                              <!-- Отображение перерывов мастера -->
            <?php if (!empty($master->breaks)): ?>
                <?php foreach ($master->breaks as $break): ?>
                    <?php 
                    // Calculate position based on time slot
                    $startTime = strtotime($break->timeSlot->start_time);
                    $hours = date('G', $startTime) - 9;
                    $minutes = date('i', $startTime);
                    $topPosition = $hours * 120 + ($minutes / 60 * 120);
                    
                    // Calculate duration in minutes (используем duration из модели)
                    $duration = $break->duration; // duration в минутах
                    $height = ($duration / 60) * 120;
                    ?>
                    
                    <div class="appointment appointment-orange p-2 mx-1 mb-2" 
                         style="position: absolute; top: <?= $topPosition ?>px; height: <?= $height ?>px; width: calc(100% - 8px);">
                        <div class="d-flex justify-content-between mb-1">
                            <div class="appointment-time"><?= Html::encode(date('H:i', strtotime($break->timeSlot->start_time))) ?></div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-warning text-dark">Перерыв</span> 
                            </div>
                        </div>
                        
                        <!-- Break info -->
                        <div class="client-name">Перерыв</div>
                        <?php if (!empty($break->comment)): ?>
                            <div class="service-name small"><?= Html::encode($break->comment) ?></div>
                        <?php endif; ?>
                        <div class="service-name small">
                            <!-- Длительность:  -->
                            <?php 
                            $hours = floor($break->duration / 60);
                            $minutes = $break->duration % 60;

                            if ($hours > 0) {
                                echo $hours . ' ' . Yii::t('app', '{n, plural, =1{час} one{час} few{часа} many{часов} other{часа}}', ['n' => $hours]);
                            }
                            if ($hours > 0 && $minutes > 0) {
                                echo ' ';
                            }
                            if ($minutes > 0) {
                                echo $minutes . ' мин.';
                            }
                            ?>
                        </div>         
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
                                <?php if (!empty($master->zapis)): ?>
                                    <?php foreach ($master->zapis as $zapis): ?>
                                        <?php 
                                        // Пропускаем отменённые записи
                                        if ($zapis->status === 'Отменён') {
                                            continue;
                                        }

                                        $startTime = $zapis->timeSlot ? strtotime($zapis->timeSlot->start_time) : 0;
                                        // Пропустить запись, если timeSlot отсутствует:
                                        if (!$zapis->timeSlot) {
                                            continue; 
                                        }
                                        $startTime = strtotime($zapis->timeSlot->start_time);
                                        $hours = date('G', $startTime) - 9;
                                        $minutes = date('i', $startTime);
                                        $topPosition = $hours * 120 + ($minutes / 60 * 120);
                                        
                                        $duration = $zapis->duration ?? 60; // минуты
                                        $height = ($duration / 60) * 120;

                                        
                                        $topPosition = max(0, $topPosition);
                                        ?>
                                        
                                        <div class="appointment appointment-green p-2 mx-1 mb-1" 
                                             style="position: absolute; 
                                                    top: <?= $topPosition ?>px; 
                                                    height: <?= $height ?>px; 
                                                    width: calc(100% - 16px);
                                                    box-sizing: border-box;">
                                            <div class="d-flex justify-content-between mb-1">
                                                <div class="appointment-time">
                                                    <?= date('H:i', $startTime) ?> 
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-success">Запись</span>
                                                </div>
                                            </div>
                                            
                                            <div class="client-name"><?= Html::encode($zapis->client->first_name) ?></div>
                                            
                                            <?php if (!empty($zapis->zapisServices)): ?>
                                                <div class="service-name small">
                                                    <?= implode(', ', array_map(function($s) { 
                                                        return Html::encode($s->service->name); 
                                                    }, $zapis->zapisServices)) ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($zapis->comment)): ?>
                                                <div class="small text-muted mt-1">
                                                    <i class="bi bi-chat-left-text"></i> <?= Html::encode($zapis->comment) ?>
                                                </div>
                                            <?php endif; ?>

                                            <?php if (Yii::$app->user->identity->role === 'admin'): ?>
                                            <div class="client-phone small mt-1">
                                                <i class="bi bi-telephone"></i> <?= Html::encode($zapis->client->phone) ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                    // echo "Текущее время: " . date('H:i');
                    
                    $current_hour = (int)date('G');
                    $current_min = (int)date('i');
$top_position = ($current_hour - 9) * 120 + ($current_min * 2);                    ?>

                    <div class="current-time-indicator" 
                         style="top: <?= $top_position ?>px;
                                display: <?= ($current_hour >= 9 && $current_hour < 22) ? 'block' : 'none' ?>;">
                    </div>
                    <!-- Отладочная информация -->
<!-- <div style="position: fixed; top: 10px; left: 10px; background: red; color: white; padding: 10px; z-index: 9999;">
    Текущее время: <?= $current_hour ?>:<?= $current_min ?><br>
    Top position: <?= $top_position ?> px -->
</div>
                <?php endif; ?>
                    </div>

                    
            </div>
        </div>
    </div>
</div>

<?php


// Встроенные стили
$this->registerCss(<<<CSS


      .appointment {
        border-radius: 6px;
        transition: all 0.2s ease;
        cursor: pointer;
        overflow: hidden;
        min-height: 120px; 
      }

      .appointment:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      }

      .appointment-green {
        background-color: #d1f7e6;
        border-left: 4px solid #0cbb7a;
      }

      .appointment-orange {
        background-color: #ffefda;
        border-left: 4px solid #ff9333;
      }

      .appointment-purple {
        background-color: #e6e1ff;
        border-left: 4px solid #8c82ff;
      }

      .time-column {
        width: 60px;
        min-width: 60px;
        position: relative;
      }

      .time-label {
        position: absolute;
        top: -10px;
        width: 100%;
        text-align: center;
      }

      .time-half {
        position: absolute;
        top: 50%;
        width: 100%;
        text-align: center;
      }

      .time-slot {
        height: 60px;
        border-top: 1px solid #e9ecef;
        position: relative;
      }

      .stylist-column {
        width: 220px;
        min-width: 180px;
        position: relative;
      }

      .stylist-header {
        height: 70px;
        display: flex;
        align-items: center;
        padding: 0 10px;
        background-color: #fff;
        border-bottom: 1px solid #e9ecef;
      }

      .avatar-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #adb5bd;
        font-size: 12px;
        margin-right: 10px;
      }

      .nav-date {
        display: flex;
        align-items: center;
        font-weight: 500;
      }

      .current-time-indicator {
        position: absolute;
        width: 100%;
        height: 2px;
        background-color: #dc3545;
        z-index: 10;
      }

      .current-time-indicator::before {
        content: '';
        position: absolute;
        left: 0;
        top: -4px;
        width: 10px;
        height: 10px;
        background-color: #dc3545;
        border-radius: 50%;
      }

      .date-nav-button {
        transition: background-color 0.2s ease;
      }

      .date-nav-button:hover {
        background-color: rgba(0, 0, 0, 0.05);
      }

      .status-icon {
        font-size: 12px;
      }

      .appointment-time {
        font-size: 12px;
        color: #6c757d;
        font-weight: 500;
      }

      .client-name {
        font-weight: 500;
      }

      .service-name {
        font-size: 13px;
      }

      .client-phone {
        font-size: 12px;
        color: #6c757d;
      }

      /* Custom scrollbar */
      .scheduler-container {
        scrollbar-width: thin;
        scrollbar-color: #ccc #f8f9fa;
      }

      .scheduler-container::-webkit-scrollbar {
        width: 8px;
        height: 8px;
      }

      .scheduler-container::-webkit-scrollbar-track {
        background: #f8f9fa;
      }

      .scheduler-container::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 4px;
      }

      /* Fix for appointment alignment */
      .time-grid {
        position: relative;
      }

      .appointment-container {
        position: absolute;
        width: calc(100% - 8px);
        width: 100%;
        left: 0;
      }
      
  
    .time-slot {
        height: 60px;
    }




@media (max-width: 767.98px) {
    /* 1. Общие стили для контейнера */
    .container-fluid.px-3.px-md-4 {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    
    /* 2. Корректировка высоты слотов и записей */
    .time-slot {
        height: 60px !important;
    }
    
    .appointment {
        min-height: 60px !important;
    }
    
    /* 3. Специальные стили для перерывов */
    .break-slot {
        height: calc(var(--break-duration, 60) * 1px) !important;
        min-height: calc(var(--break-duration, 60) * 1px) !important;
    }
    
    /* 4. Адаптация временной шкалы */
    .time-column {
        width: 40px !important;
        min-width: 40px !important;
    }
    
    .time-label {
        font-size: 0.8rem;
        right: 4px;
    }
    
    .time-half {
        font-size: 0.7rem;
        right: 4px;
    }
    
    /* 5. Область записей */
    .stylist-column {
        width: calc(100% - 40px) !important;
    }
    
    /* 6. Убираем лишние отступы */
    .bg-white.rounded-3.shadow-sm.p-3 {
        border-radius: 0 !important;
        padding: 0.75rem !important;
    }
}

@media (max-width: 575.98px) {
    /* Для очень маленьких экранов */
    .time-column {
        width: 36px !important;
    }
    
    .time-label, .time-half {
        font-size: 0.7rem;
        right: 2px;
    }
    
    /* Уменьшаем отступы в карточках */
    .appointment {
        padding: 0.5rem !important;
    }
    
    /* Корректировка высоты перерывов */
    .break-slot {
        height: calc(var(--break-duration, 60) * 0.9px) !important;
    }
}

@media (max-width: 575.98px) {
    /* Еще более компактный вид для очень маленьких экранов */
    .time-column {
        width: 35px !important;
        min-width: 35px !important;
    }
    
    .time-label, .time-half {
        font-size: 0.6rem;
    }
    
    .time-slot {
        height: 45px;
    }
    
    .appointment {
        min-height: 90px !important;
    }
    
    h4.fw-semibold {
        font-size: 1rem;
    }
    
    .bg-white.rounded-3.shadow-sm.p-3 {
        padding: 0.75rem !important;
    }
}

    
CSS
);
?>