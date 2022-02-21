<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "email_templates".
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string $body
 */
class EmailTemplates extends \yii\db\ActiveRecord
{
    /*
     * take a template called $name, with the data provided by $data
     * the indexes in $data should not be wrapped by % %
     */
    static function fillUp($name,$data){
        $template = EmailTemplates::findOne(['name'=>$name]);
        if($template == NULL){
            return null;
        }
        if($data == NULL)
            $data = [];
        $data['domain'] = Yii::$app->urlManager->getBaseUrl();
        $data['timestamp'] = date('Y-m-d H:i:s');
        return $template->fillUpData($data);
    }
    public function fillInString($str,$data){
        foreach($data as $idx=>$val){
            $str = str_replace("%".$idx."%",$val,$str);
        }
        return $str;
    }
    /*
     * fills up the data in the template
     */
    public function fillUpData($data){
        $title = $this->fillInString($this->title,$data);
        $body = $this->fillInString($this->body,$data);
        return ['title'=>$title,'body'=>$body];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'email_templates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['body'], 'string'],
            [['name'], 'string', 'max' => 90],
            [['title'], 'string', 'max' => 150],
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
            'title' => 'Title',
            'body' => 'Body',
        ];
    }
}
