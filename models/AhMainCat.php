<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ah_main_cat".
 *
 * @property int $id
 * @property string $name
 */
class AhMainCat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ah_main_cat';
    }
    public function getSubcats(){
        return $this->hasMany(AhSubCat::class,['parent_id'=>'id']);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
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
        ];
    }
}
