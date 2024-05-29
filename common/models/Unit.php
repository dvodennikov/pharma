<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

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
class Unit extends ActiveRecord
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
            'id'          => Yii::t('app', 'ID'),
            'title'       => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'updated_at'  => Yii::t('app', 'Updated at'),
            'updated_by'  => Yii::t('app', 'Updated by'),
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
