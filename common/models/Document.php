<?php

namespace common\models;

use Yii;

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
class Document extends \yii\db\ActiveRecord
{
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
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['document_type', 'number', 'issue_date', 'issuer'], 'required'],
            [['document_type', 'number'], 'default', 'value' => null],
            [['document_type', 'number'], 'integer'],
            [['issue_date', 'expire_date', 'custom_fields'], 'safe'],
            [['serial'], 'string', 'max' => 10],
            [['issuer'], 'string', 'max' => 255],
            [['document_type'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentType::class, 'targetAttribute' => ['document_type' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'document_type' => Yii::t('app', 'Document Type'),
            'serial' => Yii::t('app', 'Serial'),
            'number' => Yii::t('app', 'Number'),
            'issue_date' => Yii::t('app', 'Issue Date'),
            'issuer' => Yii::t('app', 'Issuer'),
            'expire_date' => Yii::t('app', 'Expire Date'),
            'custom_fields' => Yii::t('app', 'Custom Fields'),
        ];
    }

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
     * Gets query for [[People]].
     *
     * @return \yii\db\ActiveQuery|\common\models\queries\PersonQuery
     */
    public function getPeople()
    {
        return $this->hasMany(Person::class, ['id' => 'person_id'])->viaTable('person_document', ['document_id' => 'id']);
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
}
