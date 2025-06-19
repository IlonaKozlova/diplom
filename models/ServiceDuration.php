<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "service_duration".
 *
 * @property int $id
 * @property int $service_id
 * @property int $duration_minutes
 *
 * @property Service $service
 */
class ServiceDuration extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service_duration';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['service_id', 'duration_minutes'], 'required'],
            [['service_id', 'duration_minutes'], 'integer'],
            [['service_id'], 'exist', 'skipOnError' => true, 'targetClass' => Service::class, 'targetAttribute' => ['service_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'service_id' => 'Service ID',
            'duration_minutes' => 'Duration Minutes',
        ];
    }

    /**
     * Gets query for [[Service]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getService()
    {
        return $this->hasOne(Service::class, ['id' => 'service_id']);
    }
}
