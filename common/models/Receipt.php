<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "receipt".
 *
 * @property int $id
 * @property string|null $number
 * @property int $person_id
 * @property int $drug_id
 * @property int $quantity
 * @property int $unit_id
 *
 * @property Drug $drug
 * @property Person $person
 * @property Unit $unit
 */
class Receipt extends \yii\db\ActiveRecord
{
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
            [['person_id', 'drug_id', 'unit_id'], 'required'],
            [['person_id', 'drug_id', 'quantity', 'unit_id'], 'default', 'value' => null],
            [['person_id', 'drug_id', 'quantity', 'unit_id'], 'integer'],
            [['number'], 'string', 'max' => 10],
            [['drug_id'], 'exist', 'skipOnError' => true, 'targetClass' => Drug::class, 'targetAttribute' => ['drug_id' => 'id']],
            [['person_id'], 'exist', 'skipOnError' => true, 'targetClass' => Person::class, 'targetAttribute' => ['person_id' => 'id']],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Unit::class, 'targetAttribute' => ['unit_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'number' => Yii::t('app', 'Number'),
            'person_id' => Yii::t('app', 'Person ID'),
            'drug_id' => Yii::t('app', 'Drug ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'unit_id' => Yii::t('app', 'Unit ID'),
        ];
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
     * Gets query for [[Person]].
     *
     * @return \yii\db\ActiveQuery|\common\models\queries\PersonQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::class, ['id' => 'person_id']);
    }

    /**
     * Gets query for [[Unit]].
     *
     * @return \yii\db\ActiveQuery|\common\models\queries\UnitQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Unit::class, ['id' => 'unit_id']);
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
