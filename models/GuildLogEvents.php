<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "guild_log_events".
 *
 * @property int $id
 * @property int $guild_id
 * @property int $channel_id
 * @property string $char_name
 * @property string $datetime
 * @property int $sent
 * @property string $operation
 * @property string $extra_data
 * @property GuildLogCharacters $char
 */
class GuildLogEvents extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'guild_log_events';
    }
    public function getChar(){
        return $this->hasOne(GuildLogCharacters::class,['guild_id'=>'guild_id','char_name'=>'char_name']);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sent'], 'integer'],
            [['guild_id','channel_id'],'string','max'=>100],
            [['datetime','extra_data'], 'safe'],
            [['char_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'guild_id' => 'Guild ID',
            'channel_id' => 'Channel ID',
            'char_name' => 'Char Name',
            'datetime' => 'Datetime',
            'sent' => 'Sent',
        ];
    }
}
