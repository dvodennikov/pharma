<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "unit".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 *
 * @property Drug[] $drugs
 * @property Receipt[] $receipts
 */
class Unit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'unit';
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
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 4096],
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
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * Gets query for [[Drugs]].
     *
     * @return \yii\db\ActiveQuery|\common\models\queries\DrugQuery
     */
    public function getDrugs()
    {
        return $this->hasMany(Drug::class, ['measury_unit' => 'id']);
    }

    /**
     * Gets query for [[Receipts]].
     *
     * @return \yii\db\ActiveQuery|\common\models\queries\ReceiptQuery
     */
    public function getReceipts()
    {
        return $this->hasMany(Receipt::class, ['unit_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\queries\UnitQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\UnitQuery(get_called_class());
    }
}
