<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zapis".
 *
 * @property int $id
 * @property int $client_id
 * @property int $master_id
 * @property string|null $status
 * @property string|null $cancel_reason
 * @property string $date
 * @property string|null $created_at
 *
 * @property User $client
 * @property User $master
 * @property ZapisService[] $zapisServices
 */
class Zapis extends \yii\db\ActiveRecord
{
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'zapis';
    }

    public $services;
    public $time_slots_id;
    public $master_working_days;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // 'client_id', 'master_id', 
            [['time_slots_id', 'master_id', 'date'], 'required'],
            [['client_id', 'master_id'], 'integer'],
            [['status', 'cancel_reason', 'comment'], 'string', 'max' => 255],
            [['date', 'created_at'], 'safe'],
            // [['total_price'], 'number'],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['client_id' => 'id']],
            [['master_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['master_id' => 'id']],
            // [['services'], 'required', 'message' => 'Выберите хотя бы одну услугу'],
            // [['services'], 'each', 'rule' => ['integer']],
            [['time_slots_id'], 'exist', 'skipOnError' => true, 'targetClass' => TimeSlots::class, 'targetAttribute' => 'id'],
            [['master_working_days'], 'safe'],
[['services'], 'required', 'message' => 'Пожалуйста, выберите услугу'],
[['services', 'time_slots_id'], 'required', 'on' => 'create'],
[['services'], 'each', 'rule' => ['integer'], 'on' => 'update'],


            // ['cancel_reason', 'required', 'when' => function($model) {
            //     return $model->status === 'Отменён';}, 
            // 'message' => 'Укажите причину отмены'],
            // ['cancel_reason', 'string', 'max' => 255],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'Клиент',
            'master_id' => 'Мастер',
            'status' => 'Статус',
            'cancel_reason' => 'Причина отмены',
            'date' => 'Дата',
            // 'total_price' => 'Итого',
            'created_at' => 'Дата создания записи',
            // 'start_time' => 'Время',
            'comment' => 'Комментарий',
            'time_slots_id' => 'Время',
            'services' => 'Услуга',

        ];
    }

    /**
     * Gets query for [[Client]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(User::class, ['id' => 'client_id']);
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
     * Gets query for [[ZapisServices]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getZapisServices()
    {
        return $this->hasMany(ZapisService::class, ['zapis_id' => 'id']);
    }

    public function getServices()
    {
        return $this->hasMany(Service::class, ['id' => 'service_id'])->viaTable('zapis_service', ['zapis_id' => 'id']);
    }

    public function getZapisSlots()
    {
        return $this->hasMany(ZapisSlots::className(), ['zapis_id' => 'id']);
    }


    public function getTimeSlot()
    {
        return $this->hasOne(TimeSlots::class, ['id' => 'time_slots_id'])->viaTable('zapis_slots', ['zapis_id' => 'id']);
    }
    

    
    
}
