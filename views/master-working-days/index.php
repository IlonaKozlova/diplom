<?php
use app\models\MasterWorkingDays;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\MasterWorkingDaysSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'График работы мастеров';

$russianMonths = [
    1 => 'Январь',
    2 => 'Февраль',
    3 => 'Март',
    4 => 'Апрель',
    5 => 'Май',
    6 => 'Июнь',
    7 => 'Июль',
    8 => 'Август',
    9 => 'Сентябрь',
    10 => 'Октябрь',
    11 => 'Ноябрь',
    12 => 'Декабрь'
];

// Получаем текущий месяц и год из GET-параметров или используем текущую дату
$currentMonth = Yii::$app->request->get('month', date('n'));
$currentYear = Yii::$app->request->get('year', date('Y'));
$timestamp = mktime(0, 0, 0, $currentMonth, 1, $currentYear);
$monthName = $russianMonths[$currentMonth] . ' ' . $currentYear;
$daysInMonth = date('t', $timestamp);
$firstDayOfWeek = date('N', $timestamp);

// Получаем всех мастеров с их графиками
$masters = $dataProvider->getModels();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Html::encode($this->title) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <div class="working-schedule-index">
        <div class="custom-header">
            <div class="header-content">
                 
                <h1 class="header-title"><?= Html::encode($this->title) ?></h1>
                
                <div class="header-subline">
                    <div class="subline-dots"></div>
                </div>
                
            </div>
        </div>

        <div class="calendar-container">
            <header class="calendar-header">
                <div class="month-selector">
                    <?= Html::a('&lt;', ['index', 
                        'month' => $currentMonth == 1 ? 12 : $currentMonth - 1,
                        'year' => $currentMonth == 1 ? $currentYear - 1 : $currentYear
                    ], ['class' => 'month-nav']) ?>
                    <h2 class="current-month"><?= $monthName ?></h2>
                    <?= Html::a('&gt;', ['index', 
                        'month' => $currentMonth == 12 ? 1 : $currentMonth + 1,
                        'year' => $currentMonth == 12 ? $currentYear + 1 : $currentYear
                    ], ['class' => 'month-nav']) ?>

                    <a href="<?= Url::to(['/master-working-days/create']) ?>" class="add-button">
                    <i class="bi bi-plus"></i>
                </a>
                </div>
            </header>

            <div class="calendar-wrapper">
                <div class="calendar">
                    <div class="weekdays">
                        <?php
                        $russianWeekdays = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
                        foreach ($russianWeekdays as $day) {
                            echo '<div class="weekday'.(($day === 'Сб' || $day === 'Вс') ? ' weekend' : '').'">'.$day.'</div>';
                        }
                        ?>
                    </div>

                    <div class="days">
                        <?php
                        // Пустые дни в начале месяца
                        for ($i = 1; $i < $firstDayOfWeek; $i++) {
                            echo '<div class="day empty-day"></div>';
                        }
                        
                        // Дни месяца
                        for ($day = 1; $day <= $daysInMonth; $day++):
                            $currentDate = date('Y-m-') . str_pad($day, 2, '0', STR_PAD_LEFT);
                            $dayOfWeek = date('N', strtotime($currentDate));
                            $isWeekend = ($dayOfWeek >= 6);
                            $isToday = ($currentDate == date('Y-m-d'));
                        ?>
                        <div class="day <?= $isWeekend ? 'weekend' : '' ?> <?= $isToday ? 'today' : '' ?>" data-date="<?= $currentDate ?>">
                            <span class="date"><?= $day ?></span>
                            <div class="schedule">
                                <?php foreach ($masters as $master): 
                                    $dayColumn = strtolower(date('l', strtotime($currentDate)));
                                    if ($master->$dayColumn == 1): ?>
                                        <div class="master master-<?= $master->id % 4 + 1 ?>" 
                                             title="<?= $master->master->last_name.' '.$master->master->first_name.' '.$master->master->middle_name ?>">
                                            <?= $master->master->first_name.' '.mb_substr($master->master->last_name, 0, 1).'.' ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>

            <div class="legend">
                <h3>Мастера</h3>
                <div class="legend-items">
                    <?php foreach ($masters as $master): ?>
                    <div class="legend-item">
                        <div class="legend-name">
                            <?=   $master->master->first_name . ' ' . $master->master->middle_name . ' ' . $master->master->last_name  ?>
                        </div>
                        <div class="legend-actions">
                            <a href="<?= Url::to(['update', 'id' => $master->id]) ?>" class="btn-edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="<?= Url::to(['delete', 'id' => $master->id]) ?>" class="btn-delete" 
                               data-method="post" data-confirm="Вы уверены, что хотите удалить этого мастера?">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <style>
