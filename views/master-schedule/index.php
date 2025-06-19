<?php

use app\models\MasterSchedule;
use app\models\Service;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'График работы';
$this->registerCss('
    .time-slot {
        display: inline-block;
        margin: 2px;
        padding: 5px 8px;
        border-radius: 4px;
        font-size: 0.85rem;
    }
    .available-slot {
        background-color: #d4edda;
        color: #155724;
        cursor: pointer;
    }
    .booked-slot {
        background-color: #f8d7da;
        color: #721c24;
    }
    .break-slot {
        background-color: #fff3cd;
        color:rgb(209, 135, 23);
    }
');
?>
<div class="master-schedule-index">

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h1 class="h5 mb-0"><?= Html::encode($this->title) ?></h1>

            <!-- <?= Html::a('<i class="fas fa-plus"></i> Добавить график', ['create'], ['class' => 'btn btn-success btn-sm']) ?> -->
             
        </div>
        
        <div class="card-body">
        <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table table-hover table-bordered mb-0'],
    'options' => ['class' => 'table-responsive'],
    'layout' => "{items}\n<div class='d-flex justify-content-between align-items-center mt-3'>{summary}\n{pager}</div>",
    'pager' => [
        'options' => ['class' => 'pagination pagination-sm justify-content-end'],
        'linkContainerOptions' => ['class' => 'page-item'],
        'linkOptions' => ['class' => 'page-link'],
        'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link disabled'],
    ],
    'columns' => [
        ['attribute' => 'Дата', 'value' => fn($data) => $data->zapis->appointment_date ?? 'Нет'],

        // ['attribute' => 'day_of_week',],



        [
            'attribute' => 'is_working',
            'value' => function($model) {
                return $model->is_working 
                    ? Html::tag('span', 'Рабочий', ['class' => 'badge bg-success']) 
                    : Html::tag('span', 'Выходной', ['class' => 'badge bg-secondary']);
            },
            'format' => 'raw',
            'contentOptions' => ['class' => 'text-center'],
        ],

        [
            'label' => 'Рабочее время',
            'value' => function($model) {
                if (!$model->is_working) {
                    return '-';
                }
                
                $workTime = date('H:i', strtotime($model->start_time)) . ' - ' . 
                             date('H:i', strtotime($model->end_time));
                
                
                return $workTime;
            },
            'contentOptions' => ['class' => 'text-center'],
        ],

        [
            'label' => 'Перерыв',
            'value' => function($model) {
                if (!$model->is_working) {
                    return '-';
                }
                
                if ($model->break_start_time && $model->break_end_time) {
                    return date('H:i', strtotime($model->break_start_time)) . ' - ' . 
                           date('H:i', strtotime($model->break_end_time));
                }
                
                return 'Без перерыва';
            },
            'contentOptions' => ['class' => 'text-center'],
        ],

        [
            'label' => 'Доступное время',
            'format' => 'raw',
            'value' => function($model) {
                if (!$model->is_working) return '-';
                
                $start = strtotime($model->start_time);
                $end = strtotime($model->end_time);
                $breakStart = $model->break_start_time ? strtotime($model->break_start_time) : null;
                $breakEnd = $model->break_end_time ? strtotime($model->break_end_time) : null;
                
                $slots = '';
                $slotDuration = 30 * 60; // 30 минут в секундах
                
                // Проверяем занятые слоты (это можно получить из модели записи)
                $bookedSlots = []; // Здесь должны быть реальные занятые слоты
                
                for ($current = $start; $current < $end; $current += $slotDuration) {
                    $slotEnd = $current + $slotDuration;
                    
                    // Пропускаем перерыв
                    if ($breakStart && $current >= $breakStart && $current < $breakEnd) {
                        $slots .= Html::tag('span', 
                            date('H:i', $current).'-'.date('H:i', $breakEnd), 
                            ['class' => 'time-slot break-slot', 'title' => 'Перерыв']
                        );
                        $current = $breakEnd - $slotDuration;
                        continue;
                    }
                    
                    if ($slotEnd > $end) break;
                    
                    $slotKey = date('H:i', $current).'-'.date('H:i', $slotEnd);
                    $isBooked = in_array($slotKey, $bookedSlots);
                    
                    $slots .= Html::tag('span', $slotKey, [
                        'class' => $isBooked ? 'time-slot booked-slot' : 'time-slot available-slot',
                        'title' => $isBooked ? 'Занято' : 'Доступно для записи',
                        'data-time' => $slotKey,
                        'data-day' => $model->day_of_week
                    ]);
                }
                
                return $slots ?: 'Нет доступного времени';
            },
            'contentOptions' => ['style' => 'min-width: 300px;']
        ],


        [
            'class' => ActionColumn::className(),
            'header' => 'Действия',
            'contentOptions' => ['class' => 'text-center'],
            'template' => '{update} {delete}',
            
        ],
        
    ],
]); ?>



</div>

