<?php

namespace common\models\queries;

/**
 * This is the ActiveQuery class for [[\common\models\Person]].
 *
 * @see \common\models\Person
 */
class PersonQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\Person[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Person|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
