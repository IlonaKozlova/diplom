<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterNotWorking $model */

$this->title = 'Корректировка перерыва' //. $model->id;
// $this->params['breadcrumbs'][] = ['label' => 'Master Not Workings', 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
// $this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-not-working-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'master' => $master,
        'timeslots' => $timeslots,
    ]) ?>

    

</div>
