<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Person;

/**
 * PersonSearch represents the model behind the search form of `common\models\Person`.
 */
class PersonSearch extends Person
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'snils', 'polis'], 'integer'],
            [['surname', 'name', 'secondname', 'birthdate', 'address'], 'safe'],
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
        $query = Person::find();

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
            //'birthdate' => $this->birthdate,
            'snils' => $this->snils,
            'polis' => $this->polis,
        ]);
        
        if (preg_match('/(\d{2}\-\d{2}\-\d{4})|(\d{4}\-\d{2}\-\d{2})/',$this->birthdate, $matches))
			$query->andFilterWhere(['birthdate' => ($matches[1] != '')?$matches[1]:$matches[2]]);

        $query->andFilterWhere(['ilike', 'surname', $this->surname])
            ->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'secondname', $this->secondname])
            ->andFilterWhere(['ilike', 'address', $this->address]);

        return $dataProvider;
    }
}
