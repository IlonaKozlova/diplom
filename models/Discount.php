<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "discount".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $start_date
 * @property string $end_date
 * @property float $discount_percentage
 */
class Discount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'discount';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'start_date', 'end_date', 'discount_percentage'], 'required'],
            [['description'], 'string'],
            [['start_date', 'end_date'], 'safe'],
            [['discount_percentage'], 'number'],
            [['name'], 'string', 'max' => 100],
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
            'start_date' => 'Начало действия',
            'end_date' => 'Конец действия',
            'discount_percentage' => 'Процент скидки',
        ];
    }
}
