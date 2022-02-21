<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "item_sub_class".
 *
 * @property int $id
 * @property int $class_id
 * @property string $name
 */
class ItemSubClass extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item_sub_class';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','class_id','name'], 'required'],
            [['id', 'class_id'], 'integer'],
            [['name'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class_id' => 'Class ID',
            'name' => 'Name',
        ];
    }
}
