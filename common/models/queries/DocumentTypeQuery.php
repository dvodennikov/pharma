<?php

namespace common\models\queries;

/**
 * This is the ActiveQuery class for [[\common\models\DocumentType]].
 *
 * @see \common\models\DocumentType
 */
class DocumentTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\DocumentType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\DocumentType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
