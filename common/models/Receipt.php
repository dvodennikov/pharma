<?php

namespace common\models;

use Yii;
use common\helpers\Pharma;

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
        ];
    }
    
    /**
     * Validate date fields
     * @param string $attribute
     * @param array $params
     */
    public function validateDate($attribute, $params)
    {
		$date = $this->$attribute;
		if (preg_match('/(\d{4})\-(\d{2})\-(\d{2})/', $date)) {
			if (isset($params['min'])) {
				if (Pharma::dateToTimestamp($date) < Pharma::dateToTimestamp($params['min'])) {
					$this->addError($attribute, \Yii::t('app', 'Date {date} must be greater than or equal to {min}', ['date' => $date, 'min' => $params['min']]));
				}
			}
			
			if (isset($params['max'])) {
				if (Pharma::dateToTimestamp($date) > Pharma::dateToTimestamp($params['max'])) {
					$this->addError($attribute, \Yii::t('app', 'Date {date} must be less than or equal to {max}', ['date' => $date, 'max' => $params['max']]));
				}
			}
		} else {
			$this->addError($attribute, \Yii::t('app', 'Wrong date: {date}', ['date' => $date]));
		}
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
     * {@inheritdoc}
     * @return \common\models\queries\ReceiptQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\ReceiptQuery(get_called_class());
    }
}
