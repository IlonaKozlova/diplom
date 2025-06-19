<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zapis_slots".
 *
 * @property int $id
 * @property int $zapis_id
 * @property int $time_slots_id
 * @property string $comment
 *
 * @property MasterNotWorking $ZapisSlots
 * @property Zapis $zapis
 */
class ZapisSlots extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'zapis_slots';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['zapis_id', 'time_slots_id'], 'required'],
            [['zapis_id', 'time_slots_id'], 'integer'],
            [['comment'], 'string', 'max' => 255],
            [['zapis_id'], 'exist', 'skipOnError' => true, 'targetClass' => Zapis::class, 'targetAttribute' => ['zapis_id' => 'id']],
            [['time_slots_id'], 'exist', 'skipOnError' => true, 'targetClass' => MasterNotWorking::class, 'targetAttribute' => ['time_slots_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'zapis_id' => 'Zapis ID',
            'time_slots_id' => 'Времянной слот',
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
        return $this->hasOne(MasterNotWorking::class, ['id' => 'time_slots_id']);
    }

    /**
     * Gets query for [[Zapis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getZapis()
    {
        return $this->hasOne(Zapis::class, ['id' => 'zapis_id']);
    }

    // public function getTimeSlots()
    // {
    //     return $this->hasOne(TimeSlots::className(), ['id' => 'time_slots_id']);
    // }

    public function getTimeSlots()
{
    return $this->hasOne(TimeSlots::className(), ['id' => 'time_slots_id']);
}

    public function getTimeSlot()
{
    return $this->hasOne(TimeSlots::className(), ['id' => 'time_slots_id']);
}
}
