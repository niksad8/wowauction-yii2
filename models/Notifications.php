<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notifications".
 *
 * @property int $id
 * @property int $userid
 * @property string $icon
 * @property string $title
 * @property string $message
 * @property int $sent
 * @property string $datetime_created
 * @property string $datetime_sent
 * @property string $notification_type
 * @property User $user
 * @property ItemTemplate $item
 * @property UserItemAlerts $alert
 */
class Notifications extends \yii\db\ActiveRecord
{
    public function getUser(){
        return $this->hasOne(Users::class,['id'=>'userid']);
    }
    public function getItem(){
        return $this->hasOne(ItemTemplate::class,['entry'=>'item_id']);
    }
    public function getAlert(){
        return $this->hasOne(UserItemAlerts::class,['id'=>'alert_id']);
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notifications';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid','item_id'], 'required'],
            [['userid','item_id'], 'integer'],
            [['message'], 'string'],
            [['datetime_created', 'datetime_sent'], 'safe'],
            [['icon'], 'string', 'max' => 100],
            [['title'], 'string', 'max' => 200],
            [['sent'], 'integer'],
            [['notification_type'], 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => 'Userid',
            'icon' => 'Icon',
            'title' => 'Title',
            'message' => 'Message',
            'sent' => 'Sent',
            'datetime_created' => 'Datetime Created',
            'datetime_sent' => 'Datetime Sent',
            'notification_type' => 'Notification Type',
        ];
    }
}
