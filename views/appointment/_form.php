<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Appointment $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="appointment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'client_id')->textInput() ?>

    <?= $form->field($model, 'master_id')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList([ 'new' => 'New', 'completed' => 'Completed', 'canceled' => 'Canceled', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'cancel_reason')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'appointment_date')->textInput() ?>

    <?= $form->field($model, 'total_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
