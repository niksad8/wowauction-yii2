<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "discord_bot_settings".
 *
 * @property string $serverid
 * @property string $setting_name
 * @property string $value
 */
class DiscordBotSettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'discord_bot_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['setting_name','serverid'], 'required'],
            [['value'], 'string'],
            [['serverid'], 'string', 'max' => 50],
            [['setting_name'], 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'serverid' => 'Serverid',
            'setting_name' => 'Setting Name',
            'value' => 'Value',
        ];
    }
}
