<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ZapisSlots $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="zapis-slots-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'zapis_id')->textInput() ?>

    <?= $form->field($model, 'time_slots_id')->textInput() ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
