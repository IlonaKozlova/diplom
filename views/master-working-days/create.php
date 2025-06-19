<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterWorkingDays $model */

$this->title = 'Новый график';
// $this->params['breadcrumbs'][] = ['label' => 'Master Working Days', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-working-days-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
			'mast' => $mast,
    ]) ?>

</div>
