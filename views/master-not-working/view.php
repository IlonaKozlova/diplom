<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\MasterNotWorking $model */

$this->title = $model->id;
// $this->params['breadcrumbs'][] = ['label' => 'Master Not Workings', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="master-not-working-view">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

<br>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            'master_id',
            'date',
            'time_slot_id',//:datetime
            'comment',
        ],
    ]) ?>
    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
</div>
