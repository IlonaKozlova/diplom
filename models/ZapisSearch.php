<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Zapis;
use Yii;
/**
 * ZapisSearch represents the model behind the search form of `app\models\Zapis`.
 */
class ZapisSearch extends Zapis
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'client_id', 'master_id'], 'integer'],
            [['status', 'cancel_reason', 'date', 'created_at'], 'safe'],
            // [['total_price'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Zapis::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,

            'sort' => [
            'defaultOrder' => [
                'created_at' => SORT_DESC, // стортировка по умолчанию (новые)
            ]
        ],

        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'client_id' => $this->client_id,
            'master_id' => $this->master_id,
            'date' => $this->date,
            // 'total_price' => $this->total_price,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'cancel_reason', $this->cancel_reason]);

            if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'client'){
                $query->andFilterWhere([
                    'client_id' => Yii::$app->user->id
                ]);
            }

        return $dataProvider;
    }
}
