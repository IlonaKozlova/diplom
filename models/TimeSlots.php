<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "time_slots".
 *
 * @property int $id
 * @property string $start_time
 * @property string $end_time
 * @property string $comment
 *
 * @property ZapisSlots[] $ZapisSlots
 */
class TimeSlots extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'time_slots';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['start_time', 'end_time', 'comment'], 'required'],
            [['start_time', 'end_time'], 'safe'],
            [['comment'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'start_time' => 'Начало процедуры',
            'end_time' => 'Окончание процедуры',
            'comment' => 'Комментарий',
        ];
    }

    /**
     * Gets query for [[ZapisSlots]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getZapisSlots()
    {
        return $this->hasMany(ZapisSlots::class, ['time_slot_id' => 'id']);
    }
}
