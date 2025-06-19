<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MasterWorkingDays;

/**
 * MasterWorkingDaysSearch represents the model behind the search form of `app\models\MasterWorkingDays`.
 */
class MasterWorkingDaysSearch extends MasterWorkingDays
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'master_id', 'is_working'], 'integer'],
            [['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday', 'comment'], 'safe'],
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
        $query = MasterWorkingDays::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'master_id' => $this->master_id,
            'is_working' => $this->is_working,
        ]);

        $query->andFilterWhere(['like', 'monday', $this->monday])
            ->andFilterWhere(['like', 'tuesday', $this->tuesday])
            ->andFilterWhere(['like', 'wednesday', $this->wednesday])
            ->andFilterWhere(['like', 'thursday', $this->thursday])
            ->andFilterWhere(['like', 'friday', $this->friday])
            ->andFilterWhere(['like', 'saturday', $this->saturday])
            ->andFilterWhere(['like', 'sunday', $this->sunday])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
