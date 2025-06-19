<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "master_working_days".
 *
 * @property int $id
 * @property int $master_id
 * @property int|null $is_working
 * @property string|null $monday
 * @property string|null $tuesday
 * @property string|null $wednesday
 * @property string|null $thursday
 * @property string|null $friday
 * @property string|null $saturday
 * @property string|null $sunday
 * @property string $comment
 *
 * @property User $master
 */
class MasterWorkingDays extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_working_days';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['master_id'], 'required'],
            [['master_id', 'is_working'], 'integer'],
            [['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'], 'string', 'max' => 11],
            [['comment'], 'string', 'max' => 255],
            [['master_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['master_id' => 'id']],
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
            'is_working' => 'Работает/Не работает',
            'monday' => 'Понедельник',
            'tuesday' => 'Вторник',
            'wednesday' => 'Среда',
            'thursday' => 'Четверг',
            'friday' => 'Пятница',
            'saturday' => 'Суббота',
            'sunday' => 'Воскресенье',
            'comment' => 'Комментарий',
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
