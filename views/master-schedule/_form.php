<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\MasterSchedule $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="master-schedule-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- <= $form->field($model, 'master_id')->textInput() ?> -->

    <?= $form->field($model, 'day_of_week')->dropDownList(, ['prompt' => '']) ?>

    <?= $form->field($model, 'is_working')->textInput() ?>

    <?= $form->field($model, 'start_time')->textInput() ?>

    <?= $form->field($model, 'end_time')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
