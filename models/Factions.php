<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "factions".
 *
 * @property int $id
 * @property string $name
 */
class Factions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factions';
    }
    public function emblemCode($width=200,$height=200){
        return "<div class='logo-".strtolower($this->name)."' style='display:inline-block; width:".$width."px; height:".$height."px'></div>";
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['name'], 'string', 'max' => 45],
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
        ];
    }
}
