<?php

use app\models\ZapisSlots;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ZapisSlotsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Расписание мастеров';
// $this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
// $this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css');
// $this->registerJsFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
// $this->registerCssFile('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap');

?>

<div class="zapis-slots-index">
    <style>
      /* Custom styles */
      body {
        background-color: #f8f9fa;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
      }

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
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
      }

      .scheduler-inner {
        display: inline-block;
        min-width: 100%;
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

      /* Mobile styles */
      @media (max-width: 768px) {
        .stylist-column {
            width: 180px;
            min-width: 180px;
        }
        
        .time-column {
            position: sticky;
            left: 0;
            background-color: #fff;
            z-index: 20;
        }
        
        .stylist-header {
            height: 60px;
            padding: 0 8px;
        }
        
        .avatar-placeholder {
            width: 30px;
            height: 30px;
            margin-right: 8px;
        }
        
        .appointment {
            min-height: 100px;
        }
        
        .client-name {
            font-size: 14px;
        }
        
        .service-name {
            font-size: 12px;
        }
      }

      @media (max-width: 768px) {
    /* Стили для кнопок на мобильных устройствах */
    .date-navigation-buttons .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
        margin-right: 0.3rem;
        margin-left: 0.3rem;
    }
    
    /* Уменьшаем отступы в навигации по датам */
    .date-navigation {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    /* Уменьшаем заголовок даты */
    .nav-date h3 {
        font-size: 1.1rem;
        margin: 0 0.5rem;
    }
}
    </style>

    <!-- Main Content -->
    <div class="container-fluid p-0 m-0 h-50 d-flex flex-column" style="height: calc(100vh - 60px);">
        <div class="bg-white shadow-sm rounded flex-grow-1 d-flex flex-column">
            
            <!-- Date Navigation -->
            <div class="d-flex align-items-center justify-content-between p-3 border-bottom bg-white rounded-3 shadow-sm mb-4">
                
                <!-- Левая часть: бургер + дата -->
                <div class="d-flex align-items-center">
                    <!-- Кнопка бургер -->
                    <!-- <button class="btn btn-sm p-2 date-nav-button rounded-circle me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                        <i class="bi bi-list"></i>
                    </button> -->

                    <!-- Кнопки навигации по дням -->
                    <a href="<?= Url::to(['', 'date' => date('Y-m-d', strtotime($currentDate . ' -1 day'))]) ?>" 
                       class="btn btn-sm btn-outline-secondary me-2">
                        <i class="bi bi-chevron-left"></i>
                    </a>

                    <div>
                        <h3 class="mb-0 fw-medium"><?= $currentDay ?>, <?= $todayDate ?></h3>
                    </div>

                    <a href="<?= Url::to(['', 'date' => date('Y-m-d', strtotime($currentDate . ' +1 day'))]) ?>" 
                       class="btn btn-sm btn-outline-secondary ms-2  me-2">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </div>

                <!-- Правая часть: кнопки действий -->
                <div class="d-flex align-items-center date-navigation-buttons">
                    <button class="btn btn-success me-2" onclick="window.location.href='/master-not-working/create'">
                        Перерыв
                    </button>

                    <button class="btn btn-primary me-2" onclick="window.location.href='/zapis/create'">
                        Запись
                    </button>
                </div>
            </div>

            <?php
            // Проверяем есть ли записи у мастеров
            $hasRecords = false;
            foreach ($masters as $master) {
                if (!empty($master->zapis)) {
                    $hasRecords = true;
                    break;
                }
            }
            ?>

            <?php if (!$hasRecords): ?>
                <div class="alert alert-info text-center m-4 py-3">
                    <i class="bi bi-calendar-x me-2"></i> Нет записей
                </div>
            <?php else: ?>
                <!-- Scheduler Grid -->
                <div class="scheduler-container flex-grow-1">
                    <div class="scheduler-inner">
                        <!-- Stylists Header -->
                        <div class="d-flex">
                            <div class="time-column border-end border-bottom"></div>
                            <?php foreach ($masters as $master): ?>
                                <div class="stylist-column border-end border-bottom">
                                    <div class="stylist-header" onclick="window.location.href='/zapis-slots/view?id=<?= $master->id ?>'" style="cursor: pointer;">                  
                                        <div class="avatar-placeholder">
                                            <?= Html::img(
                                                $master->profile_photo,
                                                [
                                                    'alt' => Html::encode($master->last_name . ' ' . $master->first_name),
                                                    'style' => 'width: 100%; height: 100%; object-fit: cover; border-radius: 50%;'
                                                ]
                                            ) ?>
                                        </div>
                                        <div>
                                            <div class="fw-medium">
                                                <?= Html::encode($master->last_name) ?> 
                                                <?= Html::encode($master->first_name) ?>
                                            </div>
                                            <?php 
                                            if (empty($workingDaysInfo[$master->id]['isWorkingDay'])) {
                                                echo "<div class='text-danger'>Не работает</div>"; 
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

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

                            <!-- Appointments for each master -->
                            <?php foreach ($masters as $masterId => $master): ?>
                                <div class="stylist-column border-end position-relative">
                                    <div class="appointment-container" style="top: 0px; height: auto;">
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
                                                     style="position: absolute; top: <?= $topPosition ?>px; height: <?= $height ?>px; width: calc(100% - 8px);"
                                                     onclick="window.location.href='<?= Url::to(['master-not-working/update', 'id' => $break->id]) ?>'">
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

                                        <!-- Отображение записей мастера -->
                                        <?php if (!empty($master->zapis)): ?>
                                            <?php foreach ($master->zapis as $zapis): ?>
                                                <?php 
                                                // Пропускаем отменённые записи
                                                if ($zapis->status === 'Отменён') {
                                                    continue;
                                                }
                                                // Calculate position based on time slot
                                                $startTime = $zapis->timeSlot ? strtotime($zapis->timeSlot->start_time) : 0;
                                                // Пропустить запись, если timeSlot отсутствует:
                                                if (!$zapis->timeSlot) {
                                                    continue; 
                                                }
                                                $startTime = strtotime($zapis->timeSlot->start_time);
                                                $hours = date('G', $startTime) - 9;
                                                $minutes = date('i', $startTime);
                                                $topPosition = $hours * 120 + ($minutes / 60 * 120);
                                                
                                                // Calculate total duration from services
                                                $totalDuration = 0;
                                                if (!empty($zapis->zapisServices)) {
                                                    foreach ($zapis->zapisServices as $zapisService) {
                                                        $totalDuration += $zapisService->service->duration_slots;
                                                    }
                                                }
                                                $height = ($totalDuration / 60) * 120;
                                                ?>
                                                
                                                <div class="appointment appointment-green p-2 mx-1 mb-2" 
                                                     style="position: absolute; top: <?= $topPosition ?>px; height: <?= $height ?>px; width: calc(100% - 8px); z-index: 1;"
     onclick="window.location.href='<?= Url::to(['zapis/update', 'id' => $zapis->id]) ?>'">
                                                    <div class="d-flex justify-content-between ">
                                                        <!-- mb-1 -->
                                                        <div class="appointment-time"><?= Html::encode(date('H:i', strtotime($zapis->timeSlot->start_time))) ?></div>
                                                        <span 
                                                            class="badge" 
                                                            style="
                                                                <?php if ($zapis->status === 'Завершён') { ?>
                                                                    background-color: #9c80f7; 
                                                                <?php } else { ?>
                                                                    background-color: #0cbb7a; 
                                                                <?php } ?>
                                                                color: white; 
                                                                
                                                            "
                                                        >
                                                            <?php if ($zapis->status === 'Завершён') { ?>
                                                                Завершён
                                                            <?php } else { ?>
                                                                Новый
                                                            <?php } ?>
                                                        </span>
                                                    </div>
                                                    
                                                    <!-- Client info -->
                                                    <div class="client-name"><?= Html::encode($zapis->client->first_name) ?></div>
                                                    
                                                    <!-- Services list -->
                                                    <?php if (!empty($zapis->zapisServices)): ?>
                                                        <?php foreach ($zapis->zapisServices as $zapisService): ?>
                                                            <div class="service-name small">
                                                                <?= Html::encode($zapisService->service->name) ?>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!empty($zapis->comment)): ?>
                                                        <div class="service-name small"><?= Html::encode($zapis->comment) ?></div>
                                                    <?php endif; ?>

                                                    <div class="service-name small"><?= Html::encode($zapis->client->phone) ?></div>
                                                    
                                                    <!-- Total duration -->
                                                    <div class="service-name small">
                                                        <?php 
                                                        $hours = floor($totalDuration / 60);
                                                        $minutes = $totalDuration % 60;

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
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <?php
                            // Расчет позиции для индикатора
                            $current_hour = (int)date('G');
                            $current_min = (int)date('i');
                            $top_position = ($current_hour - 9) * 120 + ($current_min / 60 * 120);
                            ?>

                            <div class="current-time-indicator" 
                                 style="top: <?= $top_position ?>px;
                                        display: <?= ($current_hour >= 9 && $current_hour < 22) ? 'block' : 'none' ?>;">
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>


</div>