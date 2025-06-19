<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\DopPhoto $model */

$this->title = 'Новое фото для услуги';
// $this->params['breadcrumbs'][] = ['label' => 'Dop Photos', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="dop-photo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'service' => $service,
    ]) ?>

</div>
