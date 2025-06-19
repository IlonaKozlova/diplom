<?php

use app\models\MasterNotWorking;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\MasterNotWorkingSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Отгулы';
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-not-working-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Дать отгул', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'master_id',
            'date',
            'time_slot_id', //:datetime
            'comment',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, MasterNotWorking $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
