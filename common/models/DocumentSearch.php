<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Document;

/**
 * DocumentSearch represents the model behind the search form of `common\models\Document`.
 */
class DocumentSearch extends Document
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'document_type', 'number'], 'integer'],
            [['serial', 'issue_date', 'issuer', 'expire_date', 'custom_fields'], 'safe'],
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
        $query = Document::find();

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
            'document_type' => $this->document_type,
            'number' => $this->number,
            'issue_date' => $this->issue_date,
            'expire_date' => $this->expire_date,
        ]);

        $query->andFilterWhere(['ilike', 'serial', $this->serial])
            ->andFilterWhere(['ilike', 'issuer', $this->issuer])
            ->andFilterWhere(['ilike', 'custom_fields', $this->custom_fields]);

        return $dataProvider;
    }
}
