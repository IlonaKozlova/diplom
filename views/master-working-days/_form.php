<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\MasterWorkingDays $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="master-working-days-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'master_id')->dropDownList($mast, ['prompt' => 'Выберите мастера']) ?>

    <!-- <?= $form->field($model, 'is_working')->textInput() ?> -->

    <?= $form->field($model, 'monday')->dropDownList(['0' => 'Выходной', '1' => 'Рабочий']) ?>

    <?= $form->field($model, 'tuesday')->dropDownList(['0' => 'Выходной', '1' => 'Рабочий']) ?>

    <?= $form->field($model, 'wednesday')->dropDownList(['0' => 'Выходной', '1' => 'Рабочий']) ?>

    <?= $form->field($model, 'thursday')->dropDownList(['0' => 'Выходной', '1' => 'Рабочий']) ?>

    <?= $form->field($model, 'friday')->dropDownList(['0' => 'Выходной', '1' => 'Рабочий']) ?>

    <?= $form->field($model, 'saturday')->dropDownList(['0' => 'Выходной', '1' => 'Рабочий']) ?>

    <?= $form->field($model, 'sunday')->dropDownList(['0' => 'Выходной', '1' => 'Рабочий']) ?>

    <?= $form->field($model, 'comment')->textInput(['maxheight' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
