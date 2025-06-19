<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "master_schedule".
 *
 * @property int $id
 * @property int $master_id
 * @property string $day_of_week
 * @property int|null $is_working
 * @property string|null $start_time
 * @property string|null $end_time
 *
 * @property User $master
 */
class MasterSchedule extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_schedule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['master_id', 'day_of_week'], 'required'],
            [['master_id', 'is_working'], 'integer'],
            [['day_of_week'], 'string'],
            [['start_time', 'end_time'], 'safe'],
            [['master_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['master_id' => 'id']],
            [['break_start_time', 'break_end_time'], 'datetime', 'format' => 'php:H:i'], 

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'master_id' => 'Мастер',
            'day_of_week' => 'День недели',
            'is_working' => 'Рабочий/Выходной',
            'start_time' => 'Начало работы',
            'end_time' => 'Окончание работы',
            'number' => 'Дата',
        ];
    }

    /**
     * Gets query for [[Master]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMaster()
    {
        return $this->hasOne(User::class, ['id' => 'master_id']);
    }
}
