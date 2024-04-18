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
            [['id', 'number'], 'integer'],
            [['document_type', 'serial', 'issue_date', 'surname', 'name', 'secondname', 'issuer', 'expire_date', 'custom_fields'], 'safe'],
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
        
        $query->joinWith('documentType');//join('INNER JOIN', 'document_type', 'document.document_type=document_type.id');
        $document_type = isset($params['DocumentSearch']['document_type'])?$params['DocumentSearch']['document_type']:null;//$this->document_type;
        $this->document_type = '';

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        $this->custom_fields = isset($params['DocumentSearch']['custom_fields'])?$params['DocumentSearch']['custom_fields']:null;

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            //'document_type' => $this->document_type,
            'number' => $this->number,
            'issue_date' => $this->issue_date,
            'expire_date' => $this->expire_date,
        ]);

        $query->andFilterWhere(['ilike', 'serial', $this->serial])
            ->andFilterWhere(['ilike', 'issuer', $this->issuer])
            ->andFilterWhere(['ilike', 'surname', $this->surname])
            ->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'secondname', $this->secondname])
            //->andFilterWhere(['ilike', 'custom_fields', $this->custom_fields])
            ->andFilterWhere(['ilike', DocumentType::tableName() . '.title', $document_type]);
            
        if (isset($this->custom_fields) && (strlen($this->custom_fields) > 0)) {
            if (stripos(\Yii::$app->db->dsn, 'psql') >= 0) {
				$query->andWhere('jsonb_path_query_array(' . Document::tableName() . '.custom_fields, \'$[*].title\')::text LIKE :text', ['text' => '%' . $this->custom_fields . '%'])
				      ->orWhere('jsonb_path_query_array(' . Document::tableName() . '.custom_fields, \'$[*].value\')::text LIKE :text', ['text' => '%' . $this->custom_fields . '%']);
			} elseif (stripos(\Yii::$app->db->dsn, 'mysql') >= 0) {
				$query->andWhere('JSON_SEARCH(' . Document::tableName() . '.custom_fields, \'all\', :text) IS NOT NULL', ['text' => '%' . $this->custom_fields . '%']);
			}
        }

        return $dataProvider;
    }
}
