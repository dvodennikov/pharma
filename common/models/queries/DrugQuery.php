<?php

namespace common\models\queries;

/**
 * This is the ActiveQuery class for [[\common\models\Drug]].
 *
 * @see \common\models\Drug
 */
class DrugQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\Drug[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Drug|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
