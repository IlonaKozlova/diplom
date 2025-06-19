<?php
/** @var yii\web\View $this */
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Расписание мастеров';
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<div class="master-not-working-mschedule container-fluid px-0 py-3 h-50 d-flex flex-column" style="height: calc(100vh - 60px);">
    <title><?= Html::encode($this->title) ?></title>
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
      
    </style>

<!-- Main Content -->
<div class="container-fluid p-0 m-0 h-50 d-flex flex-column" style="height: calc(100vh - 60px);">
  <div class="bg-white shadow-sm rounded flex-grow-1 d-flex flex-column">
    
    <!-- Date Navigation -->
    <div class="d-flex align-items-center justify-content-between p-3 border-bottom bg-white rounded-3 shadow-sm mb-4">
      
      <!-- Левая часть: бургер + дата -->
      <div class="d-flex align-items-center">
        <!-- Кнопка бургер -->
        <button class="btn btn-sm p-2 date-nav-button rounded-circle me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
          <i class="bi bi-list"></i>
        </button>

        <!-- Кнопки навигации по дням -->
        <a href="<?= Url::to(['', 'date' => date('Y-m-d', strtotime($currentDate . ' -1 day'))]) ?>" 
           class="btn btn-sm btn-outline-secondary me-2">
          <i class="bi bi-chevron-left"></i>
        </a>

       <div>   <!-- style="min-width: 20px;" -->
          <h3 class="mb-0 fw-medium"><?= $currentDay ?>, <?= $todayDate ?></h3>
        </div>

        <a href="<?= Url::to(['', 'date' => date('Y-m-d', strtotime($currentDate . ' +1 day'))]) ?>" 
           class="btn btn-sm btn-outline-secondary ms-2">
          <i class="bi bi-chevron-right"></i>
        </a>
      </div>

      <!-- Правая часть: кнопки действий -->
      <div class="d-flex align-items-center">
        <button class="btn btn-success me-2" onclick="window.location.href='/zapis/create'">
          <!-- data-bs-toggle="modal" data-bs-target="#appointmentModal" -->
          <i class="bi bi-plus-lg"></i> Новая запись
        </button>

        <div class="dropdown">
          <button class="btn btn-sm p-2 date-nav-button rounded-circle" type="button" data-bs-toggle="dropdown">
            <i class="bi bi-three-dots-vertical"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#"><i class="bi bi-printer me-2"></i>Печать</a></li>
            <li><a class="dropdown-item" href="#"><i class="bi bi-download me-2"></i>Экспорт</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Настройки</a></li>
          </ul>
        </div>
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
    <?php      else:?>


    
<!-- Scheduler Grid -->
<div class="scheduler-container flex-grow-1">
    <!-- Stylists Header -->
    <div class="d-flex">
        <div class="time-column border-end border-bottom"></div>
        <?php foreach ($masters as $master): ?>
            <div class="stylist-column border-end border-bottom">
<div class="stylist-header" onclick="window.location.href='/master-not-working/view?id=<?= $master->id ?>'" style="cursor: pointer;">                  
  <!-- data-bs-toggle="modal" data-bs-target="#masterModal<= $master->id?>" 
                   href='/zapis/view?id={$product->id}-->
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
                    <?php if (!empty($master->zapis)): ?>
                        <?php foreach ($master->zapis as $zapis): ?>
                            <?php 
                            // Calculate position based on time slot
                            $startTime = strtotime($zapis->timeSlot->start_time);
                            $hours = date('G', $startTime) - 9;
                            $minutes = date('i', $startTime);
                            $topPosition = $hours * 120 + ($minutes / 60 * 120);
                            
                            // Calculate duration in minutes
                            $endTime = strtotime($zapis->timeSlot->end_time);
                            $duration = ($endTime - $startTime) / 60;
                            $height = ($duration / 60) * 120;
                            ?>
                            
                            <div class="appointment appointment-green p-2 mx-1 mb-2" 
                                 style="position: absolute; top: <?= $topPosition ?>px; height: <?= $height ?>px; width: calc(100% - 8px);">
                                <div class="d-flex justify-content-between mb-1">
                                    <div class="appointment-time"><?= Html::encode($zapis->timeSlot->start_time) ?></div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-success">Запись</span>
                                    </div>
                                </div>
                                
                                <!-- Client info -->
                                <div class="client-name"><?= Html::encode($zapis->client->first_name) ?></div>
                                
                                <!-- Services list -->
                                <?php if (!empty($zapis->zapisServices)): ?>
                                    <?php foreach ($zapis->zapisServices as $zapisService): ?>
                                        <div class="service-name small"><?= Html::encode($zapisService->service->name) ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                
                                <?php if (!empty($zapis->zapisServices)): ?>
                                    <?php foreach ($zapis->zapisServices as $zapisService): ?>
                                        <div class="service-name small"><?= Html::encode($zapisService->zapis->comment) ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <?php if (!empty($zapis->zapisServices)): ?>
                                    <?php foreach ($zapis->zapisServices as $zapisService): ?>
                                        <div class="service-name small"><?= Html::encode($zapis->client->phone) ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>

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
<?php endif; ?>
    </div>
