<?php

namespace app\models;

use klisl\nestable\NestableBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "folders".
 *
 * @property int $id
 * @property int $parent_id
 * @property string $name
 * @property int $left
 * @property int $right
 * @property int $depth
 * @property int $created_at
 * @property int $updated_at
 */
class Folders extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'folders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'left', 'right', 'depth', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'name' => Yii::t('app', 'Name'),
            'left' => Yii::t('app', 'Left'),
            'right' => Yii::t('app', 'Right'),
            'depth' => Yii::t('app', 'Depth'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return FoldersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FoldersQuery(get_called_class());
    }

    public function behaviors() {
        return [
            TimestampBehavior::class,
            'tree' => [
                'class' => NestableBehavior::class,
                'leftAttribute' => 'left',
                'rightAttribute' => 'right',
                'depthAttribute' => 'depth',
                ],
        ];
    }

    /**
     * Get parent's ID
     * @return \yii\db\ActiveQuery
     */
    public function getParentId()
    {
        $parent = $this->parent;
        return $parent ? $parent->id : null;
    }

    /**
     * Get parent's node
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->parents(1)->one();
    }

    /**
     * Get a full tree as a list, except the node and its children
     * @param  integer $node_id node's ID
     * @return array array of node
     */
    public static function getTree($node_id = 0)
    {
        // don't include children and the node
        $children = [];

        if ( ! empty($node_id))
            $children = array_merge(
                self::findOne($node_id)->children()->column(),
                [$node_id]
            );

        $rows = self::find()->
        select('id, name, depth')->
        where(['NOT IN', 'id', $children])->
        orderBy('parent_id, left')->
        all();

        $return = [];
        foreach ($rows as $row)
            $return[$row->id] = str_repeat('-', $row->depth) . ' ' . $row->name;

        return $return;
    }
}
