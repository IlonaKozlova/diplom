<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\DopPhoto $model */

$this->title = 'Обновить' //. $model->id;
// $this->params['breadcrumbs'][] = ['label' => 'Dop Photos', 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
// $this->params['breadcrumbs'][] = 'Update';
?>
<div class="dop-photo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'service' => $service,
    ]) ?>

</div>
