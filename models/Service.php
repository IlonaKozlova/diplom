<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "service".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property float $price
 * @property string|null $photo
 * @property int|null $is_active
 *
 * @property AppointmentService[] $appointmentServices
 */
class Service extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'price', 'duration_slots', 'photo'], 'required'],
            [['description'], 'string'],
            [['price', 'duration_slots'], 'number'],
            [['is_active'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['photo'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'description' => 'Описание',
            'price' => 'Цена',
            'duration_slots' => 'Длительность услуги (минуты)',
            'photo' => 'Фото',
            'is_active' => 'Активна ли услуга',
            'comment' => 'Комментарий (видит админ)',
        ];
    }

    /**
     * Gets query for [[AppointmentServices]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAppointmentServices()
    {
        return $this->hasMany(AppointmentService::class, ['service_id' => 'id']);
    }
}
