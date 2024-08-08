<?php

namespace common\models;

use Yii;
use common\models\traitDate;
use \yii\web\NotFoundHttpException;
use \yii\web\BadRequestHttpException;

/**
 * This is the model class for table "receipt".
 *
 * @property int $id
 * @property string|null $number
 * @property int $person_id
 *
 * @property Drug $drug
 * @property Person $person
 * @property Unit $unit
 */
class Receipt extends \yii\db\ActiveRecord
{
	use traitDate;
	
	public $drugs;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'receipt';
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
            [['person_id'], 'required'],
            [['person_id'], 'default', 'value' => null],
            [['person_id'], 'integer'],
            [['number'], 'string', 'max' => 10],
            [['number'], 'unique'],
            /*['issue_date', 'date', 'timestampAttribute' => 'issue_date'],
            ['sell_date', 'date', 'timestampAttribute' => 'sell_date'],
            [['issue_date', 'sell_date'], 'default', 'value' => null],
            ['issue_date', 'compare', 'compareAttribute' => 'sell_date', 'operator' => '<=', 'enableClientValidation' => false],*/
            ['issue_date', 'validateDate', 'params' => ['max' => date('Y-m-d')]],
            ['sell_date', 'validateDate', 'params' => ['min' => $this->issue_date, 'max' => date('Y-m-d')]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('app', 'ID'),
            'number'     => Yii::t('app', 'Number'),
            'person_id'  => Yii::t('app', 'Person ID'),
            'issue_date' => Yii::t('app', 'Issue date'),
            'sell_date'  => Yii::t('app', 'Sell date'),
            'updated_at' => Yii::t('app', 'Updated at'),
            'updated_by' => Yii::t('app', 'Updated by'),
            'snils'      => Yii::t('app', 'SNILS'),
            'drugs'      => Yii::t('app', 'Drugs'),
        ];
    }
    

    /**
     * Gets query for [[Person]].
     *
     * @return \yii\db\ActiveQuery|\common\models\queries\PersonQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::class, ['id' => 'person_id']);
    }
    
    /**
     * Get Drugs for the Receipt
     * @return \yii\db\ActiveQuery|\common\models\queries\DrugQuery
     */
    public function getDrugsDescription()
    {
		//return Drug::find()->innerJoinWith('receipt_drugs')->where(['receipt_drugs.receipt_id' => $this->id])->all();
		return $this->hasMany(Drug::class, ['id' => 'drug_id'])->viaTable('receipt_drugs', ['receipt_id' => 'id']);
	}
	
	/**
	 * Get ReceiptDrugs for the Receipt
	 * @return \yii\db\ActiveQuery
	 */
	public function getReceiptDrugs()
	{
		return $this->hasMany(ReceiptDrugs::class, ['receipt_id' => 'id']);
	}
	
	public function getDrugsList()
	{
		//return ReceiptDrugs::find()->with('drug')->where(['receipt_id' => $this->id])->all();
		//return $this->hasMany(ReceiptDrugs::class, ['receipt_id' => 'id'])->with('drug');
		//return ReceiptDrugs::find()->select(['receipt_drugs.quantity', 'drug.title'])->joinWith('drug')->where(['receipt_drugs.receipt_id' => $this->id]);
		//return $this->hasMany(ReceiptDrugs::class, ['receipt_id' => 'id'])->joinWith('drug')->addSelect(['receipt_drugs.*', 'drug.*']);
		
		$receiptDrugs = ReceiptDrugs::find()->where(['receipt_id' => $this->id])->all();
		$drugsIds     = [];
		
		foreach ($receiptDrugs as $receiptDrug) {
			$drugsIds[] = $receiptDrug->drug_id;
		}
		
		if (count($drugsIds) <= 0)
			return [];
		
		$drugs     = Drug::find()->where(['in', 'id', $drugsIds])->asArray()->indexBy('id')->all();
		$drugsList = [];
		
		foreach ($receiptDrugs as $receiptDrug) {
			$drugsList[] = (object) [
				'id'          => $receiptDrug->drug_id,
				'title'       => $drugs[$receiptDrug->drug_id]['title'],
				'description' => $drugs[$receiptDrug->drug_id]['description'],
				'quantity'    => $receiptDrug->quantity,
			];
		}
		
		return $drugsList;
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
     * Get last $limit number Receipts with Drugs order by issue_date
     * @param int $limit
     * @return Receipt[]
     */
    public static function getLastReceipts($limit = 5)
    {
		$receipts   = Receipt::find()->joinWith('person')->orderBy(['updated_at' => SORT_DESC])->limit($limit)->all();
		$receiptIds = [];
		
		foreach ($receipts as $receipt) {
			$receiptIds[] = $receipt->id;
		}
		
		$receiptDrugs = ReceiptDrugs::getReceiptDrugsByReceiptIds($receiptIds);
		
		foreach ($receipts as &$receipt) {
			$receipt->drugs = [];
			//if (isset($receiptDrugs[$receipt->id]))
			foreach ($receiptDrugs as $receiptDrug)
				if ($receiptDrug->receipt_id == $receipt->id)
					$receipt->drugs[] = $receiptDrug->drug->title . ': ' . $receiptDrug->quantity;
		}
		
		return $receipts;
	}
	
	/**
     * Updates Receipt with ReceiptDrugs
     * @param bool $clearReceiptDrugs
     * @return string|\yii\web\Response
     * @throws BadRequestHttpException if fails to parse/validate data
     */
    public function updateReceiptWithReceiptDrugs($clearReceiptDrugs = true, $receiptDrugs = null)
    {
		$receiptDrugs = ReceiptDrugs::loadReceiptDrugs(
			is_array($receiptDrugs)?$receiptDrugs:'ReceiptDrugs', 
			$this
		);
		
		if (count($receiptDrugs) < 1) {
			$this->addError('drugs', Yii::t('app', 'No drugs specified'));
			
			throw new BadRequestHttpException(Yii::t('app', 'No drugs specified'));
		}
		
		foreach ($receiptDrugs as $receiptDrug)
			if ($receiptDrug->hasErrors()) {
				$errors = $receiptDrug->getFirstErrors();
				$this->addError('drugs', $errors[0]);
				
				throw new BadRequestHttpException($errors[0]);
			}

		if (!$this->hasErrors() && $this->save()) {
			$receiptId = $this->getPrimaryKey();
			$drugIds   = [];
			
			foreach ($receiptDrugs as &$receiptDrug) {
				$receiptDrug->receipt_id = $receiptId;
				$receiptDrug->save();
					
				$drugIds[] = isset($receiptDrug->id)?$receiptDrug->id:$receiptDrug->getPrimaryKey();
			}
			
			if ($clearReceiptDrugs)
				ReceiptDrugs::deleteAll(
					['AND', 'receipt_id=:receipt_id', ['NOT', ['IN', 'id', $drugIds]]], 
					['receipt_id' => $receiptId]
				);
			
			return true;
		} else {
			$errors = $this->getFirstErrors();
			throw new BadRequestHttpException(isset($errors[0])?$errors[0]:print_r($errors, true));
		}
    }
}
