<?php

namespace common\models;

use Yii;
use common\models\traitDate;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "document".
 *
 * @property int $id
 * @property int $document_type
 * @property string|null $serial
 * @property int $number
 * @property string $issue_date
 * @property string $issuer
 * @property string|null $expire_date
 * @property string|null $custom_fields
 *
 * @property DocumentType $documentType
 * @property Person[] $people
 * @property PersonDocument[] $personDocuments
 */
class Document extends ActiveRecord
{
	use traitDate;
	
	//public $customFields;
	
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('dbdata');
    }
    
    /**
     * Behaviors for DocumentType
     */
    public function behaviors()
    {
		return [
			[
				'class'      => TimestampBehavior::class,
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => ['updated_at'],
					ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
				],
			],
			[
				'class'              => BlameableBehavior::class,
				'createdByAttribute' => 'updated_by',
				'updatedByAttribute' => 'updated_by',
			],
		];
	}
    
    /**
     * Validate customFields fields, stores in custom_fields in db
     * @param array $customFields
     */
    public function validateCustomFields($attribute, $params, $validator)
    {
		$customFields = $this->$attribute;
		
		if (is_null($customFields)) {
			$this->$attribute = [];
			
			return;
		}
		
		if (!is_array($customFields)) {
			$this->addError($attribute, \Yii::t('app', 'Wrong format: {format}', ['format' => print_r($customFields, true)]));
			
			return;
		}
		
		Document::parseCustomFields($customFields, $params['documentType'], $this);
	}
	
	/**
     * Parse customFields fields
     * @param array $customFields
     */
    public static function parseCustomFields($customFields, $documentTypeId, $validator = null)
    {
		if (is_null($documentTypeId)) 
			return 	[];
			
		$documentType = \common\models\DocumentType::findOne(['id' => $documentTypeId]);
		
		if (!is_null($customFields) && is_array($customFields) && !is_null($documentType->custom_fields) && is_array($documentType->custom_fields)) {
			$customFieldsValidated = [];
			foreach ($documentType->custom_fields as $fieldParams) {
				foreach ($customFields as $customField) {
					if ($customField['title'] == $fieldParams['title']) {
						$customFieldsValidated[] = Document::parseCustomField($customField, $fieldParams['mask'], $validator);
						
						continue 2;
					}
				}
				
				$customFieldsValidated[] = ['title' => $fieldParams['title'], 'value' => null];
			}
			
			return $customFieldsValidated;
		}
		
		return [];
	}
	
	/**
     * Parse customField fields
     * @param array $customFields
     * @param string $mask
     */
    public static function parseCustomField($customField, $mask, $validator = null)
    {
		if (is_null($mask) || (strlen($mask) == 0)) {
			return [
				'title' => substr($customField['title'], 0, 255),
				'value' => substr($customField['value'], 0, 4096)
			];
		} elseif (isset($customField['value']) && preg_match('/^\s*(' . $mask . ')\s*$/', $customField['value'], $matches)) {
			return [
				'title' => substr($customField['title'], 0, 255),
				'value' => $matches[1]
			];
		}
		
		if (!is_null($validator)) {
			$validator->addError('custom_fields', 
				Yii::t('app', 'Value of the field {field} does not match mask {mask}', [ 'field' => $customField['title'], 'mask' => $mask]));
		}
		
		return [
			'title' => substr($customField['title'], 0, 255),
			'value' => null
		];
	}

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['document_type', 'number', 'issue_date', 'issuer', 'surname', 'name', 'birthdate', 'custom_fields'], 'required'],
            [['document_type', 'number', 'secondname', 'person_id'], 'default', 'value' => null],
            [['document_type', 'number', 'person_id'], 'integer'],
            //[['custom_fields'], 'safe'],
            ['birthdate', 'validateDate', 'params' => ['max' => date('Y-m-d')]],
            ['issue_date', 'validateDate', 'params' => ['min' => $this->birthdate, 'max' => date('Y-m-d')]],
            ['expire_date', 'validateDate', 'params' => ['min' => $this->issue_date, 'max' => date('Y-m-d')]],
            [['serial'], 'string', 'max' => 10],
            [['surname', 'name', 'secondname', 'issuer'], 'string', 'max' => 255],
            [['document_type'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentType::class, 'targetAttribute' => ['document_type' => 'id']],
            ['custom_fields', 'validateCustomFields', 'params' => ['documentType' => $this->document_type], 'skipOnEmpty' => false, 'skipOnError' => false],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'            => Yii::t('app', 'ID'),
            'document_type' => Yii::t('app', 'Document type'),
            'serial'        => Yii::t('app', 'Serial'),
            'number'        => Yii::t('app', 'Number'),
            'issue_date'    => Yii::t('app', 'Issue date'),
            'issuer'        => Yii::t('app', 'Issuer'),
            'expire_date'   => Yii::t('app', 'Expire date'),
            'custom_fields' => Yii::t('app', 'Custom fields'),
            'person_id'     => Yii::t('app', 'Person'),
            'updated_at'    => Yii::t('app', 'Updated at'),
            'updated_by'    => Yii::t('app', 'Updated by'),
            'surname'       => Yii::t('app', 'Surname'),
            'name'          => Yii::t('app', 'Name'),
            'secondname'    => Yii::t('app', 'Secondname'),
        ];
    }
    
    /**
     * {@inheritdoc}
     * Convert custom_fields attribute from array of arrays to array of objects before save.
     * @return \common\models\queries\DocumentQuery the active query used by this AR class.
     */
    /*public function beforeSave($insert)
    {
		if (!parent::beforeSave($insert)) {
			return false;
		}
		
		$custom_fields = [];
		foreach ($this->custom_fields as $customField) {
			$custom_fields[] = (object) $customField;
		}
		
		$this->custom_fields = $custom_fields;
		
		return true;
	}*/

    /**
     * Gets query for [[DocumentType]].
     *
     * @return \yii\db\ActiveQuery|\common\models\queries\DocumentTypeQuery
     */
    public function getDocumentType()
    {
        return $this->hasOne(DocumentType::class, ['id' => 'document_type']);
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
     * Gets query for [[PersonDocuments]].
     *
     * @return \yii\db\ActiveQuery|\common\models\queries\PersonDocumentQuery
     */
    public function getPersonDocuments()
    {
        return $this->hasMany(PersonDocument::class, ['document_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\queries\DocumentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\DocumentQuery(get_called_class());
    }
    
    /**
     * Convert date to timestamp
     * @param string date
     */
    public static function dateToTimestamp($date)
    {
		if (preg_match('/(\d{4})\-(\d{2})\-(\d{2})/', $date, $matches)) {
			return mktime(0, 0, 0, $matches[2], $matches[3], $matches[1]);
		}
		
		return 0;
	}
}
