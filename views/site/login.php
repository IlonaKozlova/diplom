<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\LoginForm $model */

$this->title = 'Вход в AVA Studio';

?>
<style>
    :root {
        --primary: #8A4FFF;
        --secondary: #FF6B9E;
        --light-bg: #FDFAFF;
    }
    
    .login-container {
        display: flex;
        /* min-height: 100vh; */
        align-items: center;
        flex-direction: column;
        padding: 0;
        margin: 0;
    }
    
    .login-image-col {
        flex: 0 0 auto;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 1rem;
        width: 100%;
    }
    
    .login-image {
        max-width: 150px;
        height: auto;
        animation: jackInTheBox 0.8s ease-in-out;
    }
    
    .login-form {
        flex: 1;
        padding: 1rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        width: 100%;
    }
    
    .form-card {
        background: white;
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 25px 50px -12px rgba(138, 79, 255, 0.08);
        max-width: 500px;
        margin: 0 auto;
        width: 100%;
        position: relative;
        overflow: hidden;
    }
    
    .form-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(138, 79, 255, 0.05) 0%, rgba(255, 255, 255, 0) 70%);
        z-index: 0;
    }
    
    .login-title {
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 1.5rem;
        text-align: center;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 1;
    }
    
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--primary);
    }
    
    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s;
    }
    
    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(138, 79, 255, 0.2);
        outline: none;
    }
    
    .btn-login {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        border: none;
        padding: 14px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        width: 100%;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 1rem;
    }
    
    .btn-login:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(138, 79, 255, 0.3);
    }
    
    .remember-me {
        display: flex;
        align-items: center;
        margin: 1rem 0;
    }
    
    .remember-me input {
        margin-right: 0.5rem;
    }
    
    .login-link {
        text-align: center;
        margin-top: 1.5rem;
        color: #64748b;
    }
    
    .login-link a {
        color: var(--primary);
        font-weight: 600;
        text-decoration: none;
    }
    
    @media (min-width: 992px) {
        .login-container {
            flex-direction: row;
        }
        
        .login-image-col {
            flex: 1;
            min-height: 700px;
            padding: 0;
        }
        
        .login-image {
            max-width: 80%;
        }
        
        .login-form {
            flex: 1;
            padding: 4rem;
        }
    }
    
    /* Анимации */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideUp {
        from { 
            opacity: 0;
            transform: translateY(20px);
        }
        to { 
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes jackInTheBox {
        from {
            opacity: 0;
            transform: scale(0.1) rotate(30deg);
            transform-origin: center bottom;
        }
        50% {
            transform: rotate(-10deg);
        }
        70% {
            transform: rotate(3deg);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>

<div class="login-container">
    <div class="login-image-col animate__animated animate__jackInTheBox">
        <img src="/images/кисточка.png" class="login-image" style="max-width: 80%; height: auto;">
    </div>
    
    <div class="login-form">
        <div class="form-card animate__animated animate__fadeIn">
            <h1 class="login-title animate__animated animate__fadeInDown">Вход</h1>
            
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'form-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'errorOptions' => ['class' => 'text-danger small']
                ],
            ]); ?>
            
            <div class="form-group animate__animated animate__fadeIn" style="animation-duration: 0.3s; animation-delay: 0.1s;">
                <?= $form->field($model, 'login')->textInput([
                    'autofocus' => true,
                    'placeholder' => 'Введите ваш логин'
                ]) ?>
            </div>
                
            <div class="form-group animate__animated animate__fadeIn" style="animation-duration: 0.3s; animation-delay: 0.4s;">
                <?= $form->field($model, 'password')->passwordInput([
                    'placeholder' => 'Введите пароль'
                ]) ?>
            </div>
                
            <div class="remember-me animate__animated animate__fadeIn" style="animation-duration: 0.3s; animation-delay: 0.9s;">
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
            </div>
                
            <div class="form-group animate__animated animate__fadeIn" style="animation-duration: 0.3s; animation-delay: 1.4s;">
                <?= Html::submitButton('Войти', [
                    'class' => 'btn-login',
                    'name' => 'login-button'
                ]) ?>
            </div>
            
            <div class="login-link animate__animated animate__fadeIn animate__delay-5s">
                Нет аккаунта? <?= Html::a('Зарегистрироваться', ['/user/create']) ?>
            </div>
            
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>