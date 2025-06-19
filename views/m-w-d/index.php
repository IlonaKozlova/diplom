<?php

use app\models\MasterWorkingDays;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\MWDSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Master Working Days';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-working-days-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Master Working Days', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'master_id',
            'is_working',
            'monday',
            'tuesday',
            //'wednesday',
            //'thursday',
            //'friday',
            //'saturday',
            //'sunday',
            //'comment',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, MasterWorkingDays $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
