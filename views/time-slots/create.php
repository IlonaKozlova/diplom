<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TimeSlots $model */

$this->title = 'Create Time Slots';
$this->params['breadcrumbs'][] = ['label' => 'Time Slots', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="time-slots-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