</div>





              <!-- Appointment 2 (11:00-12:00) -->
              <!-- <div class="appointment-container" style="top: 140px; height: 60px;">
                <div class="appointment appointment-orange h-100 p-2 mx-1">
                  <div class="d-flex justify-content-between mb-1">
                    <div class="appointment-time">11:00—12:00</div>
                    <div class="d-flex align-items-center">
                      <i class="bi bi-check-circle-fill text-success status-icon"></i>
                    </div>
                  </div>
                  <div class="service-name mb-1">Женская стрижка</div>
                  <div class="client-name">Ольга</div>
                </div>
              </div>
            </div> -->

            <!-- Stylist 2 Column -->
            <!-- <div class="stylist-column border-end position-relative"> -->
              <!-- Appointment 1 (11:00-12:00) -->
              <!-- <div class="appointment-container" style="top: 140px; height: 60px;">
                <div class="appointment appointment-orange h-100 p-2 mx-1">
                  <div class="d-flex justify-content-between mb-1">
                    <div class="appointment-time">11:00—12:00</div>
                    <div class="d-flex align-items-center">
                      <i class="bi bi-check-circle-fill text-success status-icon"></i>
                    </div>
                  </div>
                  <div class="service-name mb-1">Мужская модельная стрижка</div>
                  <div class="client-name">Алексей</div>
                </div>
              </div> -->

              <!-- Appointment 2 (14:30-15:30) -->
              <!-- <div class="appointment-container" style="top: 380px; height: 60px;">
                <div class="appointment appointment-purple h-100 p-2 mx-1">
                  <div class="d-flex justify-content-between mb-1">
                    <div class="appointment-time">14:30—15:30</div>
                    <div class="d-flex align-items-center">
                      <i class="bi bi-check-circle-fill text-success status-icon"></i>
                    </div>
                  </div>
                  <div class="service-name mb-1">Окрашивание</div>
                  <div class="client-name">Мария</div>
                </div>
              </div>
            </div> -->

            <!-- Current Time Indicator -->
<!-- Current Time Indicator -->
        </div>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <!-- <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title">Меню</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body">
        <div class="list-group list-group-flush">
          <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
            <i class="bi bi-calendar-week me-3"></i>
            Расписание
          </a>
          <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
            <i class="bi bi-people me-3"></i>
            Клиенты
          </a>
          <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
            <i class="bi bi-cash-coin me-3"></i>
            Финансы
          </a>
          <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
            <i class="bi bi-box-seam me-3"></i>
            Склад
          </a>
          <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
            <i class="bi bi-bar-chart me-3"></i>
            Аналитика
          </a>
        </div>
      </div>
    </div> -->

    <!-- Модальное окно мастера -->
    <?php foreach ($masters as $master): ?>
    <div class="modal fade" id="masterModal<?= $master->id ?>" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Новая запись для <?= Html::encode($master->last_name . ' ' . $master->first_name) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="/zapis/create" method="post">
                        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                        <input type="hidden" name="master_id" value="<?= $master->id ?>">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Клиент</label>
                                <div class="input-group">
                                    <input type="text" name="client_name" class="form-control" placeholder="Введите имя клиента" required>
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Телефон</label>
                                <input type="tel" name="phone" class="form-control" placeholder="+7 (___) ___-__-__" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Услуга</label>
                                <select class="form-select" name="service_id" required>
                                    <option selected disabled>Выберите услугу</option>
                                    <?php foreach ($services as $service): ?>
                                        <option value="<?= $service->id ?>"><?= Html::encode($service->name) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Дата</label>
                                <input type="date" name="date" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Комментарий</label>
                            <textarea class="form-control" name="comment" rows="3"></textarea>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                            <button type="submit" class="btn btn-success">Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>



    <!-- Модальное окно -->
    <div class="modal fade" id="appointmentModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Новая запись</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label">Клиент</label>
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Выберите клиента">
                    <button class="btn btn-outline-secondary" type="button">
                      <i class="bi bi-search"></i>
                    </button>
                  </div>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Телефон</label>
                  <input type="tel" class="form-control" placeholder="+7 (___) ___-__-__">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label">Услуга</label>
                  <select class="form-select">
                    <option selected disabled>Выберите услугу</option>
                    <option>Женская стрижка</option>
                    <option>Мужская стрижка</option>
                    <option>Окрашивание</option>
                    <option>Укладка</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Мастер</label>
                  <select class="form-select">
                    <option selected disabled>Выберите мастера</option>
                    <option>Ксения Манина</option>
                    <option>Диана Степанова</option>
                  </select>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label">Дата</label>
                  <input type="date" class="form-control">
                </div>
                <div class="col-md-3">
                  <label class="form-label">Начало</label>
                  <input type="time" class="form-control">
                </div>
                <div class="col-md-3">
                  <label class="form-label">Окончание</label>
                  <input type="time" class="form-control">
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Комментарий</label>
                <textarea class="form-control" rows="3"></textarea>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
            <button type="button" class="btn btn-success">Сохранить</button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
