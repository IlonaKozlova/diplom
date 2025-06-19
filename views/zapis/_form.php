<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Zapis $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="zapis-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'services')->dropDownList($services, ['prompt' => 'Выберите услугу']) ?>

    <?= $form->field($model, 'master_id')->dropDownList($master, [
        'prompt' => 'Выберите мастера',
        'id' => 'master-select'
    ]) ?>

    <div class="form-group">
        <!-- <label class="control-label">Дни работы мастера:</label> -->
        <div id="working-days-info" style="padding: 8px 0; color:#9c80f7; font-weight: bold;">
            Выберите мастера, чтобы увидеть дни работы
        </div>
    </div>

    <?= $form->field($model, 'date')->input('date', [
        'value' => $model->date ? date('Y-m-d', strtotime($model->date)) : '',
        'min' => date('Y-m-d', strtotime('today midnight')),
        'id' => 'appointment-date'
    ]) ?>

    <?= $form->field($model, 'time_slots_id')->dropDownList($timeslots, ['prompt' => 'Выберите время']) ?>   

    <div class="form-group">
        <div id="time-slots-info" style="padding: 8px 0; color:#9c80f7; font-weight: bold;">
            Выберите услугу, мастера и дату, чтобы увидеть время работы
        </div>
    </div>
    
    <?= $form->field($model, 'client_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>

    <?= $form->field($model, 'comment')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Записаться', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$url = Url::to(['zapis/get-master-working-days']);
$script = <<< JS
$(document).ready(function() {
    $('#master-select').on('change', function() {
        var masterId = $(this).val();
        var workingDaysInfo = $('#working-days-info');
        
        if (masterId) {
            $.getJSON('$url', {masterId: masterId})
                .done(function(data) {
                    if (data.workingDays && data.workingDays.length > 0) {
                        $('#working-days-info').text('Работает в: ' + data.workingDays.join(', '));
                    } else {
                        $('#working-days-info').text('Информация о рабочих днях недоступна');
                    }
                })
                .fail(function() {
                    $('#working-days-info').text('Ошибка загрузки данных');
                });
        } else {
            $('#working-days-info').text('Выберите мастера, чтобы увидеть дни работы');
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
    // Обработчик изменения даты или мастера
    $('#master-select, #appointment-date').on('change', function() {
        var masterId = $('#master-select').val();
        var date = $('#appointment-date').val();
        var timeSlotsInfo = $('#time-slots-info');
        var timeSlotsSelect = $('#zapis-time_slots_id');
        
        // timeSlotsInfo.html('<i class="fa fa-spinner fa-spin"></i> Загрузка доступного времени...');
        
        if (masterId && date) {
            $.ajax({
                url: '$availableTimesUrl',
                type: 'GET',
                data: {masterId: masterId, date: date},
                dataType: 'json',
                success: function(data) {
                    console.log('Response data:', data); // Для отладки
                    
                    if (data.status === 'success') {
                        if (data.availableSlots && Object.keys(data.availableSlots).length > 0) {
                            // Очищаем и заполняем select
                            timeSlotsSelect.empty();
                            timeSlotsSelect.append($('<option>', {
                                value: '',
                                text: 'Выберите время'
                            }));
                            
                            $.each(data.availableSlots, function(id, time) {
                                timeSlotsSelect.append($('<option>', {
                                    value: id,
                                    text: time
                                }));
                            });
                            
                            var availableTimes = Object.values(data.availableSlots).join(', ');
// timeSlotsInfo.html('<i class="fa fa-check-circle" style="color:green"></i> Доступное время: ' + availableTimes);
                        } else {
                            timeSlotsSelect.empty().append($('<option>', {
                                value: '',
                                text: 'Нет доступных слотов'
                            }));
                            timeSlotsInfo.html('<i class="fa fa-info-circle" style="color:orange"></i> На выбранную дату нет свободных временных слотов');
                        }
                    } else {
                        timeSlotsInfo.html('<i class="fa fa-exclamation-circle" style="color:red"></i> ' + (data.message || 'Ошибка загрузки данных'));
                        console.error('Server error:', data);
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = 'Ошибка загрузки данных о времени: ' + error;
                    timeSlotsInfo.html('<i class="fa fa-exclamation-circle" style="color:red"></i> ' + errorMessage);
                    console.error('AJAX error:', status, error, xhr.responseText);
                }
            });
        } else {
            timeSlotsInfo.html('<i class="fa fa-info-circle" style="color:#9c80f7"></i> Выберите мастера и дату, чтобы увидеть время работы');
            timeSlotsSelect.empty().append($('<option>', {
                value: '',
                text: 'Выберите время'
            }));
        }
    });
});
JS;
$this->registerJs($additionalScript);
?>