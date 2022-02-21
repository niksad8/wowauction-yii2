<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ah_sub_cat".
 *
 * @property int $id
 * @property string $name
 * @property int $parent_id
 */
class AhSubCat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ah_sub_cat';
    }
    public function getParent(){
        return $this->hasOne(AhMainCat::class,['parent_id'=>'id']);
    }
    public function getSlots(){
        return $this->hasOne(SlotMaster::class,['parent_id'=>'id']);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['name'], 'string', 'max' => 95],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'parent_id' => 'Parent ID',
        ];
    }
}
