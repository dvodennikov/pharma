<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "drug".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int $measury
 * @property int $measury_unit
 *
 * @property Unit $measuryUnit
 * @property Receipt[] $receipts
 */
class Drug extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'drug';
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
            [['title', 'measury', 'measury_unit'], 'required'],
            [['measury', 'measury_unit'], 'default', 'value' => null],
            [['measury', 'measury_unit'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 4096],
            [['measury_unit'], 'exist', 'skipOnError' => true, 'targetClass' => Unit::class, 'targetAttribute' => ['measury_unit' => 'id']],
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
            'measury' => Yii::t('app', 'Measury'),
            'measury_unit' => Yii::t('app', 'Measury Unit'),
        ];
    }

    /**
     * Gets query for [[MeasuryUnit]].
     *
     * @return \yii\db\ActiveQuery|\common\models\queries\UnitQuery
     */
    public function getMeasuryUnit()
    {
        return $this->hasOne(Unit::class, ['id' => 'measury_unit']);
    }

    /**
     * Gets query for [[Receipts]].
     *
     * @return \yii\db\ActiveQuery|\common\models\queries\ReceiptQuery
     */
    public function getReceipts()
    {
        return $this->hasMany(Receipt::class, ['drug_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\queries\DrugQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\DrugQuery(get_called_class());
    }
}
