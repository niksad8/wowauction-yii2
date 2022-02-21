<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "slot_master".
 *
 * @property int $id
 * @property string $name
 * @property int $parent_id
 * @property int $translation_id
 */
class SlotMaster extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'slot_master';
    }

    public function getParent(){
        return $this->hasOne(AhSubCat::class,['parent_id'=>'id']);
    }
    public function getTranslation(){
        return $this->hasOne(SlotTranslation::class,['translation_id'=>'id']);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'parent_id','translation_id'], 'integer'],
            [['name'], 'string', 'max' => 90],
            [['id'], 'unique'],
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
            'translation_id'=>'Translation ID'
        ];
    }
}
