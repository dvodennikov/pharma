<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "person".
 *
 * @property int $id
 * @property string $surname
 * @property string $name
 * @property string|null $secondname
 * @property string $birthdate
 * @property int $snils
 * @property int|null $polis
 * @property string $address
 *
 * @property Document[] $documents
 * @property PersonDocument[] $personDocuments
 * @property Receipt[] $receipts
 */
class Person extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'person';
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
            [['surname', 'name', 'birthdate', 'snils', 'address'], 'required'],
            [['birthdate'], 'safe'],
            [['polis'], 'default', 'value' => null],
            ['snils', 'validateSnils'],
            ['polis', 'validatePolis'],
            [['surname', 'name', 'secondname'], 'string', 'max' => 255],
            [['address'], 'string', 'max' => 1024],
        ];
    }
    
    /**
     * Validate snils attribute
     * @param string $attribute
     * @param array $params
     * @param $validator
     */
    public function validateSnils($attribute, $params, $validator = null)
    {
		$snils = $this->$attribute;
		
		if (is_null($validator))
			$validator = $this;
			
		if (preg_match('/^\s*(\d{11})\s*$/', $snils, $matches)) {
			$this->snils = $matches[1];
		} else {
			$validator->addError($this, $attribute, Yii::t('app', 'SNILS field must contain 11 digits'));
		}
	}

    /**
     * Validate polis attribute
     * @param string $attribute
     * @param array $params
     * @param $validator
     */
    public function validatePolis($attribute, $params, $validator = null)
    {
		$polis = $this->$attribute;
		
		if (is_null($polis))
			return;
		
		if (is_null($validator))
			$validator = $this;
			
		if (preg_match('/^\s*(\d{14})\s*$/', $polis, $matches)) {
			$this->polis = $matches[1];
		} else {
			$validator->addError($this, $attribute, Yii::t('app', 'Polis field must contain 14 digits'));
		}
	}

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'surname' => Yii::t('app', 'Surname'),
            'name' => Yii::t('app', 'Name'),
            'secondname' => Yii::t('app', 'Secondname'),
            'birthdate' => Yii::t('app', 'Birthdate'),
            'snils' => Yii::t('app', 'Snils'),
            'polis' => Yii::t('app', 'Polis'),
            'address' => Yii::t('app', 'Address'),
        ];
    }

    /**
     * Gets query for [[Documents]].
     *
     * @return \yii\db\ActiveQuery|\common\models\queries\DocumentQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::class, ['id' => 'document_id'])->viaTable('person_document', ['person_id' => 'id']);
    }

    /**
     * Gets query for [[PersonDocuments]].
     *
     * @return \yii\db\ActiveQuery|\common\models\queries\PersonDocumentQuery
     */
    public function getPersonDocuments()
    {
        return $this->hasMany(PersonDocument::class, ['person_id' => 'id']);
    }

    /**
     * Gets query for [[Receipts]].
     *
     * @return \yii\db\ActiveQuery|\common\models\queries\ReceiptQuery
     */
    public function getReceipts()
    {
        return $this->hasMany(Receipt::class, ['person_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\queries\PersonQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\PersonQuery(get_called_class());
    }
}
