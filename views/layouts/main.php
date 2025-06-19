<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Geologica:wght@400;500;700&display=swap" rel="stylesheet">    
<link rel="icon" href="/gem.svg?v=2" type="image/svg+xml">
<style>
        body {
            font-family: "Geologica", sans-serif !important;
            background: linear-gradient(45deg, #FFD6E5 0%, #FFFFFF 100%);
            background-attachment: fixed;
            background-size: cover;
            min-height: 100vh;
            }
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #FFD6E5 0%, #FFFFFF 100%);
            z-index: -1;
        }
        .alert {
            margin-top: 10px;
        }
            /* .navbar-toggler {
        display: none !important;
    } */
    </style>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    
</head>
<body class="d-flex flex-column h-100"> 
    <!-- style="background: linear-gradient(45deg, #FFD6E5 0%, #FFFFFF 100%); " -->
<?php $this->beginBody() ?>

<header id="header" class="position-relative" >
    <!-- class="position-relative" realaty -->
<?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'): ?>
<div class="position-fixed" style="z-index: 1040; left: 10px; ">
    <button class="btn btn-lg date-nav-button rounded-circle" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" style="font-size: 2rem;">
        <i class="bi bi-list"></i>
    </button>
</div>
<? endif; ?>

    <?php
        // гость
    if (Yii::$app->user->isGuest) {
        $items=[
            ['label' => 'О нас', 'url' => ['/site/contact']],
            ['label' => 'Услуги', 'url' => ['/service/index']],
            ['label' => 'Мастера', 'url' => ['/user/masters']],
            ['label' => 'Регистрация', 'url' => ['/user/create']],
            ['label' => 'Вход', 'url' => ['/site/login']]

        ];} else{
            $role = !Yii::$app->user->isGuest ? Yii::$app->user->identity->role : null;
        // админ
        if ($role == 'admin'){
            ( $items=[
                ['label' => 'Панель администратора', 'url' => ['/zapis-slots']],
            ]);}

            // мастер
            if ($role == 'master') {
            ($items=[
                ['label' => 'Расписание', 'url' => ['/zapis-slots/view', 'id' => Yii::$app->user->id]],
                ['label' => 'Профиль', 'url' => ['/user/profil']],
            ]);}

            // клиент
            if ($role == 'client'){
            ($items=[
                ['label' => 'Услуги', 'url' => ['/service/index']],
                ['label' => 'Мастера', 'url' => ['/user/masters']],
                ['label' => 'Новая запись', 'url' => ['/zapis/create']],
                ['label' => 'Мои записи', 'url' => ['/zapis/index']],
                ['label' => 'Профиль', 'url' => ['/user/profil']],
                ['label' => 'О нас', 'url' => ['/site/contact']],

        ]);}

            array_push($items, '<li class="nav-item">'
            . Html::beginForm(['/site/logout'])
            . Html::submitButton(
            'Выйти (' . Yii::$app->user->identity->login . ')',
            ['class' => 'nav-link logout', 'style' => 'color: #E94C89;']
            )
            . Html::endForm()
            . '</li>');
            }



 NavBar::begin([
    'brandLabel' => Html::tag('span', 
        '<svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="#9c80f7" viewBox="0 0 16 16">
          <path d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6l3-4zm11.386 3.785-1.806-2.41-.776 2.413 2.582-.003zm-3.633.004.961-2.989H4.186l.963 2.995 5.704-.006zM5.47 5.495 8 13.366l2.532-7.876-5.062.005zm-1.371-.999-.78-2.422-1.818 2.425 2.598-.003zM1.499 5.5l5.113 6.817-2.192-6.82L1.5 5.5zm7.889 6.817 5.123-6.83-2.928.002-2.195 6.828z"/>
        </svg> 
        AVA Studio', 
        ['class' => 'fw-bold', 'style' => 'color:#E94C89']
    ),
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-expand-md navbar-light fixed-top', 
        'style' => 'background: #FFFFFF; padding-left: 60px;' /*  отступ */
    ]
]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' =>$items
    ]);
    NavBar::end();
    ?>
</header>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Меню</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <div class="list-group list-group-flush">
                <a href="/zapis-slots" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="bi bi-calendar-week me-3"></i>
                    Расписание мастеров
                </a>
                <a href="/master-working-days" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="bi bi-clock me-3"></i>
                    График работы
                </a>
                <a href="/service" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="bi bi-scissors me-3"></i>
                    Услуги
                </a>
                <a href="/dop-photo" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="bi bi-images me-3"></i>
                    Управление фото
                </a>
                <a href="/user" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="bi bi-people me-3"></i>
                    Пользователи
                </a>


            </div>
        </div>
    </div>

   

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 " style="background: #333333;
            color: #FFFFFF;
            text-align: center;
            padding: 2rem;">
    <div class="container">
        <div class="row text-muted">
            <div class="text-center text-light"><marquee> &copy; AVA Studio <?= date('Y') ?> </marquee></div>
            <!-- <div class="col-md-6 text-center text-md-end"><?= Yii::powered() ?></div> -->
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>


