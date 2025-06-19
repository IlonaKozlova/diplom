<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zapis_service".
 *
 * @property int $id
 * @property int $zapis_id
 * @property int $service_id
 *
 * @property Service $service
 * @property Zapis $zapis
 */
class ZapisService extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'zapis_service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['zapis_id', 'service_id'], 'required'],
            [['zapis_id', 'service_id'], 'integer'],
            [['zapis_id'], 'exist', 'skipOnError' => true, 'targetClass' => Zapis::class, 'targetAttribute' => ['zapis_id' => 'id']],
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
            'zapis_id' => 'Zapis ID',
            'service_id' => 'Service ID',
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

    public function getZapisServices()
    {
        return $this->hasMany(ZapisService::class, ['zapis_id' => 'id']);
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
}
