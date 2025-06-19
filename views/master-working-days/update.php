<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterWorkingDays $model */

$this->title = 'Редактировать расписание'//. $model->id;
// $this->params['breadcrumbs'][] = ['label' => 'Master Working Days', 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
// $this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-working-days-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
			'mast' => $mast,
    ]) ?>

</div>
