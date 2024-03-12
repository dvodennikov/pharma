<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Receipt;

/**
 * ReceiptSearch represents the model behind the search form of `common\models\Receipt`.
 */
class ReceiptSearch extends Receipt
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'person_id', 'drug_id', 'quantity', 'unit_id'], 'integer'],
            [['number'], 'safe'],
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
        $query = Receipt::find();

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
            'person_id' => $this->person_id,
            'drug_id' => $this->drug_id,
            'quantity' => $this->quantity,
            'unit_id' => $this->unit_id,
        ]);

        $query->andFilterWhere(['ilike', 'number', $this->number]);

        return $dataProvider;
    }
}
