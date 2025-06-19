<?php

use app\models\Zapis;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\models\TimeSlots;
use yii\bootstrap5\Modal;

/** @var yii\web\View $this */
/** @var app\models\ZapisSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Мои записи';
$this->registerJs(<<<JS
    $(document).on('click', '.cancel-btn', function(e) {
        e.preventDefault();
        var zapisId = $(this).data('id');
        $('#cancelModal').modal('show').data('id', zapisId);
    });
    
    // Добавляем обработчик для скрытия стандартного сообщения браузера
    $('#cancel-reason').on('invalid', function(e) {
        e.preventDefault();
        $(this).addClass('is-invalid');
        $('#cancel-reason-feedback').show();
    });
    
    // Добавляем обработчик при вводе текста
    $('#cancel-reason').on('input', function() {
        if ($(this).val().trim() !== '') {
            $(this).removeClass('is-invalid');
            $('#cancel-reason-feedback').hide();
        }
    });
    
    $('#confirmCancel').on('click', function() {
        var zapisId = $('#cancelModal').data('id');
        var reason = $('#cancel-reason').val().trim();
        var \$reasonField = $('#cancel-reason');
        
        // Проверяем заполненность поля
        if (!reason) {
            \$reasonField.addClass('is-invalid');
            $('#cancel-reason-feedback').show();
            \$reasonField.focus();
            return false;
        }
        
        $.ajax({
            url: '/zapis/index',
            type: 'POST',
            data: {
                id: zapisId,
                cancel_reason: reason
            },
            success: function(response) {
                if (response.success) {
                    $('#cancelModal').modal('hide');
                    location.reload();
                } else {
                    alert(response.error || 'Ошибка при отмене записи');
                }
            },
            error: function() {
                alert('Ошибка соединения с сервером');
            }
        });
    });
JS
);

// Массивы с русскими названиями месяцев
$russianMonths = [
    1 => 'Январь',
    2 => 'Февраль',
    3 => 'Март',
    4 => 'Апрель',
    5 => 'Май',
    6 => 'Июнь',
    7 => 'Июль',
    8 => 'Август',
    9 => 'Сентябрь',
    10 => 'Октябрь',
    11 => 'Ноябрь',
    12 => 'Декабрь'
];

$russianShortMonths = [
    1 => 'Января',
    2 => 'Февраля',
    3 => 'Марта',
    4 => 'Апреля',
    5 => 'Мая',
    6 => 'Июня',
    7 => 'Июля',
    8 => 'Августа',
    9 => 'Сентября',
    10 => 'Октября',
    11 => 'Ноября',
    12 => 'Декабря'
];
?>
<div class="zapis-index">

<!-- Модальное окно для отмены записи -->
<?php Modal::begin([
    'id' => 'cancelModal',
    'title' => 'Отмена записи',
    'footer' => Html::button('Отменить запись', ['class' => 'btn danger-btn', 'id' => 'confirmCancel']),
]); ?>

<div class="form-group">
    <label for="cancel-reason">Укажите причину отмены:</label>
    <textarea class="form-control" id="cancel-reason" name="cancel_reason" rows="3" required></textarea>
    <div id="cancel-reason-feedback" class="invalid-feedback" style="display: none;">
        Пожалуйста, укажите причину отмены.
    </div>
</div>

<?php Modal::end(); ?>

<div class="custom-header">
  <div class="header-content">
    <h1 class="header-title"><?= Html::encode($this->title) ?></h1>
    <div class="header-subline">
      <div class="subline-dots"></div>
    </div>
  </div>
</div>

<div class="history-timeline">
    <!-- <div class="timeline-progress">
        <div class="progress-bar"></div>
    </div> -->
    
    <div class="timeline-items">
        <?php if (count($dataProvider->getModels()) > 0): ?>
            <?php 
            $currentYear = null;
            $currentMonth = null;
            $firstItem = true;
            ?>
            
            <?php foreach ($dataProvider->getModels() as $index => $zapis): ?>
                <?php 
                $date = new DateTime($zapis->date);
                $year = $date->format('Y');
                $monthNum = (int)$date->format('n');
                $monthName = $russianMonths[$monthNum];
                $shortMonthName = $russianShortMonths[$monthNum];
                ?>
                
                <?php if ($year !== $currentYear): ?>
                    <div class="timeline-year-marker">
                        <span><?= $year ?> год</span>
                    </div>
                    <?php $currentYear = $year; ?>
                <?php endif; ?>
                
                <?php if ($monthNum != $currentMonth): ?>
                    <div class="timeline-month-marker">
                        <span><?= $monthName ?></span>
                    </div>
                    <?php $currentMonth = $monthNum; ?>
                <?php endif; ?>
                
                <div class="timeline-item <?= $firstItem ? 'first-item' : '' ?>">
                    <div class="timeline-dot 
                        <?= $zapis->status === 'Новый' ? 'status-new' : 
                           ($zapis->status === 'Завершён' ? 'status-completed' : 'status-cancelled') ?>">
                    </div>
                    
                    <div class="timeline-card">
                        <div class="card-header">
                            <div class="card-date">
                                <span class="day"><?= $date->format('d') ?></span>
                                <span class="month"><?= $shortMonthName ?></span>
                            </div>
                            
                            <div class="card-time">
                                <?php if ($zapis->timeSlot): ?>
                                    <?= date('H:i', strtotime($zapis->timeSlot->start_time)) ?>
                                <?php else: ?>
                                    Время не указано
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-status 
                                <?= $zapis->status === 'Новый' ? 'status-new' : 
                                   ($zapis->status === 'Завершён' ? 'status-completed' : 'status-cancelled') ?>">
                                <?= Html::encode($zapis->status) ?>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <div class="card-master">
                                <i class="fas fa-user"></i>
                                <span>
                                    <?php if ($zapis->master): ?>
                                        <?= Html::encode(trim(
                                            $zapis->master->last_name . ' ' . 
                                            $zapis->master->first_name
                                        )) ?>
                                    <?php else: ?>
                                        (Мастер не назначен)
                                    <?php endif; ?>
                                </span>
                            </div>
                            
                            <?php if ($zapis->status === 'Отменён' && $zapis->cancel_reason): ?>
                                <div class="cancel-reason">
                                    <strong>Причина отмены:</strong>
                                    <p><?= Html::encode($zapis->cancel_reason) ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-actions">
                                <?php if ($zapis->status === 'Новый'): ?>
                                    <?= Html::a(
                                        '<i class="fas fa-times"></i> Отменить',
                                        ['cancel', 'id' => $zapis->id],
                                        [
                                            'class' => 'btn danger-btn cancel-btn',
                                            'data' => [
                                                'id' => $zapis->id,
                                            ]
                                        ]
                                    ) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php $firstItem = false; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-calendar-plus empty-icon"></i>
                <h3>История записей пуста</h3>
                <p>У вас пока нет ни одной записи. Сделайте первую запись к мастеру!</p>
                <?= Html::a('Записаться', ['create'], ['class' => 'btn primary-btn']) ?>
            </div>
        <?php endif; ?>
    </div>
</div>

</div>

<style>
/* Base styles */
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');



