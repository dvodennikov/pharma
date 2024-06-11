<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Drug;

/**
 * DrugSearch represents the model behind the search form of `common\models\Drug`.
 */
class DrugSearch extends Drug
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'measury', 'measury_unit'], 'integer'],
            [['title', 'description'], 'string', 'max' => 255],
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
        $query = Drug::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        //throw new \yii\base\NotSupportedException(print_r($this, true));
        
        $query->joinWith('measuryUnit');
        
        if (!isset($params['sort']))
			$dataProvider->sort->defaultOrder = ['title' => SORT_ASC];

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'measury' => $this->measury,
            'measury_unit' => $this->measury_unit,
        ]);

        $query->andFilterWhere(['ilike', '{{drug}}.title', $this->title])
            ->andFilterWhere(['ilike', '{{drug}}.description', $this->description]);
            
         if (isset($params['initial']))
			$query->andFilterWhere(['ilike', '{{drug}}.title', mb_substr($params['initial'], 0, 1) . '%', false]);
            
        return $dataProvider;
    }
}
