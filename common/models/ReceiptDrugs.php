<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "receipt".
 *
 * @property int $person_id
 * @property int $drug_id
 * @property int $quantity
 *
 * @property Drug $drug
 * @property Person $person
 * @property Unit $unit
 */
class ReceiptDrugs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'receipt_drugs';
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
            [['person_id', 'drug_id'], 'required'],
            [['person_id', 'drug_id', 'quantity'], 'integer'],
            ['quantity', 'default', 'value' => 1],
            [['drug_id'], 'exist', 'skipOnError' => true, 'targetClass' => Drug::class, 'targetAttribute' => ['drug_id' => 'id']],
            [['person_id'], 'exist', 'skipOnError' => true, 'targetClass' => Person::class, 'targetAttribute' => ['person_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'person_id' => Yii::t('app', 'Person ID'),
            'drug_id' => Yii::t('app', 'Drug ID'),
            'quantity' => Yii::t('app', 'Quantity'),
        ];
    }

    /**
     * Gets query for [[Receipt]].
     *
     * @return \yii\db\ActiveQuery|\common\models\queries\ReceiptQuery
     */
    public function getReceipt()
    {
        return $this->hasOne(Receipt::class, ['id' => 'drug_id']);
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
     * {@inheritdoc}
     * @return \common\models\queries\ReceiptQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\ReceiptQuery(get_called_class());
    }
}