/* Modern header */
.custom-header {
  text-align: center;
  margin: 0 auto 3px;
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

/* Timeline styles */
.history-timeline {
    max-width: 800px;
    margin: 0 auto;
    position: relative;
    padding: 0 20px;
}


.progress-bar {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 0;
    background: linear-gradient(to bottom, #6a3093, #E94C89);
    border-radius: 2px;
    transition: height 1s ease;
}

.timeline-items {
    position: relative;
    z-index: 2;
    padding-bottom: 50px;
}

.timeline-item {
    display: flex;
    margin-bottom: 30px;
    position: relative;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.5s ease;
}

.timeline-item.visible {
    opacity: 1;
    transform: translateY(0);
}

.timeline-item.first-item {
    opacity: 1;
    transform: translateY(0);
}

.timeline-dot {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    margin-right: 30px;
    flex-shrink: 0;
    position: relative;
    z-index: 2;
    border: 4px solid white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.timeline-dot.status-new {
    background: #1cc88a;
}

.timeline-dot.status-completed {
    background: #4e73df; 
}

.timeline-dot.status-cancelled {
    background: #e74a3b;
}

.timeline-month-marker {
    margin: 4px 0 20px 54px;
    font-size: 1.1rem;
    font-weight: 600;
    color: #6a3093;
    position: relative;
}

.timeline-month-marker span {
    background: white;
    padding: 5px 15px;
    border-radius: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.timeline-year-marker {
    margin: 6px 0 20px -35px;
    font-size: 1.4rem;
    font-weight: 700;
    color: #6a3093;
    position: relative;
}

.timeline-year-marker span {
    background: white;
    padding: 5px 20px;
    border-radius: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Timeline card */
.timeline-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    overflow: hidden;
    flex-grow: 1;
    transition: all 0.3s ease;
    border: 1px solid rgba(233, 76, 137, 0.1);
}

.timeline-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.card-header {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    background: linear-gradient(135deg, #f8f5ff 0%, #f0e9ff 100%);
    border-bottom: 1px solid rgba(156, 128, 247, 0.1);
}

.card-date {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-right: 20px;
    background: white;
    padding: 8px 12px;
    border-radius: 8px;
    min-width: 50px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.card-date .day {
    font-size: 1.4rem;
    font-weight: 700;
    color: #6a3093;
    line-height: 1;
}

.card-date .month {
    font-size: 0.7rem;
    text-transform: uppercase;
    color: #E94C89;
    font-weight: 600;
    letter-spacing: 1px;
}

.card-time {
    flex-grow: 1;
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
}

.card-status {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card-status.status-new {
    background-color:  #1cc88a;
    color: white;
}

.card-status.status-completed {
    background-color: #4e73df;
    color: white;
}

.card-status.status-cancelled {
    background-color: #e74a3b;
    color: white;
}

.card-body {
    padding: 20px;
}

.card-master {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    font-size: 1rem;
}

.card-master i {
    color: #E94C89;
    margin-right: 10px;
    width: 20px;
    text-align: center;
}

.cancel-reason {
    background-color: #fff5f5;
    border-left: 3px solid #e74a3b;
    padding: 10px 15px;
    margin: 15px 0;
    border-radius: 0 4px 4px 0;
}

.cancel-reason p {
    margin: 5px 0 0;
    color: #6c757d;
    font-size: 0.9rem;
}

.card-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

/* Buttons */
.btn {
    padding: 8px 16px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.85rem;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    border: none;
    cursor: pointer;
}

.primary-btn {
    background: #6a3093;
    color: white;
    box-shadow: 0 2px 8px rgba(106, 48, 147, 0.3);
}

.primary-btn:hover {
    background: #5a2580;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(106, 48, 147, 0.4);
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

.danger-btn {
    background: #fff5f5;
    color: #e74a3b;
    border: 1px solid rgba(231, 74, 59, 0.3);
}

.danger-btn:hover {
    background: #ffecec;
    box-shadow: 0 2px 8px rgba(231, 74, 59, 0.2);
    color: #e74a3b;
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 50px 20px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(156, 128, 247, 0.1);
    margin: 40px 0;
}

.empty-icon {
    font-size: 3rem;
    color: #E94C89;
    margin-bottom: 15px;
    opacity: 0.7;
}

.empty-state h3 {
    color: #6a3093;
    margin-bottom: 10px;
}

.empty-state p {
    color: #6c757d;
    margin-bottom: 20px;
}

/* Modal styles */
.modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}

.modal-header {
    background: linear-gradient(135deg, #f8f5ff 0%, #f0e9ff 100%);
    border-bottom: 1px solid rgba(156, 128, 247, 0.1);
    border-radius: 12px 12px 0 0;
    padding: 15px 20px;
}

.modal-title {
    font-weight: 600;
    color: #6a3093;
}

.modal-body {
    padding: 20px;
}

.form-group label {
    font-weight: 500;
    color: #555;
    margin-bottom: 8px;
    display: block;
}

.form-control {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 10px 15px;
    width: 100%;
    transition: all 0.3s;
}

.form-control:focus {
    border-color: #9c80f7;
    box-shadow: 0 0 0 0.2rem rgba(156, 128, 247, 0.25);
}

/* Validation styles */
.is-invalid {
    border-color: #e74a3b !important;
}

.invalid-feedback {
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: #e74a3b;
    display: none;
}

/* Responsive - Mobile Adaptations */
@media (max-width: 768px) {
    .header-title {
        font-size: 1.8rem;
    }
    
    .history-timeline {
        padding: 0 15px;
    }
    
    .timeline-dot {
        width: 20px;
        height: 20px;
        margin-right: 25px;
        margin-left: 8px;
    }
    
    .timeline-month-marker,
    .timeline-year-marker {
        margin-left: 44px;
    }
    
    .timeline-month-marker {
        font-size: 1rem;
    }
    
    .timeline-year-marker {
        font-size: 1.2rem;
    }
    
    .card-header {
        flex-wrap: wrap;
        gap: 10px;
        padding: 12px 15px;
    }
    
    .card-date {
        margin-right: 10px;
        padding: 6px 10px;
        min-width: 45px;
    }
    
    .card-date .day {
        font-size: 1.2rem;
    }
    
    .card-time {
        font-size: 1rem;
    }
    
    .card-status {
        padding: 4px 10px;
        font-size: 0.75rem;
    }
    
    .card-body {
        padding: 15px;
    }
    
    .card-master {
        font-size: 0.95rem;
    }
    
    .card-actions {
        flex-direction: column;
    }
    
    .btn {
        padding: 7px 12px;
        font-size: 0.8rem;
    }
}

@media (max-width: 480px) {
    .history-timeline {
        padding: 0 10px;
    }
    
    .timeline-progress {
        left: 30px;
    }
    
    .timeline-item {
        flex-direction: row;
        align-items: flex-start;
    }
    
    .timeline-dot {
        margin-bottom: 0;
        margin-right: 20px;
        margin-left: 10px;
    }
    
    .timeline-card {
        margin-left: -10px;
    }
    
    .empty-state {
        padding: 30px 15px;
    }
    
    .empty-icon {
        font-size: 2.5rem;
    }
    
    .empty-state h3 {
        font-size: 1.2rem;
    }
    
    .empty-state p {
        font-size: 0.9rem;
    }
}

@media (max-width: 360px) {
    .timeline-progress {
        left: 25px;
    }
    
    .timeline-dot {
        margin-right: 15px;
        margin-left: 5px;
    }
    
    .card-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .card-date {
        margin-bottom: 5px;
    }
}
</style>

<script>
// Анимация появления элементов и прогресс-бара
document.addEventListener('DOMContentLoaded', function() {
    const timelineItems = document.querySelectorAll('.timeline-item');
    const progressBar = document.querySelector('.progress-bar');
    
    // Анимация появления элементов с задержкой
    timelineItems.forEach((item, index) => {
        setTimeout(() => {
            item.classList.add('visible');
            
            // Обновление прогресс-бара
            if (index === timelineItems.length - 1) {
                progressBar.style.height = '100%';
            }
        }, 150 * index);
    });
    
    // Если только один элемент, заполняем прогресс-бар полностью
    if (timelineItems.length === 1) {
        progressBar.style.height = '100%';
    }
});
</script>