<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Folders]].
 *
 * @see Folders
 */
class FoldersQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Folders[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Folders|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * {@inheritdoc}
     * @return Folders|array|null
     */
    public function roots()
    {
        return parent::where(['parent_id'=>0]);
    }
}
