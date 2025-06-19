<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Zapis $model */

$this->title = $model->id;
// $this->params['breadcrumbs'][] = ['label' => 'Zapis', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="zapis-view">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
<br>
    <!-- <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p> -->

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'client_id',
            // 'master_id',
            // 'status',
            'cancel_reason',
            // 'date',
            // 'created_at',
            'comment',
        ],
    ]) ?>

</div>
