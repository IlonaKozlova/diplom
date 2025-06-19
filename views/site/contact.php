<?php
/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'AVA Studio | О нас';
?>

<style>
    /* Базовые настройки */
    :root {
        --primary: #8A4FFF;
        --secondary: #FF6B9E;
        --text: #3A3A3A;
        --light-bg: #FDFAFF;
    }
    
    body {
        line-height: 1.7;
    }
    
    /* Основной белый блок */
    .luxury-card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(138, 79, 255, 0.08);
        padding: 60px;
        margin: 40px auto;
        max-width: 1200px;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(138, 79, 255, 0.1);
        margin: 15px auto;
         
        
    }
    
    .luxury-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(138, 79, 255, 0.05) 0%, rgba(255, 255, 255, 0) 70%);
        z-index: 0;
    }
    
    /* Типографика */
    .page-header {
        text-align: center;
        margin-bottom: 20px;
        position: relative;
    }
    
    .page-title {
        font-size: 3.2rem;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 15px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        position: relative;
        display: inline-block;
    }
    
    .page-subtitle {
        font-size: 1.2rem;
        color: var(--primary);
        font-weight: 500;
        letter-spacing: 1px;
        position: relative;
        display: inline-block;
    }
    
    .page-subtitle::after {
        content: '';
        position: absolute;
        bottom: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
        border-radius: 3px;
    }
    
    .section-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary);
        margin: 60px 0 30px;
        position: relative;
        padding-left: 20px;
    }
    
    .section-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        height: 70%;
        width: 5px;
        background: linear-gradient(to bottom, var(--primary), var(--secondary));
        border-radius: 5px;
    }
    
    /* Карусель */
    .carousel-container {
        position: relative;
        margin: 40px 0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(138, 79, 255, 0.1);
    }
    
    .carousel {
        display: flex;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        height: 500px;
    }
    
    .carousel-slide {
        min-width: 100%;
        position: relative;
    }
    
    .carousel-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .slide-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
        color: white;
        padding: 30px;
        text-align: center;
    }
    
    .carousel-nav {
        position: absolute;
        top: 50%;
        width: 100%;
        display: flex;
        justify-content: space-between;
        transform: translateY(-50%);
        z-index: 2;
    }
    
    .carousel-btn {
        width: 50px;
        height: 50px;
        background: rgba(255,255,255,0.3);
        border: none;
        border-radius: 50%;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 20px;
        transition: all 0.3s;
        backdrop-filter: blur(5px);
    }
    
    .carousel-btn:hover {
        background: rgba(255,255,255,0.5);
        transform: scale(1.1);
    }
    
    .carousel-dots {
        position: absolute;
        bottom: 20px;
        left: 0;
        right: 0;
        display: flex;
        justify-content: center;
        gap: 10px;
        z-index: 2;
    }
    
    .carousel-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: rgba(255,255,255,0.5);
        border: none;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .carousel-dot.active {
        background: white;
        transform: scale(1.2);
    }
    
    /* Сервис карточки */
    .service-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(138, 79, 255, 0.05);
        border-left: 4px solid var(--primary);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }
    
    .service-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(138, 79, 255, 0.1);
    }
    
    .service-card:hover::after {
        transform: scaleX(1);
    }
    
    .service-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 15px;
    }
    
    /* Контакты */
    .contact-container {
        background: var(--light-bg);
        border-radius: 16px;
        padding: 20px;
        margin: 50px 0;
        border: 1px solid rgba(138, 79, 255, 0.1);
        position: relative;
    }
    
    .contact-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
    }
    
    .contact-item {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
        padding: 15px;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    
    .contact-item:hover {
        background: rgba(255, 255, 255, 0.7);
        transform: translateX(5px);
    }
    
    .contact-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
        font-size: 1.2rem;
        flex-shrink: 0;
        box-shadow: 0 5px 15px rgba(138, 79, 255, 0.2);
    }
    
    .contact-text strong {
        display: block;
        margin-bottom: 5px;
        color: var(--primary);
    }
    
    /* Кнопки */
    .luxury-btn {
        text-decoration: none !important;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        border: none;
        padding: 15px 35px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 10px 20px rgba(138, 79, 255, 0.2);
        position: relative;
        overflow: hidden;
    }
    
    .luxury-btn::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    
    .luxury-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(138, 79, 255, 0.3);
    }
    
    .luxury-btn:hover::after {
        opacity: 1;
    }
    
    .luxury-btn span {
        position: relative;
        z-index: 1;
    }
    
    /* Социальные сети */
    .social-buttons {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }
    
    .social-btn {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .social-btn.vk {
        background: linear-gradient(135deg, #4A76A8 0%, #5B88BD 100%);
         text-decoration: none;
    }
    
    .social-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }
    
    /* Адаптивность */
    @media (max-width: 992px) {
        .luxury-card {
            padding: 40px;
        }
        
        .page-title {
            font-size: 2.5rem;
        }
        
        .carousel {
            height: 400px;
        }
    }
    
    @media (max-width: 768px) {
        .luxury-card {
            padding: 30px 20px;
            margin: 0px auto;
            border-radius: 16px;
            margin-left: -15px;
            margin-right: -15px;
        }

        
        
        .page-title {
            font-size: 2rem;
        }
        
        .section-title {
            font-size: 1.5rem;
            margin: 40px 0 20px;
        }
        
        .contact-item {
            flex-direction: column;
            text-align: center;
            padding: 20px;
        }
        
        .contact-icon {
            margin-right: 0;
            margin-bottom: 15px;
        }
        
        .carousel {
            height: 300px;
        }
        
        .slide-caption {
            padding: 15px;
        }
        
        .carousel-btn {
            width: 40px;
            height: 40px;
            margin: 0 10px;
        }
    }
</style>

<div class="container">
    <div class="luxury-card">
        <div class="page-header">
            <h1 class="page-title">AVA Studio</h1>
            <div class="page-subtitle">Создаём красоту с 2015 года</div>
        </div>
        
        <div class="row">
            <div class="col-lg-8">
                <h2 class="section-title">Наш салон</h2>
                <p>Салон красоты <strong>AVA Studio</strong> — это место, где встречаются профессионализм и творчество. 
                Наша миссия — помочь клиентам раскрыть свою природную красоту и создать индивидуальный стиль.</p>
                
                <p>За 8 лет работы наш салон превратился в настоящий beauty-клуб. Он стал местом, где изысканный интерьер дарит ощущение уюта и роскоши, а заботливые администраторы всегда предложат ароматный чай или кофе.</p>
                
                <div class="carousel-container">
                    <div class="carousel" id="mainCarousel">
                        <div class="carousel-slide">
                            <img src="/images/вход.png" alt="Вход в салон">
                        </div>
                        <div class="carousel-slide">
                            <img src="/images/зал.png" alt="Зал ожидания">
                        </div>
                        <div class="carousel-slide">
                            <img src="/images/ещё.png" alt="Интерьер салона">
                        </div>
                        <div class="carousel-slide">
                            <img src="/images/кусты.png" alt="Зона отдыха">
                        </div>
                    </div>
                    
                    <div class="carousel-nav">
                        <button class="carousel-btn" id="prevBtn">&#10094;</button>
                        <button class="carousel-btn" id="nextBtn">&#10095;</button>
                    </div>
                    
                    <div class="carousel-dots" id="carouselDots"></div>
                </div>
                
                <h2 class="section-title">Наши услуги</h2>
                
                <div class="service-card">
                    <h3 class="service-title">Ногтевой сервис</h3>
                    <p>Мы предлагаем более 50 видов покрытий и 120+ вариантов дизайна. 
                    Особое внимание уделяем здоровью ногтей — используем только профессиональные бренды 
                    (OPI, Essie, CND) и стерильные инструменты.</p>
                </div>
                
                <?= Html::a('<span>Посмотреть все услуги и цены</span>', ['/service/index'], ['class' => 'luxury-btn']) ?>
            </div>
            
            <div class="col-lg-4">
                <div class="contact-container">
                    <h2 class="section-title" style="margin-top: 0;">Контакты</h2>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-text">
                            <strong>Адрес:</strong>
                            Санкт-Петербург, ул. Кораблестроителей, 32к3
                            <small style="display: block; margin-top: 5px; color: #777;">1 этаж, вход со двора</small>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-text">
                            <strong>Телефон:</strong>
                            +7 (911) 293-13-14
                            <small style="display: block; margin-top: 5px; color: #777;">Звонки с 9:00 до 21:00</small>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-text">
                            <strong>Часы работы:</strong>
                            Ежедневно с 9:00 до 21:00
                            <small style="display: block; margin-top: 5px; color: #777;">По предварительной записи</small>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-subway"></i>
                        </div>
                        <div class="contact-text">
                            <strong>Метро:</strong>
                            Приморская (5 мин пешком)
                            <small style="display: block; margin-top: 5px; color: #777;">Есть парковка для клиентов</small>
                        </div>
                    </div>
                    
                    <h3 class="section-title" style="margin-bottom: 20px;">Мы в соцсетях</h3>
                    <div class="social-buttons">
                        <a href="https://vk.com/naillounge" class="social-btn vk">
                            <i class="fab fa-vk"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <h2 class="section-title">Наша команда</h2>
        <p>В AVA Studio работают только дипломированные специалисты с опытом от 5 лет. 
        Каждый мастер ежегодно проходит обучение и имеет медицинскую книжку. 
        Мы тщательно следим за стерильностью инструментов и используем только профессиональные материалы.</p>
    </div>
</div>

<!-- Подключаем Font Awesome для иконок -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const carousel = document.getElementById('mainCarousel');
        const slides = document.querySelectorAll('.carousel-slide');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const dotsContainer = document.getElementById('carouselDots');
        
        let currentIndex = 0;
        const slideCount = slides.length;
        
        // Создаем точки навигации
        slides.forEach((_, index) => {
            const dot = document.createElement('button');
            dot.classList.add('carousel-dot');
            if (index === 0) dot.classList.add('active');
            dot.addEventListener('click', () => {
                goToSlide(index);
            });
            dotsContainer.appendChild(dot);
        });
        
        const dots = document.querySelectorAll('.carousel-dot');
        
        // Функция перехода к конкретному слайду
        function goToSlide(index) {
            currentIndex = index;
            updateCarousel();
        }
        
        // Обновление карусели
        function updateCarousel() {
            carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
            
            // Обновляем активную точку
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentIndex);
            });
        }
        
        // Кнопка "назад"
        prevBtn.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + slideCount) % slideCount;
            updateCarousel();
        });
        
        // Кнопка "вперед"
        nextBtn.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % slideCount;
            updateCarousel();
        });
        
        // Автопрокрутка
        let interval = setInterval(() => {
            currentIndex = (currentIndex + 1) % slideCount;
            updateCarousel();
        }, 5000);
        
        // Остановка автопрокрутки при наведении
        carousel.addEventListener('mouseenter', () => {
            clearInterval(interval);
        });
        
        // Возобновление автопрокрутки
        carousel.addEventListener('mouseleave', () => {
            interval = setInterval(() => {
                currentIndex = (currentIndex + 1) % slideCount;
                updateCarousel();
            }, 5000);
        });
        
        // Свайп для мобильных устройств
        let touchStartX = 0;
        let touchEndX = 0;
        
        carousel.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, {passive: true});
        
        carousel.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, {passive: true});
        
        function handleSwipe() {
            if (touchEndX < touchStartX - 50) {
                // Свайп влево
                currentIndex = (currentIndex + 1) % slideCount;
                updateCarousel();
            }
            
            if (touchEndX > touchStartX + 50) {
                // Свайп вправо
                currentIndex = (currentIndex - 1 + slideCount) % slideCount;
                updateCarousel();
            }
        }
    });
</script>