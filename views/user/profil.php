<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Мой профиль';
?>
<div class="user-profile">

<div class="custom-header">
  <div class="header-content">
    <h1 class="header-title"><?= Html::encode($this->title) ?></h1>
    <div class="header-subline">
      <div class="subline-dots"></div>
    </div>
  </div>
</div>

<div class="profile-container">
    <div class="row">
        <div class="col-md-4">
            <div class="profile-card">
                <div class="profile-photo-container">
                    <div class="photo-wrapper">
                        <img src="<?= $model->profile_photo ?>" class="profile-photo" alt="Фото профиля">
                        <div class="photo-overlay">
                            <div class="photo-edit-btn">
                                <i class="bi bi-camera-fill"></i>
                                <span>Изменить фото</span>
                            </div>
                        </div>
                    </div>
                    <h3 class="profile-name"><?= Html::encode($model->last_name.' '.$model->first_name.' '.$model->middle_name) ?></h3>
                    <?php if (!empty($model->email)): ?>
                        <div class="profile-contact">
                            <i class="bi bi-envelope-fill"></i>
                            <?= Html::encode($model->email) ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($model->phone)): ?>
                        <div class="profile-contact">
                            <i class="bi bi-telephone-fill"></i>
                            <?= Html::encode($model->phone) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="profile-form-card">
                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
                
                <div class="row">
                    <div class="col-md-4">
                        <?= $form->field($model, 'last_name')->textInput(['class' => 'form-control modern-input']) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'first_name')->textInput(['class' => 'form-control modern-input']) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'middle_name')->textInput(['class' => 'form-control modern-input']) ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'email')->textInput(['type' => 'email', 'class' => 'form-control modern-input']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'phone')->textInput(['type' => 'tel', 'class' => 'form-control modern-input']) ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'birth_date')->input('date', [
                            'max' => date('Y-m-d', strtotime('-16 years')),
                            'class' => 'form-control modern-input'
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'login')->textInput(['class' => 'form-control modern-input']) ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'password')->passwordInput([
                            'maxlength' => true, 
                            'autocomplete' => 'current-password',
                            'class' => 'form-control modern-input'
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'confirm_password')->passwordInput([
                            'class' => 'form-control modern-input'
                        ]) ?>
                    </div>
                </div>
                
                <div class="form-group photo-upload-group">
                    <label class="photo-upload-label">
                        <span class="upload-icon"><i class="bi bi-cloud-arrow-up-fill"></i></span>
                        <span class="upload-text">Загрузить новое фото профиля</span>
                        <?= $form->field($model, 'profile_photo')->fileInput([
                            'class' => 'photo-upload-input',
                            'accept' => 'image/*'
                        ])->label(false) ?>
                    </label>
                    <div class="photo-upload-hint">Фото будет сохранено только после нажатия кнопки "Сохранить изменения"</div>
                </div>
                
                <div class="form-group text-end mt-4">
                    <?= Html::submitButton('Сохранить изменения', ['class' => 'btn btn-save']) ?>
                </div>
                
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

</div>

<style>
.custom-header {
  text-align: center;
  margin: 0 auto 30px;
  padding: 15px 0 2px;
  position: relative;
}

.header-content {
  position: relative;
  z-index: 2;
}

.header-title {
  font-size: 2.2rem;
  color: #E94C89;
  margin: 0 0 15px;
  font-weight: 700;
  text-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.header-subline {
  width: 120px;
  height: 6px;
  margin: 0 auto;
  position: relative;
}

.subline-dots {
  position: absolute;
  width: 100%;
  height: 100%;
  background: radial-gradient(circle, #9c80f7 30%, transparent 30%);
  background-size: 12px 100%;
  background-position: 0 0;
  animation: dotsFlow 3s linear infinite;
}

@keyframes dotsFlow {
  0% { background-position-x: 0; }
  100% { background-position-x: 24px; }
}

/* Profile container */
.profile-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 15px;
}

/* Profile card */
.profile-card {
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 8px 25px rgba(156, 128, 247, 0.1);
  padding: 20px;
  height: 100%;
  border: 1px solid rgba(233, 76, 137, 0.1);
}

.profile-photo-container {
  text-align: center;
}

/* Увеличенные стили для фото профиля */
.photo-wrapper {
  position: relative;
  width: 320px;
  height: 320px;
  margin: 0 auto 25px;
  border-radius: 50%;
  overflow: hidden;
  border: 5px solid #fff;
  box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.photo-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,0.4);
  opacity: 0;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.photo-wrapper:hover .photo-overlay {
  opacity: 1;
}

.photo-edit-btn {
  display: flex;
  flex-direction: column;
  align-items: center;
  color: white;
  text-align: center;
  cursor: pointer;
  transform: translateY(20px);
  transition: all 0.3s ease;
}

.photo-wrapper:hover .photo-edit-btn {
  transform: translateY(0);
}

.photo-edit-btn i {
  font-size: 2.5rem;
  margin-bottom: 8px;
  background: rgba(233, 76, 137, 0.9);
  width: 70px;
  height: 70px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.photo-edit-btn span {
  font-size: 1.1rem;
  font-weight: 500;
  text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.profile-photo {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: all 0.3s;
}

.profile-photo-preview {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 50%;
  border: 3px solid #9c80f7;
  box-sizing: border-box;
}

.photo-wrapper:hover .profile-photo {
  transform: scale(1.05);
}

.profile-name {
  font-size: 1.5rem;
  color: #E94C89;
  margin-bottom: 20px;
  font-weight: 600;
}

.profile-contact {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 10px;
  color: #555;
  font-size: 0.95rem;
}

.profile-contact i {
  color: #9c80f7;
  padding: 0 7px;
}

/* Form card */
.profile-form-card {
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 8px 25px rgba(156, 128, 247, 0.1);
  padding: 30px;
  height: 100%;
  border: 1px solid rgba(233, 76, 137, 0.1);
}

.modern-input {
  border-radius: 8px;
  border: 1px solid #e0e0e0;
  padding: 12px 15px;
  transition: all 0.3s;
}

.modern-input:focus {
  border-color: #9c80f7;
  box-shadow: 0 0 0 0.25rem rgba(156, 128, 247, 0.25);
}

/* Photo upload */
.photo-upload-group {
  margin-top: 30px;
}

.photo-upload-label {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 30px;
  border: 2px dashed #e0e0e0;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s;
  text-align: center;
}

.photo-upload-label:hover {
  border-color: #9c80f7;
  background: rgba(156, 128, 247, 0.05);
}

.photo-upload-hint {
  font-size: 0.85rem;
  color: #6c757d;
  text-align: center;
  margin-top: 10px;
}

.upload-icon {
  font-size: 2.5rem;
  color: #9c80f7;
  margin-bottom: 10px;
}

.upload-text {
  color: #555;
  font-size: 1rem;
}

.photo-upload-input {
  display: none;
}

/* Buttons */
.btn-save {
  background: #6a3093;
  color: white;
  border: none;
  padding: 12px 30px;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 500;
  transition: all 0.3s;
  box-shadow: 0 4px 15px rgba(106, 48, 147, 0.3);
}

.btn-save:hover {
  background:rgb(139, 65, 192);
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(106, 48, 147, 0.4);
}





/* Responsive */
@media (max-width: 992px) {
  .profile-card {
    margin-bottom: 30px;
  }
  
  .photo-wrapper {
    width: 280px;
    height: 280px;
  }
}

@media (max-width: 768px) {
  .header-title {
    font-size: 1.8rem;
  }

    .btn-save {
    padding: 10px 20px;
    font-size: 0.95rem;
    width: 100%;
    max-width: none;
    margin-bottom: 10px;
  }
  
  .profile-name {
    font-size: 1.3rem;
  }
  
  .photo-wrapper {
    width: 240px;
    height: 240px;
  }
  
  .photo-edit-btn i {
    width: 60px;
    height: 60px;
    font-size: 2rem;
  }
  
  .photo-edit-btn span {
    font-size: 1rem;
  }
  
  .profile-container {
    padding: 0 10px;
  }
  
  .profile-card,
  .profile-form-card {
    padding: 20px 15px;
  }
}

@media (max-width: 576px) {
  .profile-contact {
    padding: 0 20px; 
    word-break: break-all; 
  }
    
  .photo-wrapper {
    width: 300px;
    height: 300px;
  }
  
  .profile-container {
    padding: 0 5px;
  }
}

@media (min-width: 810px) and (max-width: 830px) and (min-height: 1170px) and (max-height: 1190px) {
  /* Общие стили для мобильного вида */
  .profile-container {
    padding: 0 10px;
    max-width: 100%;
  }
  
  .row {
    flex-direction: column;
    margin: 0;
  }
  
  .col-md-4, .col-md-8 {
    width: 100%;
    padding: 0;
  }
  
  /* Шапка */
  .custom-header {
    margin-bottom: 15px;
    padding-top: 10px;
  }
  
  .header-title {
    font-size: 1.6rem;
    margin-bottom: 8px;
  }
  
  .header-subline {
    width: 90px;
    height: 4px;
  }
  
  /* Карточка профиля */
  .profile-card {
    margin-bottom: 20px;
    padding: 15px;
    border-radius: 12px;
  }
  
  .photo-wrapper {
    width: 180px;
    height: 180px;
    margin-bottom: 15px;
  }
  
  .photo-edit-btn i {
    width: 50px;
    height: 50px;
    font-size: 1.5rem;
  }
  
  .photo-edit-btn span {
    font-size: 0.9rem;
  }
  
  .profile-name {
    font-size: 1.2rem;
    margin-bottom: 15px;
  }
  
  .profile-contact {
    font-size: 0.85rem;
    justify-content: center;
  }
  
  /* Форма профиля */
  .profile-form-card {
    padding: 15px;
    border-radius: 12px;
  }
  
  .modern-input {
    padding: 10px 12px;
    font-size: 0.9rem;
    margin-bottom: 10px;
  }
  
  /* Загрузка фото */
  .photo-upload-group {
    margin-top: 20px;
  }
  
  .photo-upload-label {
    padding: 20px;
  }
  
  .upload-icon {
    font-size: 2rem;
  }
  
  .upload-text {
    font-size: 0.9rem;
  }
  
  /* Кнопки */
  .btn-save {
    padding: 10px 20px;
    font-size: 0.95rem;
    width: 100%;
    max-width: none;
  }
  
  /* Адаптация полей формы */
  .row > div {
    margin-bottom: 5px;
  }
  
  .form-group {
    margin-bottom: 15px;
  }
  
  /* Специфичные стили для этого разрешения */
  body {
    font-size: 14px;
  }
  
  h1, h2, h3 {
    line-height: 1.3;
  }
  
  .form-control {
    height: auto;
    padding: 8px 12px;
  }
  
  .btn {
    padding: 8px 16px;
  }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Photo upload preview
    const photoInput = document.querySelector('.photo-upload-input');
    const profilePhoto = document.querySelector('.profile-photo');
    const photoWrapper = document.querySelector('.photo-wrapper');
    const originalPhotoSrc = profilePhoto.src;
    
    if (photoInput && profilePhoto) {
        photoInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                // Удаляем старое превью, если есть
                const oldPreview = photoWrapper.querySelector('.profile-photo-preview');
                if (oldPreview) {
                    oldPreview.remove();
                }
                
                const reader = new FileReader();
                
                reader.onload = function(event) {
                    // Создаем элемент для превью
                    const preview = document.createElement('img');
                    preview.src = event.target.result;
                    preview.className = 'profile-photo-preview';
                    
                    // Вставляем превью
                    photoWrapper.appendChild(preview);
                    
                    // Скрываем оригинальное фото
                    profilePhoto.style.opacity = '0.5';
                }
                
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    }
    
    // При отправке формы восстанавливаем оригинальное фото, если форма не отправлена
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            // При успешной отправке превью станет основным фото автоматически
        });
        
        
    }
    
    // Trigger file input when edit button clicked
    const editBtn = document.querySelector('.photo-edit-btn');
    if (editBtn && photoInput) {
        editBtn.addEventListener('click', function() {
            photoInput.click();
        });
    }
});
</script>