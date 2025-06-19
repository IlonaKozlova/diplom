<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterNotWorking $model */

$this->title = 'Новый перерыв';
// $this->params['breadcrumbs'][] = ['label' => 'Master Not Workings', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-not-working-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'master' => $master,
        'timeslots' => $timeslots,
    ]) ?>

</div>
