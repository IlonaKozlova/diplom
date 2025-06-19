<?php
use app\models\MasterSchedule;
use app\models\Service;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var string $currentDay */
/** @var string $todayDate */
/** @var string $fullDate */
/** @var array $timeSlots */

$this->title = 'Расписание мастера';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- <title><= Html::encode($this->title) ?> | BeautySalon</title> -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    body {
        font-family: 'Montserrat', sans-serif;
    }
    .time-slot {
        height: 30px;
    }
</style>
</head>
<body class="bg-light">
  <div class="d-flex flex-column min-vh-100">

    <!-- Основное содержимое -->
    <main class="flex-grow-1">
      <div class="container-fluid px-3 px-md-4 pt-3 pb-5">
        
        <!-- Панель управления расписанием -->
        <div class="bg-white rounded-3 shadow-sm p-3 mb-4">
          <div class="row g-3 align-items-center">
            <!-- Навигация по датам -->
            <div class="col-md-5 order-2 order-md-1">
              <div class="d-flex align-items-center">
                <button class="btn btn-sm btn-outline-secondary" id="prevDay">
                  <i class="bi bi-chevron-left"></i>
                </button>
                <div class="mx-3" style="min-width: 230px;">
                  <h3 class="mb-0 fw-medium"><?= $currentDay ?>, <?= $todayDate ?></h3>
                </div>
                <button class="btn btn-sm btn-outline-secondary" id="nextDay">
                  <i class="bi bi-chevron-right"></i>
                </button>
                <!-- <button class="btn btn-sm btn-outline-secondary ms-2" id="todayBtn">
                  Сегодня
                </button> -->
              </div>
            <!-- </div> -->
            
            <!-- Выбор мастера -->
            <div class="col-md-4 order-1 order-md-2">                
              <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role === 'admin'): ?>

              <select class="form-select" id="masterSelect">            
              <?php foreach ($masters as $master): ?>
                  <option value="<?= $master->id ?>" <?= $selectedMasterId == $master->id ? 'selected' : '' ?>>
                  <?= Html::encode($master->last_name) ?> <?= Html::encode($master->first_name) ?> <?= Html::encode($master->middle_name) ?>        </option>
              <?php endforeach; ?>
              </select>
            </div>
            <!-- Управление видом -->
            <div class="col-md-3 order-3 text-md-end">
              <div class="d-flex justify-content-end">
                <button class="btn btn-sm btn-primary ms-2" id="newAppointmentBtn">
                  <i class="bi bi-plus-lg me-1"></i>Запись
                </button>        
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Временная шкала расписания -->
        <div class="bg-white rounded-3 shadow-sm p-3">
          <div class="row g-0">
            <div class="col-1">
              <div class="text-end pe-2">&nbsp;</div>
            </div>
            <div class="col-11">
              <div class="mb-2 pb-2 border-bottom">
                <div class="d-flex align-items-center ps-2">
                  <img src="<?= $selectedMaster->profile_photo ?: 'https://via.placeholder.com/45' ?>" 
                       class="rounded-circle border border-primary" 
                       style="width: 60px; height: 60px; object-fit: cover;">
                  <div class="ms-2">
                    <h4 class="mb-0 fw-semibold"><?= Html::encode($selectedMaster->last_name) ?> <?= Html::encode($selectedMaster->first_name) ?> <?= Html::encode($selectedMaster->middle_name) ?> </h4>   
                    <?= Html::encode($selectedMaster->login) ?>  
                </option>

                    <!-- <p class="text-muted mb-0 small"><?= Html::encode($selectedMaster->role) ?></p> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- 5-минутные слоты -->
          <div>
            <?php foreach ($timeSlots as $time => $slot): ?>
              <div class="border-bottom">
                <div class="row g-0">
                  <div class="col-1">
                    <div class="text-end pe-2 small text-muted">
                      <?= date('i', strtotime($time)) % 15 == 0 ? date('H:i', strtotime($time)) : '' ?>
                    </div>
                  </div>
                  <div class="col-11">
                    <div class="position-relative <?= $slot['type'] === 'appointment' ? 'appointment-slot' : 'time-slot' ?>">
                      <div class="h-100 w-100 rounded-1 
                          <?= $slot['type'] === 'appointment' ? 'bg-danger-subtle border-danger-subtle' : '' ?>
                          <?= $slot['type'] === 'break' ? 'bg-body-tertiary border-secondary-subtle' : '' ?>
                          <?= $slot['type'] === 'available' ? 'bg-body-tertiary border-body-tertiary' : '' ?>">
                        <?php if ($slot['type'] === 'appointment'): ?>
                          <div class="d-flex flex-column p-1 h-100">
                            <div class="fw-semibold small"><?= $slot['service'] ?></div>
                            <div class="text-muted small"><?= $slot['client'] ?></div>
                          </div>
                        <?php elseif ($slot['type'] === 'break'): ?>
                          <div class="d-flex align-items-center justify-content-center h-100">
                            <span class="text-muted small"><i class="bi bi-cup-hot me-1"></i>Перерыв</span>
                          </div>
                        <?php elseif ($slot['type'] === 'available'): ?>
                          <div class="h-100 w-100" style="cursor: pointer;" 
                               onclick="handleTimeSlotClick('<?= date('H:i', strtotime($time)) ?>')"></div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </main>
  </div>

  <!-- Модальное окно новой записи -->
  <div class="modal fade" id="newAppointmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Новая запись</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="appointmentForm">
            <!-- Форма записи -->
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
          <button type="button" class="btn btn-primary" id="saveAppointment">Сохранить</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function handleTimeSlotClick(time) {
      document.getElementById('appointmentTime').value = time + ':00';
      const modal = new bootstrap.Modal(document.getElementById('newAppointmentModal'));
      modal.show();
    }

    document.addEventListener('DOMContentLoaded', function() {
      // Навигация по дням
      document.getElementById('prevDay').addEventListener('click', function() {
        // Логика перехода на предыдущий день
      });
      
      document.getElementById('nextDay').addEventListener('click', function() {
        // Логика перехода на следующий день
      });
      
      document.getElementById('todayBtn').addEventListener('click', function() {
        // Логика перехода на сегодня
      });
      
      // Выбор мастера
      document.getElementById('masterSelect').addEventListener('change', function() {
        window.location.href = '<?= Url::to(['schedule/now']) ?>?master_id=' + this.value;
      });
      
      // Новая запись
      document.getElementById('newAppointmentBtn').addEventListener('click', function() {
        const modal = new bootstrap.Modal(document.getElementById('newAppointmentModal'));
        modal.show();
      });
      
      // Сохранение записи
      document.getElementById('saveAppointment').addEventListener('click', function() {
        // Логика сохранения записи
        alert('Запись сохранена!');
        const modal = bootstrap.Modal.getInstance(document.getElementById('newAppointmentModal'));
        modal.hide();
      });
    });
  </script>
</body>
</html>