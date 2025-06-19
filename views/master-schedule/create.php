<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterSchedule $model */

$this->title = 'Create Master Schedule';
$this->params['breadcrumbs'][] = ['label' => 'Master Schedules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-schedule-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
