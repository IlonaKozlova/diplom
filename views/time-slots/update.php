<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TimeSlots $model */

$this->title = 'Update Time Slots: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Time Slots', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="time-slots-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
