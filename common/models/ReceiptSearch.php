<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Receipt;
use common\models\ReceiptDrugs;

/**
 * ReceiptSearch represents the model behind the search form of `common\models\Receipt`.
 */
class ReceiptSearch extends Receipt
{
	public $snils;
	public $drugs;
	
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['person_id'], 'string', 'max' => 255],
            [['snils'], 'string', 'max' => 11],
            [['number'], 'string', 'max' => 10],
            [['drugs'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
		$labels = parent::attributeLabels();
		
		$labels['snils'] = \Yii::t('app', 'SNILS');
		$labels['person_id'] = \Yii::t('app', 'Person');
		
		return $labels;
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
        
        if (isset(\Yii::$app->params['pageSize']))
			$dataProvider->pagination->pageSize = (int) \Yii::$app->params['pageSize'];
        
        $query->joinWith('person');

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            //'person_id' => $this->person_id,
        ]);

        $query->andFilterWhere(['ILIKE', 'number', $this->number]);
        
        if (isset($this->person_id))
			$query->andFilterWhere(['OR', ['ILIKE', 'person.surname', $this->person_id], ['ILIKE', 'person.name', $this->person_id], ['ILIKE', 'person.secondname', $this->person_id]]);

        if (isset($this->snils))
			$query->andFilterWhere(['ILIKE', 'person.snils', $this->snils]);
			
		if (isset($this->drugs) && ($this->drugs != '')) {
			$receiptDrugs = ReceiptDrugs::getReceiptDrugsByTitle($this->drugs);
			$receiptIds = [];
			
			foreach ($receiptDrugs as $receiptDrug)
				$receiptIds[] = $receiptDrug->receipt_id;
			
			$query->andFilterWhere(['IN', 'receipt.id', $receiptIds]);
		}
			
		// get data for drugs column of the receipts
		$receipts    = $dataProvider->getModels();
		$receiptIds = [];
		foreach ($receipts as $receipt) {
			$receiptIds[] = $receipt->id;
		}
		
		$receiptDrugs = ReceiptDrugs::getReceiptDrugsByReceiptIds($receiptIds);
		//throw new \yii\base\NotSupportedException(print_r($receiptDrugs, true));
		foreach ($receipts as &$receipt) {
			$receipt->drugs = [];
			if (isset($receiptDrugs[$receipt->id]))
			foreach ($receiptDrugs as $receiptDrug)
				if ($receiptDrug->receipt_id == $receipt->id)
					$receipt->drugs[] = $receiptDrug->drug->title . ': ' . $receiptDrug->quantity;
		}
		//throw new \yii\base\NotSupportedException(print_r($receipts, true));

        return $dataProvider;
    }
}
