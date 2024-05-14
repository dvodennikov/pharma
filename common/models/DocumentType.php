<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "document_type".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $custom_fields
 *
 * @property Document[] $documents
 */
class DocumentType extends \yii\db\ActiveRecord
{
	//public $customFields;
	
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document_type';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('dbdata');
    }
    
    
    /**
     * Validate customFields fields, stores in custom_fields in db
     * @param array $customFields
     */
    public function validateCustomFields($attribute, $params, $validator)
    {
		$customFields = $this->$attribute;
		
		if (!is_array($customFields)) {
			$this->addError($attribute, \Yii::t('app', 'Wrong format: {format}', ['format' => print_r($customFields, true)]));
			
			return;
		}
		 
		 foreach ($customFields as $customField) {
			 if (isset($customField['title'])) {
				 if ((strlen($customField['title']) > 255) || (strlen($customField['title']) < 3))
					$this->addError($attribute, \Yii::t('app', 'Title must contain between 3 and 255 characters'));
			 } else {
				 $this->addError($attribute, \Yii::t('app', 'No title is given'));
			 }
			 
			 if (isset($customField['mask']) && (strlen($customField['mask']) > 255)) {
				 $this->addError($attribute, \Yii::t('app', 'Mask must contain less than or equal to 255 characters'));
			 }
		 }
	}
	
	/**
     * Parse customFields fields
     * @param array $customFields
     */
    public static function parseCustomFields($customFields)
    {
		if (!is_null($customFields) && is_array($customFields)) {
			$customFieldsValidated = [];
			foreach ($customFields as $customField) {
				$customFieldsValidated[] = DocumentType::parseCustomField($customField);
			}
			
			return $customFieldsValidated;
		}
		
		return [];
	}
	
	/**
     * Parse customField fields
     * @param array $customField
     */
    public static function parseCustomField($customField)
    {
		return [
			'title' => isset($customField['title'])?substr(strip_tags($customField['title']), 0, 255):'',
			'mask'  => isset($customField['mask'])?substr(strip_tags($customField['mask']), 0, 255):''
		];
	}

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['custom_fields'], 'validateCustomFields', 'skipOnEmpty' => false, 'skipOnError' => false],
            //['custom_fields', 'default', 'value' => []],
            //['customFields', 'validateCustomFields', 'skipOnEmpty' => false, 'skipOnError' => false],
            [['title'], 'string', 'min' => 3, 'max' => 255],
            [['title'], 'unique'],
            ['title', 'required'],
            [['serial_mask', 'number_mask'], 'string', 'max' => 255],
            [['serial_mask', 'number_mask'], 'default', 'value' => null],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function beforeValidate()
    {
		//$this->custom_fields = DocumentType::parseCustomFields($this->customFields);
		
		return parent::beforeValidate();
	}

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'serial_mask' => Yii::t('app', 'Serial Mask'),
            'number_mask' => Yii::t('app', 'Number Mask'),
            'custom_fields' => Yii::t('app', 'Custom Fields'),
        ];
    }

    /**
     * Gets query for [[Documents]].
     *
     * @return \yii\db\ActiveQuery|\common\models\queries\DocumentQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::class, ['document_type' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\queries\DocumentTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\DocumentTypeQuery(get_called_class());
    }
    
    /**
     * Get custom fields count for DocumentType by id
     * 
     * @param int id
     * @return int custom fields count for DocumentType
     */
    public static function getCustomFieldsCount($id)
    {
		return DocumentType::find(['id' => $id])->count();
	}
}
