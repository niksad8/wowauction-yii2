<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_settings".
 *
 * @property int $user_id
 * @property string $option_name
 * @property string $value
 */
class UserSettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function set($name,$value,$userid=null){
        if($userid == null)
            $userid = Yii::$app->user->getId();

        $option = UserSettings::findOne(['user_id'=>$userid,'option_name'=>$name]);
        if($option == NULL){
            $option = new UserSettings();
        }
        if($value == "" || $value == null){
            if(!$option->isNewRecord){
                $option->delete();
            }
            return null;
        }
        else {
            $option->user_id = $userid;
            $option->option_name = $name;
            $option->value = $value;
            $option->save();
            return $option;
        }
    }
    public static function getOption($name,$userid = null){
        if($userid == NULL)
            $userid = Yii::$app->user->getId();
        $option = UserSettings::findOne(['user_id'=>$userid,'option_name'=>$name]);
        if($option != NULL)
            return $option;
        else
            return null;
    }

    public static function get($name,$userid = null){
        if($userid == NULL)
            $userid = Yii::$app->user->getId();
        $option = UserSettings::findOne(['user_id'=>$userid,'option_name'=>$name]);
        if($option != NULL)
            return $option->value;
        else
            return null;
    }
    public static function tableName()
    {
        return 'user_settings';
    }
    public function getUser(){
        return $this->hasOne(Users::class,['id'=>'user_id']);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['value'], 'string'],
            [['option_name'], 'string', 'max' => 100],
            [['user_id', 'option_name'], 'unique', 'targetAttribute' => ['user_id', 'option_name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'option_name' => 'Option Name',
            'value' => 'Value',
        ];
    }
}
