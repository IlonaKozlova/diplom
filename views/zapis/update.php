<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
/** @var yii\web\View $this */
/** @var app\models\Zapis $model */

$this->title = 'Редактировать' //. $model->id;
// $this->params['breadcrumbs'][] = ['label' => 'Zapis', 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
// $this->params['breadcrumbs'][] = 'Update';
?>
<div class="zapis-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>


<!-- Мастер (только для чтения) -->
<div class="form-group">
    <label class="control-label">Мастер</label>
    <div class="form-control" style="background-color: #f8f9fa;">
        <?= Html::encode($model->master->last_name . ' ' . $model->master->first_name) ?>
    </div>
    <?= Html::activeHiddenInput($model, 'master_id') ?>
</div>

<!-- Услуги (только для чтения) -->
<div class="form-group">
    <label class="control-label">Услуги</label>
    <div class="form-control" style="background-color: #f8f9fa; min-height: 38px;">
        <?php 
        $servicesList = [];
        foreach ($model->zapisServices as $zapisService) {
            $servicesList[] = $zapisService->service->name;
        }
        echo Html::encode(implode(', ', $servicesList));
        ?>
    </div>
    <?= Html::activeHiddenInput($model, 'services', [
        'value' => implode(',', ArrayHelper::getColumn($model->zapisServices, 'service_id'))
    ]) ?>
</div>

<div class="form-group"> 
    <div id="working-days-info" style="padding: 8px 0; color:#9c80f7; font-weight: bold;">
        <?= $workingDaysInfo ?>
    </div>
</div>

    <?= $form->field($model, 'date')->input('date', [
        'value' => $model->date ? date('Y-m-d', strtotime($model->date)) : '',
        // 'min' => date('Y-m-d', strtotime('today midnight')),
        'id' => 'appointment-date'
    ]) ?>

    <?= $form->field($model, 'time_slots_id')->dropDownList($timeslots, ['prompt' => 'Выберите время']) ?>   

    <!-- <div class="form-group">
        <div id="time-slots-info" style="padding: 8px 0; color:#9c80f7; font-weight: bold;">
            Выберите услугу, мастера и дату, чтобы увидеть время работы
        </div>
    </div> -->
    
    <?= $form->field($model, 'client_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>

    <?= $form->field($model, 'status')->dropDownList(['Новый' => 'Новый', 'Завершён' => 'Завершён', 'Отменён' => 'Отменён'], ['id' => 'status-select']) ?>

<div id="cancel-reason-container" style="display: none;">
    <?= $form->field($model, 'cancel_reason')->textInput() ?>
</div>

    <?= $form->field($model, 'comment')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>



</div>

<?php
   $availableTimesUrl = Url::to(['zapis/get-available-time-slots']);
    $initialDate = $model->date ? date('Y-m-d', strtotime($model->date)) : '';
    $additionalScript = <<< JS
    $(document).ready(function() {
        var masterId = $('#zapis-master_id').val();
        var dateInput = $('#appointment-date');
        var timeSlotsInfo = $('#time-slots-info');
        var timeSlotsSelect = $('#zapis-time_slots_id');
        var initialTimeSlot = '$initialTimeSlot';
        var initialTimeSlotText = '$initialTimeSlotText';
        var initialDate = '$initialDate';
        
        function loadAvailableTimes() {
            var date = dateInput.val();
            
            if (masterId && date) {
                $.ajax({
                    url: '$availableTimesUrl',
                    type: 'GET',
                    data: {masterId: masterId, date: date},
                    dataType: 'json',
                    success: function(data) {
                        timeSlotsSelect.empty();
                        
                        // Добавляем текущий слот первым, если дата не изменилась date === initialDate && 
                        if (initialTimeSlot) {
                            timeSlotsSelect.append($('<option>', {
                                value: initialTimeSlot,
                                text: initialTimeSlotText,
                                selected: true
                            }));
                        }
                        
                        // Добавляем опцию выбора
                        timeSlotsSelect.append($('<option>', {
                            value: '',
                            text: 'Выберите время'
                        }));
                        
                        // Добавляем доступные слоты
                        if (data.status === 'success' && data.availableSlots) {
                            $.each(data.availableSlots, function(id, time) {
                                if (id != initialTimeSlot) { // Не добавляем текущий слот повторно
                                    timeSlotsSelect.append($('<option>', {
                                        value: id,
                                        text: time
                                    }));
                                }
                            });
                            // timeSlotsInfo.html('Доступное время загружено');
                        } else {
                            timeSlotsInfo.html(data.message || 'Нет доступных слотов');
                        }
                    },
                    error: function() {
                        timeSlotsInfo.html('Ошибка загрузки данных о времени');
                    }
                });
            } else {
                timeSlotsInfo.html('Выберите дату, чтобы увидеть доступное время');
            }
        }
        
        dateInput.on('change', loadAvailableTimes);
        
        // Загружаем сразу, если дата уже выбрана
        if (initialDate) {
            loadAvailableTimes();
        }
    });
    JS;
    $this->registerJs($additionalScript);
?>


<?php
$script = <<< JS
$(document).ready(function() {
    // Проверяем начальное состояние
    if ($('#status-select').val() === 'Отменён') {
        $('#cancel-reason-container').show();
    }
    
    // Обработчик изменения статуса
    $('#status-select').change(function() {
        if ($(this).val() === 'Отменён') {
            $('#cancel-reason-container').show();
        } else {
            $('#cancel-reason-container').hide();
        }
    });
});
JS;
$this->registerJs($script);
?>