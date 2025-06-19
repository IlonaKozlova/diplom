<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "master_not_working".
 *
 * @property int $id
 * @property int $master_id
 * @property string $date
 * @property int $time_slot_id
 * @property string|null $comment
 *
 * @property User $master
 * @property TimeSlots $timeSlot
 */
class MasterNotWorking extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_not_working';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['master_id', 'date', 'time_slot_id', 'duration'], 'required'],
            [['master_id', 'time_slot_id', 'duration'], 'integer'],
            [['date'], 'safe'],
            [['comment'], 'string', 'max' => 255],
            [['master_id', 'date', 'time_slot_id'], 'unique', 'targetAttribute' => ['master_id', 'date', 'time_slot_id']],
            [['master_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['master_id' => 'id']],
            [['time_slot_id'], 'exist', 'skipOnError' => true, 'targetClass' => TimeSlots::class, 'targetAttribute' => ['time_slot_id' => 'id']],
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
            'date' => 'Дата',
            'time_slot_id' => 'Начало',
            'comment' => 'Комментарий',
            'duration' => 'Длительность перерыва (минут)'
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

    /**
     * Gets query for [[TimeSlot]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTimeSlot()
    {
        return $this->hasOne(TimeSlots::class, ['id' => 'time_slot_id']);
    }
}
