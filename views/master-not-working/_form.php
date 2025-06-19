<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\MasterNotWorking $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="master-not-working-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'master_id')->dropDownList($master, ['prompt' => 'Выберите мастера']) ?>

    <?= $form->field($model, 'date')->input('date', ['value' => $model->date  ? date('Y-m-d', strtotime($model->date)): '', 'min' => date('Y-m-d', strtotime('today midnight'))]) ?>

    <?= $form->field($model, 'time_slot_id')->dropDownList($timeslots, ['prompt' => 'Выберите время']) ?>

    <?= $form->field($model, 'duration')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    <?php if (!$model->isNewRecord): ?>  <!-- Показываем кнопку только при редактировании -->
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот перерыв?',
                'method' => 'post',
            ],
        ]) ?>
    <?php endif; ?>
</div>

    <?php ActiveForm::end(); ?>

</div>
