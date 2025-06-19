<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "finance".
 *
 * @property int $id
 * @property string $type
 * @property float $amount
 * @property string|null $description
 * @property string $date
 */
class Finance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'finance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'amount', 'date'], 'required'],
            [['type', 'description'], 'string'],
            [['amount'], 'number'],
            [['date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Тип операции',
            'amount' => 'Сумма',
            'description' => 'Описание',
            'date' => 'Дата',
        ];
    }
}
