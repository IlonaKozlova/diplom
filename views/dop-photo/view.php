<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\DopPhoto $model */

$this->title = $model->id;
// $this->params['breadcrumbs'][] = ['label' => 'Dop Photos', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="dop-photo-view">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

<br>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            'service_id',
            'photo',
        ],
    ]) ?>
    <p>
        <?= Html::a('Поменять', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
</div>
