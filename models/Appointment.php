<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "appointment".
 *
 * @property int $id
 * @property int $client_id
 * @property int $master_id
 * @property string|null $status
 * @property string|null $cancel_reason
 * @property string $appointment_date
 * @property float $total_price
 * @property string|null $created_at
 *
 * @property AppointmentService[] $appointmentServices
 * @property User $client
 * @property User $master
 */
class Appointment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appointment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'master_id', 'appointment_date', 'total_price'], 'required'],
            [['client_id', 'master_id'], 'integer'],
            [['status', 'cancel_reason'], 'string'],
            [['appointment_date', 'created_at'], 'safe'],
            [['total_price'], 'number'],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['client_id' => 'id']],
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
            'client_id' => 'Client ID',
            'master_id' => 'Master ID',
            'status' => 'Status',
            'cancel_reason' => 'Cancel Reason',
            'appointment_date' => 'Appointment Date',
            'total_price' => 'Total Price',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[AppointmentServices]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAppointmentServices()
    {
        return $this->hasMany(AppointmentService::class, ['appointment_id' => 'id']);
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
}
