<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\DopPhoto $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="dop-photo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'service_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'photo')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
