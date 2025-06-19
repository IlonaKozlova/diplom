<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ZapisSlots $model */

$this->title = 'Create Zapis Slots';
$this->params['breadcrumbs'][] = ['label' => 'Zapis Slots', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zapis-slots-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
