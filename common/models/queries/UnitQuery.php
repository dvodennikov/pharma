<?php

namespace common\models\queries;

/**
 * This is the ActiveQuery class for [[\common\models\Unit]].
 *
 * @see \common\models\Unit
 */
class UnitQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\Unit[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Unit|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