.add-button {
    background: #9c80f7;
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    text-decoration: none;
    transition: all 0.3s;
    box-shadow: 0 2px 8px rgb(212, 118, 255);
    margin-left: auto; /* Дополнительное выравнивание вправо */
}
    
    .custom-header {
  text-align: center;
  margin: 0 auto 10px;
  padding: 30px 20px 20px;
  position: relative;
    }

    .header-content {
      position: relative;
      z-index: 2;
    }

    .header-title {
      font-size: 2.2rem;
      color: #E94C89;
      margin: 0 0 15px;
      font-weight: 700;
      text-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .header-subline {
      width: 120px;
      height: 6px;
      margin: 0 auto;
      position: relative;
    }

    .subline-dots {
      position: absolute;
      width: 100%;
      height: 100%;
      background: radial-gradient(circle, #9c80f7 30%, transparent 30%);
      background-size: 12px 100%;
      background-position: 0 0;
      animation: dotsFlow 3s linear infinite;
    }

    @keyframes dotsFlow {
      0% { background-position-x: 0; }
      100% { background-position-x: 24px; }
    }

    /* Calendar container */
    .calendar-container {
      max-width: 1200px;
      margin: 20px auto;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 8px 25px rgba(156, 128, 247, 0.1);
      overflow: hidden;
      border: 1px solid rgba(106, 48, 147, 0.1);
    }

    .calendar-wrapper {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
    }

    .calendar {
      min-width: 600px;
      padding: 20px;
    }

    .calendar-header {
      padding: 15px 20px;
      background: linear-gradient(135deg, rgba(245,247,250,0.6) 0%, rgba(228,232,240,0.8) 100%);
      border-bottom: 1px solid rgba(106, 48, 147, 0.1);
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .month-selector {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .current-month {
      font-size: 1.4rem;
      color: #6a3093;
      margin: 0;
      font-weight: 600;
    }

    .month-nav {
      background:rgb(255, 118, 170);
      color: white;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      font-size: 1.25rem;
      cursor: pointer;
      border: none;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s;
      box-shadow: 0 2px 8px rgba(106, 48, 147, 0.3);
      text-decoration: none;
    }

    .month-nav:hover {
      background: #5a287f;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(106, 48, 147, 0.4);
    }

    .weekdays {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 4px;
      margin-bottom: 4px;
    }

    .weekday {
      text-align: center;
      font-weight: 600;
      color: #6a3093;
      padding: 8px 4px;
      font-size: 0.9rem;
    }

    .weekday.weekend {
      color: rgb(255, 118, 170);
    }

    .days {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 4px;
    }

    .day {
      min-height: 80px;
      background: #fff;
      border-radius: 6px;
      padding: 6px;
      display: flex;
      flex-direction: column;
      border: 1px solid rgba(106, 48, 147, 0.1);
      transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .day:hover {
      transform: translateY(-3px);
      box-shadow: 0 4px 12px rgba(156, 128, 247, 0.1);
    }

    .day.weekend {
      background: rgba(156, 128, 247, 0.05);
    }

    .day.today {
      border: 2px solid #6a3093;
    }

    .date {
      font-weight: 500;
      font-size: 0.9rem;
      margin-bottom: 2px;
      color: #6a3093;
      text-align: center;
    }

    .today .date {
      background-color: #6a3093;
      color: white;
      border-radius: 50%;
      width: 24px;
      height: 24px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto;
    }

    .schedule {
      flex-grow: 1;
      font-size: 0.7rem;
      overflow: hidden;
    }

    .master {
      padding: 2px 4px;
      border-radius: 3px;
      margin-bottom: 2px;
      font-weight: 500;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    /* Цвета мастеров */
    .master-1 { background: rgba(156, 128, 247, 0.15); color: #6a3093; }
    .master-2 { background: rgba(233, 30, 99, 0.15); color: #e91e63; }
    .master-3 { background: rgba(33, 150, 243, 0.15); color: #2196f3; }
    .master-4 { background: rgba(76, 175, 80, 0.15); color: #4caf50; }

    .legend {
      padding: 15px 20px;
      border-top: 1px dashed #e0e0e0;
      background: linear-gradient(135deg, rgba(245,247,250,0.6) 0%, rgba(228,232,240,0.8) 100%);
    }

    .legend h3 {
      font-size: 1.2rem;
      color: #6a3093;
      margin: 0 0 12px;
      font-weight: 600;
    }

    .legend-items {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .legend-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 8px 12px;
      background: #fff;
      border-radius: 8px;
      border: 1px solid rgba(106, 48, 147, 0.1);
    }

    .legend-name {
      font-weight: 500;
      color: #333;
      font-size: 1rem;
    }

    .legend-actions {
      display: flex;
      gap: 6px;
    }

    .empty-day {
      background: rgba(245, 247, 250, 0.5);
      opacity: 0.5;
    }

    .btn-edit, .btn-delete {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      font-size: 1rem;
      transition: all 0.3s;
      text-decoration: none;
    }

    .btn-edit {
      background: rgb(106, 188, 255);
      color: white;
      box-shadow: 0 2px 5px rgba(33, 150, 243, 0.3);
    }

    .btn-edit:hover {
      background: #1976d2;
      transform: scale(1.1);
    }

    .btn-delete {
      background: #f44336;
      color: white;
      box-shadow: 0 2px 5px rgba(244, 67, 54, 0.3);
    }

    .btn-delete:hover {
      background: #d32f2f;
      transform: scale(1.1);
    }

    /* Mobile styles */
    @media (max-width: 768px) {
      .header-title {
        font-size: 1.8rem;
      }
      
      .calendar {
        padding: 10px;
        min-width: 500px;
      }
      
      .day {
        min-height: 60px;
        padding: 4px;
      }
      
      .date {
        font-size: 0.7rem;
      }
      
      .master {
        font-size: 0.6rem;
        padding: 1px 2px;
      }
      
      .current-month {
        font-size: 1.1rem;
      }
      
      .month-nav {
        width: 30px;
        height: 30px;
        font-size: 1rem;
      }
      
      .legend {
        padding: 12px 15px;
      }

          .legend-item {
      padding: 20px 12px;
    }
      
      .legend h3 {
        font-size: 1.1rem;
      }
      
      .legend-name {
        font-weight: 500;
        font-size: 1rem;
      }
      
      .btn-edit, .btn-delete {
        width: 40px;
        height: 40px;
        font-size: 1rem;
      }
    }

    @media (max-width: 480px) {
      .calendar {
        min-width: 400px;
      }
      
      .day {
        min-height: 50px;
      }
      
      .date {
        font-size: 0.6rem;
      }
      
      .master {
        display: none;
      }
      
      .day.today .master, 
      .day.active .master {
        display: block;
      }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Показывать полное имя при клике на день
        document.querySelectorAll('.day').forEach(day => {
            day.addEventListener('click', function() {
                this.classList.toggle('active');
            });
        });
        
        // Фиксировать заголовок при прокрутке
        const header = document.querySelector('.calendar-header');
        if (header) {
            const observer = new IntersectionObserver(
                ([e]) => e.target.classList.toggle('sticky', e.intersectionRatio < 1),
                {threshold: [1]}
            );
            observer.observe(header);
        }
    });
    </script>
</body>
</html>