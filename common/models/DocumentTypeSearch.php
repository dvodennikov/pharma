<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\DocumentType;

/**
 * DocumentTypeSearch represents the model behind the search form of `common\models\DocumentType`.
 */
class DocumentTypeSearch extends DocumentType
{
	//public $custom_fields_text;
	
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['title'], 'string', 'max' => 255], 
            ['custom_fields', 'string', 'max' => 255],
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
        $query = DocumentType::find();

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
        
        $this->custom_fields = isset($params['DocumentTypeSearch']['custom_fields'])?$params['DocumentTypeSearch']['custom_fields']:null;

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['ilike', 'title', $this->title]);
            //->andFilterWhere(['ilike', 'custom_fields_text', $this->custom_fields]);
        
        if (isset($this->custom_fields) && (strlen($this->custom_fields) > 0)) {
            if (stripos(\Yii::$app->db->dsn, 'psql') >= 0) {
				$query->andWhere('jsonb_path_query_array(custom_fields, \'$[*].title\')::text LIKE :text', ['text' => '%' . $this->custom_fields . '%'])
				      ->orWhere('jsonb_path_query_array(custom_fields, \'$[*].mask\')::text LIKE :text', ['text' => '%' . $this->custom_fields . '%']);
			} elseif (stripos(\Yii::$app->db->dsn, 'mysql') >= 0) {
				$query->andWhere('JSON_SEARCH(custom_fields, \'all\', :text) IS NOT NULL', ['text' => '%' . $this->custom_fields . '%']);
			}
        }

        return $dataProvider;
    }
}
