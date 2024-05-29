<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

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
class Drug extends ActiveRecord
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
            'id'           => Yii::t('app', 'ID'),
            'title'        => Yii::t('app', 'Title'),
            'description'  => Yii::t('app', 'Description'),
            'measury'      => Yii::t('app', 'Measury'),
            'measury_unit' => Yii::t('app', 'Measury unit'),
            'updated_at'   => Yii::t('app', 'Updated at'),
            'updated_by'   => Yii::t('app', 'Updated by'),
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
        return $this->hasMany(ReceiptDrugs::class, ['drug_id' => 'id'])->leftJoin(Receipt::class, ['id' => 'drug_id']);
    }
    
    /**
     * Get Drugs by Id
     * @param integer[] $ids
     * @return \common\models\Drug[]
     */
    public static function getDrugsById($ids)
    {
		if (!is_array($ids))
			return [];

		return Drug::find()->where(['id' => $ids])->indexBy('id')->all();
	}
	
	/**
	 * Get last $limit Drugs
	 * @param integer $limit
	 * @return \common\models\Drug[]
	 */
	public static function getLastDrugs($limit = 5)
	{
		return Drug::find()->limit((int) $limit)->orderBy(['updated_at' => SORT_DESC])->all();
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
