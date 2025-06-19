<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Zapis $model */
/** @var array $master */
/** @var array $services */
/** @var array $timeslots */

$this->title = 'Новая запись';
?>

<style>

    
    .zapis-create {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .page-header {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .page-header h1 {
        color: #E94C89;
        font-weight: 700;
        margin-bottom: 15px;
    }
    
    .zapis-form {
        padding: 30px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }
    
    .form-header {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .form-header h2 {
        color: #6a3093;
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 1.5rem;
    }
    
 
    
    .form-group {
        
        margin-bottom: 5px;
    }
    
    .form-label {
        font-weight: 600;
        color:rgb(62, 66, 71);
        margin-bottom: 8px;
        display: block;
    }
    
    .form-control {
        height: 48px;
        border-radius: 10px;
        border: 1px solid #e0e0e0;
        padding: 12px 15px;
        font-size: 0.95rem;
        transition: all 0.3s;
    }
    
    .form-control:focus {
        border-color: #9c80f7;
        box-shadow: 0 0 0 0.2rem rgba(156, 128, 247, 0.25);
    }
    
    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236a3093' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
        background-size: 16px 12px;
    }
    
    .info-box {
        background-color: #f8f5ff;
        border-left: 3px solid #9c80f7;
        padding: 12px 15px;
        border-radius: 0 8px 8px 0;
        margin: 15px 0;
        font-size: 0.9rem;
    }
    
    .info-box i {
        color: #9c80f7;
        margin-right: 8px;
    }
    
    .btn-primary {
        margin-top: 15px;
        background: linear-gradient(135deg,rgb(218, 77, 180) 0%, #9c80f7 100%);
        border: none;
        padding: 12px 25px;
        font-weight: 600;
        letter-spacing: 0.5px;
        border-radius: 10px;
        width: 100%;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(106, 48, 147, 0.2);
    }
    
    .btn-primary:hover {
        background-color:rgb(131, 113, 251);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(106, 48, 147, 0.3);
    }
    
    .form-row {
        display: flex;
        gap: 20px;
    }
    
    .form-row .form-group {
        flex: 1;
        margin-bottom: 0px;
    }
    
    @media (max-width: 768px) {
        .zapis-create {
            padding: 0px;
        }
        
        .zapis-form {
            padding: 20px;
        }
        
        .form-row {
            flex-direction: column;
            gap: 0;
        }
         .btn-primary {
        margin-top: 15px;
         }
    }
</style>

<div class="zapis-create">
    <div class="zapis-form">
        <div class="form-header">
            <div class="page-header">
                <h1><?= Html::encode($this->title) ?></h1>
                <p>Выберите услугу, мастера и удобное время!</p>
            </div>
        </div>

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'services')->dropDownList($services, [
            'prompt' => 'Выберите услугу',
            'class' => 'form-control',
            'id' => 'service-select'
        ]) ?>

        <?= $form->field($model, 'master_id')->dropDownList($master, [
            'prompt' => 'Выберите мастера',
            'id' => 'master-select',
            'class' => 'form-control'
        ]) ?>

        <div class="info-box" id="working-days-info">
            <i class="fas fa-info-circle"></i> Выберите мастера, чтобы увидеть дни работы
        </div>

        <div class="form-row">
            <div class="form-group">
                <?= $form->field($model, 'date')->input('date', [
                    'value' => $model->date ? date('Y-m-d', strtotime($model->date)) : '',
                    'min' => date('Y-m-d', strtotime('today midnight')),
                    'id' => 'appointment-date',
                    'class' => 'form-control'
                ]) ?>
            </div>
            
            <div class="form-group">
                <?= $form->field($model, 'time_slots_id')->dropDownList($timeslots, [
                    'prompt' => 'Выберите время',
                    'class' => 'form-control',
                    'id' => 'time-slot-select'
                ]) ?>
            </div>
        </div>

        <?= $form->field($model, 'client_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>
        <?= $form->field($model, 'comment')->textInput(['class' => 'form-control', 'placeholder' => 'Ваши пожелания (необязательно)']) ?>

        <div class="form-group">
            <?= Html::submitButton('<i class="fas fa-calendar-check"></i> Записаться', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$url = Url::to(['zapis/get-master-working-days']);
$script = <<< JS
$(document).ready(function() {
    $('#master-select').on('change', function() {
        var masterId = $(this).val();
        var workingDaysInfo = $('#working-days-info');
        
        if (masterId) {
            workingDaysInfo.html('<i class="fas fa-spinner fa-spin"></i> Загрузка информации...');
            
            $.getJSON('$url', {masterId: masterId})
                .done(function(data) {
                    if (data.workingDays && data.workingDays.length > 0) {
                        workingDaysInfo.html('<i class="fas fa-calendar-alt"></i> Работает в: ' + data.workingDays.join(', '));
                    } else {
                        workingDaysInfo.html('<i class="fas fa-calendar-times"></i> Информация о рабочих днях недоступна');
                    }
                })
                .fail(function() {
                    workingDaysInfo.html('<i class="fas fa-exclamation-triangle"></i> Ошибка загрузки данных');
                });
        } else {
            workingDaysInfo.html('<i class="fas fa-info-circle"></i> Выберите мастера, чтобы увидеть дни работы');
        }
    });
});
JS;
$this->registerJs($script);
?>

<?php
$availableTimesUrl = Url::to(['zapis/get-available-times']);
$additionalScript = <<< JS
$(document).ready(function() {
    function updateTimeSlots() {
        var masterId = $('#master-select').val();
        var date = $('#appointment-date').val();
        var serviceId = $('#service-select').val();
        var timeSlotsSelect = $('#time-slot-select');
        
        if (masterId && date && serviceId) {
            timeSlotsSelect.html('<option value="">Загрузка времени...</option>');
            
            $.ajax({
                url: '$availableTimesUrl',
                type: 'GET',
                data: {masterId: masterId, date: date, serviceId: serviceId},
                dataType: 'json',
                success: function(data) {
                    timeSlotsSelect.empty();
                    if (data.status === 'success') {
                        if (data.availableSlots && Object.keys(data.availableSlots).length > 0) {
                            timeSlotsSelect.append('<option value="">Выберите время</option>');
                            $.each(data.availableSlots, function(id, time) {
                                timeSlotsSelect.append('<option value="' + id + '">' + time + '</option>');
                            });
                        } else {
                            timeSlotsSelect.append('<option value="">Нет доступного времени</option>');
                        }
                    } else {
                        timeSlotsSelect.append('<option value="">' + (data.message || 'Ошибка загрузки') + '</option>');
                    }
                },
                error: function() {
                    timeSlotsSelect.empty().append('<option value="">Ошибка соединения</option>');
                }
            });
        } else {
            timeSlotsSelect.empty().append('<option value="">Выберите мастера, дату и услугу</option>');
        }
    }

    $('#service-select, #master-select, #appointment-date').on('change', updateTimeSlots);
    updateTimeSlots(); // Инициализация при загрузке
});
JS;
$this->registerJs($additionalScript);
?>