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
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['custom_fields'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['title'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
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
}
