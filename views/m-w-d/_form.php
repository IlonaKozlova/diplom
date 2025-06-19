<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\MasterWorkingDays $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="master-working-days-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'master_id')->textInput() ?>

    <?= $form->field($model, 'is_working')->textInput() ?>

    <?= $form->field($model, 'monday')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tuesday')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'wednesday')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'thursday')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'friday')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'saturday')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sunday')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
