<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\widgets\MaskedInput;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var yii\widgets\ActiveForm $form */

$this->title = 'Регистрация в AVA Studio';
?>
<style>
    :root {
        --primary: #8A4FFF;
        --secondary: #FF6B9E;
        --light-bg: #FDFAFF;
        --text: #3A3A3A;
    }
    
    body {
        background-color: #FAF9FE;
        font-family: 'Montserrat', sans-serif;
        color: var(--text);
        line-height: 1.6;
        padding: 20px;
    }
    
    .form-card {
        background: white;
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 25px 50px -12px rgba(138, 79, 255, 0.15);
        max-width: 600px;
        width: 100%;
        margin: 0px auto;
        border: 1px solid rgba(138, 79, 255, 0.1);
    }
    
    .form-title {
        font-size: 2rem;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 30px;
        text-align: center;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .form-group {
        margin-bottom: 25px;
    }
    
    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--primary);
        font-size: 0.95rem;
    }
    
    .form-control {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s;
        background-color: var(--light-bg);
    }
    
    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(138, 79, 255, 0.15);
        outline: none;
        background-color: white;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        border: none;
        padding: 16px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        width: 100%;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 10px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    
    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(138, 79, 255, 0.25);
    }
    
    .login-link {
        text-align: center;
        margin-top: 25px;
        color: #64748b;
        font-size: 0.95rem;
    }
    
    .login-link a {
        color: var(--primary);
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .login-link a:hover {
        text-decoration: underline;
    }
    
    @media (max-width: 576px) {
        body {
            padding: 10px;
        }
        
        .form-card {
            padding: 30px 20px;
            margin: 20px auto;
        }
        
        .form-title {
            font-size: 1.8rem;
            margin-bottom: 20px;
        }
        
        .form-control {
            padding: 12px 15px;
        }
    }
</style>

<div class="form-card">
    <h1 class="form-title">Регистрация</h1>
    
    <?php $form = ActiveForm::begin([
        'id' => 'register-form',
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            // 'labelOptions' => ['class' => 'form-label'],
            // 'inputOptions' => ['class' => 'form-control'],
            // 'errorOptions' => ['class' => 'text-danger small']
        ]
    ]); ?>
    
    <?= $form->field($model, 'first_name')->textInput([
        'maxlength' => true,
        'placeholder' => 'Введите ваше имя'
    ]) ?>
    
    <?= $form->field($model, 'phone')->widget(MaskedInput::class, [
        'mask' => '+7 (999) 999-99-99',
        'options' => [
            'class' => 'form-control',
            'placeholder' => '+7 (___) ___-__-__'
        ]
    ]) ?>
    
    <!-- <?= $form->field($model, 'email')->textInput([
        'maxlength' => true,
        'autocomplete' => 'email',
        'placeholder' => 'example@mail.ru'
    ]) ?> -->
    
    <?= $form->field($model, 'login')->textInput([
        'maxlength' => true,
        'autocomplete' => 'username',
        'placeholder' => 'Придумайте логин'
    ]) ?>
    
    <?= $form->field($model, 'password')->passwordInput([
        'maxlength' => true,
        'autocomplete' => 'new-password',
        'placeholder' => 'Введите пароль'
    ]) ?>
    
    <?= $form->field($model, 'confirm_password')->passwordInput([
        'placeholder' => 'Повторите пароль'
    ]) ?>
    
    <div class="form-group">
        <?= Html::submitButton('Зарегистрироваться', [
            'class' => 'btn-submit',
            'name' => 'register-button'
        ]) ?>
    </div>
    
    <div class="login-link">
        Уже есть аккаунт? <?= Html::a('Войти', ['site/login']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>

