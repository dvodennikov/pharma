<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "receipt".
 *
 * @property int $receipt_id
 * @property int $drug_id
 * @property int $quantity
 *
 * @property Drug $drug
 * @property Person $person
 * @property Unit $unit
 */
class ReceiptDrugs extends \yii\db\ActiveRecord
{
	/*public $receipt_id;
	public $drug_id;
	public $quantity;*/
	
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'receipt_drugs';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('dbdata');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
			//[['receipt_id', 'drug_id', 'quantity'], 'safe']
            [['drug_id'], 'required'],
            [['receipt_id', 'drug_id', 'quantity'], 'integer'],
            ['quantity', 'default', 'value' => 1],
            [['drug_id'], 'exist', 'skipOnError' => true, 'targetClass' => Drug::class, 'targetAttribute' => ['drug_id' => 'id']],
            //[['receipt_id'], 'exist', 'skipOnError' => true, 'targetClass' => Person::class, 'targetAttribute' => ['receipt_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'receipt_id' => Yii::t('app', 'Receipt ID'),
            'drug_id'    => Yii::t('app', 'Drug ID'),
            'quantity'   => Yii::t('app', 'Quantity'),
        ];
    }
    
    /**
     * Load ReceiptDrug fields from array. 
     * Return boolean, indicating where are any param loaded from array.
     * @params array $array
     * @params boolean $validate
     * @return boolean
     */
    public function loadFromArray($array, $validate = true)
    {
		if (is_array($array)) {
			foreach (['id', 'receipt_id', 'drug_id', 'quantity'] as $field) {
				if (isset($array[$field]) && preg_match('/^\s*(\d+)\s*$/', $array[$field], $matches)) {
					//don't update same value to prevent update SQL query
					if (!isset($this->$field) || ($this->$field != $matches[1]))
						$this->$field = $matches[1];
				} elseif ($field != 'id') {
					$this->$field = null;
					
					if (!is_null($validate) && ($field != 'receipt_id'))
						$this->addError($field, \Yii::t('app', 'Field {field} must contain integer', ['field' => $field]));
				}
			}
			
			return true;
		}
		
		return false;
	}

    /**
     * Gets query for [[Receipt]].
     *
     * @return \yii\db\ActiveQuery|\common\models\queries\ReceiptQuery
     */
    public function getReceipt()
    {
        return $this->hasOne(Receipt::class, ['id' => 'receipt_id']);
    }


    /**
     * Gets query for [[Drug]].
     *
     * @return \yii\db\ActiveQuery|\common\models\queries\DrugQuery
     */
    public function getDrug()
    {
        return $this->hasOne(Drug::class, ['id' => 'drug_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\queries\ReceiptQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\ReceiptQuery(get_called_class());
    }
    
    /**
     * Parse ReceiptDrugs
     * @param array ReceiptDrugs $receiptDrugs
     * @param \yii\base\Model $validator
     * @return array
     */
    public static function parseReceiptDrugs($receiptDrugs, $validator = null)
    {
		if (isset($validator) && (get_class($validator) != 'common\models\Receipt'))
			$validator = null;
			
		if (!is_null($receiptDrugs) && is_array($receiptDrugs)) {
			$receiptDrugsValidated = [];
			foreach ($receiptDrugs as $receiptDrug) {
				$receiptDrugsValidated[] = ReceiptDrugs::parseReceiptDrug($receiptDrug, $validator);
			}
			
			return $receiptDrugsValidated;
		}
		
		return [];
	}

    /**
     * Parse ReceiptDrug
     * @param ReceiptDrugs $receiptDrug
     * @param \yii\base\Model $validator
     * @return array
     */
    public static function parseReceiptDrug($receiptDrug, $validator = null)
    {
		if (isset($validator) && (get_class($validator) != 'common\models\Receipt'))
			$validator = null;
			
		$receiptDrugValidated = [
			'receipt_id' => null,
			'drug_id'    => null,
			'quantity'   => null
		];
		
		foreach (['receipt_id', 'drug_id'] as $field) {
			if (!isset($receiptDrug[$field])) {
				$receiptDrugValidated[$field] = null;
			} elseif (preg_match('/(\d+)/', $receiptDrug[$field], $matches)) {
				$receiptDrugValidated[$field] = $matches[1];
			} else {
				$receiptDrugValidated[$field] = null;
				
				if (!is_null($validator))
					$validator->addError($field, \Yii::t('app', 'Field {field} must be integer', ['field' => $field]));
			}
		}
		
		if (isset($receiptDrug['quantity']) && preg_match('/(\d+)/', $receiptDrug['quantity'], $matches)) {
			$receiptDrugValidated['quantity'] = $matches[1];
		} else {
			$receiptDrugValidated['quantity'] = null;
				
			if (!is_null($validator))
				$validator->addError('quantity', \Yii::t('app', 'Field quantity must be integer'));

		}
		
		return $receiptDrugValidated;
	}
    
    /**
     * Load ReceiptDrugs from POST request from $receiptDrugsSource or
     * load ReceiptDrugs from $receiptDrugsSource if it is an array
     * @param array|string $receiptDrugsSource
     * @params yii\db\ActiveRecord $validator
     * @return ReceiptDrug[]
     */
    public static function loadReceiptDrugs($receiptDrugsSource = 'ReceiptDrugs', $validator = null)
    {
		$receiptDrugsPost = [];
		
		if (is_string($receiptDrugsSource)) {
			$receiptDrugsPost = \Yii::$app->request->post($receiptDrugsSource);
		} elseif (is_array($receiptDrugsSource)) {
			$receiptDrugsPost = $receiptDrugsSource;
		} else {
			if (!is_null($validator))
				$validator->addError('drugs', \Yii::t('app', 'No drugs specified'));
			
			return [];
		}
		
		$receiptDrugs = [];
		
		if (is_array($receiptDrugsPost)) {
			$drugsIds        = [];
			$receiptDrugsNew = [];
			for ($idx = 0, $receiptDrugsPostCount = count($receiptDrugsPost); $idx < $receiptDrugsPostCount; $idx++) {
				//$receiptDrugs[] = ReceiptDrugs::loadReceiptDrug($receiptDrugsPost[$idx]);
				//add id for existed drug
				if (isset($receiptDrugsPost[$idx]['id']) && preg_match('/\d+/', $receiptDrugsPost[$idx]['id'])) {
					$drugsIds[] = $receiptDrugsPost[$idx]['id'];
				//add new drug
				} else {
					$receiptDrugsNew[$idx] = ReceiptDrugs::loadReceiptDrug($receiptDrugsPost[$idx]);
				}
			}
			
			//throw new \yii\base\NotSupportedException(print_r($drugsIds, true));
			$receiptDrugsDb = [];
			//load existed drug
			if (count($drugsIds) > 0) {
				//array_push($receiptDrugs, ReceiptDrugs::find()->where(['IN', 'drug_id', $drugsIds])->all());
				$receiptDrugsDb = ReceiptDrugs::find()->where(['IN', 'id', $drugsIds])->all();
				
			}
			
			$idx = -1;
			foreach ($receiptDrugsPost as $receiptDrugPost) {
				$idx++;
				//add $receiptDrugNew[$idx] to $receiptDrugs array and go to $idx++ position
				if (isset($receiptDrugsNew[$idx])) {
					$receiptDrugs[] = $receiptDrugsNew[$idx];
					//throw new \yii\base\NotSupportedException(print_r($receiptDrugs, true));
					
					continue;
				}
				
				if (isset($receiptDrugPost['drug_id'])) {
					//otherwise add $receiptDrugDb to $receiptDrugs array
					foreach ($receiptDrugsDb as &$receiptDrugDb) {
						if ($receiptDrugPost['id'] == $receiptDrugDb->id) {
							//load values from POST request
							$receiptDrugDb->loadFromArray($receiptDrugPost);
							$receiptDrugs[] = $receiptDrugDb;
							
							continue 2;
						}
					}
				}
			}
			
			/*if (count($receiptDrugsDb) > 0)	
				array_push($receiptDrugs, $receiptDrugsDb);*/
				
			//throw new \yii\base\NotSupportedException(print_r($receiptDrugs, true));
			if ((count($receiptDrugs) == 0) && !is_null($validator))
				$validator->addError('drugs', \Yii::t('app', 'No drugs specified'));
			
			return $receiptDrugs;
		}
		
		if (!is_null($validator))
				$validator->addError('drugs', \Yii::t('app', 'No drugs specified'));
		
		return [];
	}

    /**
     * Load ReceiptDrug from POST request from $receiptDrugsName or
     * load ReceiptDrug from $receiptDrugSource if it is an array
     * @param string $name
     * @param boolean $validate
     * @return ReceiptDrug
     */
    public static function loadReceiptDrug($receiptDrugSource = 'ReceiptDrug', $validate = true)
    {
		$id = null;
		if (is_string($receiptDrugSource))
			$receiptDrugSource = \Yii::$app->request->post($receiptDrugSource);
		
		if (is_array($receiptDrugSource)) {
			$receiptDrug = (isset($receiptDrugSource['id']) && preg_match('/(\d+)/', $receiptDrugSource['id'], $matches))?ReceiptDrugs::findOne($matches[1]):new ReceiptDrugs();
			//$receiptDrug->load($receiptDrugSource);
			$receiptDrug->loadFromArray($receiptDrugSource);
			//$receiptDrug->load(['drug_id' => $receiptDrugSource['drug_id'], 'quantity' => $receiptDrugSource['quantity']]);
			/*foreach (['receipt_id', 'drug_id', 'quantity'] as $field)
				$receiptDrug->$field = isset($receiptDrugSource[$field])?(int) $receiptDrugSource[$field]:null;*/
			//throw new \yii\base\NotSupportedException(print_r($receiptDrug, true));
		} else {
			if ($validate)
				foreach (['drug_id', 'quantity'] as $field)
					$receiptDrug->addError($field, \Yii::t('app', 'Not found value for field {field}', ['field' => $field]));
		}
		
		return $receiptDrug;
	}
	
	/**
	 * Get ReceiptDrugs with Drug by drug title
	 * @param string $title
	 * @return ReceiptDrug[]
	 */
	public static function getReceiptDrugsByTitle($title)
	{
		return ReceiptDrugs::find()->joinWith('drug')->where(['ILIKE', 'drug.title', $title])->all();
	}
	
	/**
	 * Get ReceiptDrugs with Drug by receiptIds
	 * @param int|array $receiptIds
	 * @return ReceiptDrug[]
	 */
	public static function getReceiptDrugsByReceiptIds($receiptIds)
	{
		if (!is_array($receiptIds))
			$receiptIds = [ (int)$receiptIds ];
			
		return \common\models\ReceiptDrugs::find()->joinWith('drug')->where(['IN', 'receipt_id', $receiptIds])/*->indexBy('receipt_id')*/->all();
	}
}
