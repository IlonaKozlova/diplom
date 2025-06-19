<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\widgets\MaskedInput;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">
<!-- [ 'enableAjaxValidation' => true,] -->
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <!-- <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?> -->

    <!-- <?= $form->field($model, 'middle_name')->textInput(['maxlength' => true]) ?> -->

    <!-- <?= $form->field($model, 'description')->textarea(['rows' => 5]) ?> -->

    <?= $form->field($model, 'phone')->widget(MaskedInput::class, ['mask' => '+7 (999) 999-99-99',]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'autocomplete' => 'email',],) ?>

    <!-- <?= $form->field($model, 'profile_photo')->fileInput() ?> -->

    <!-- <?= $form->field($model, 'birth_date')->input('date', ['max' => date('Y-m-d', strtotime('-16 years')) ]) ?> -->

    <?= $form->field($model, 'login')->textInput(['maxlength' => true, 'autocomplete' => 'username',]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true, 'autocomplete' => 'current-password',]) ?>

    <?= $form->field($model, 'confirm_password')->passwordInput([]) ?>

    <!-- <= $form->field($model, 'created_at')->textInput() ?> -->

    <!-- <= $form->field($model, 'role')->dropDownList([ 'admin' => 'Admin', 'master' => 'Master', 'client' => 'Client', ], ['prompt' => '']) ?> -->


    <div class="form-group">
        <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
