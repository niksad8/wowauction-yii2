<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "guild_log_settings".
 *
 * @property int $id
 * @property int $guild_id
 * @property int $channel_id
 * @property string $guild_name
 * @property int $realm_id
 * @property int $faction_id
 */
class GuildLogSettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'guild_log_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['guild_id', 'channel_id'], 'string','max'=>30],
            [['guild_name','realm_name'], 'string', 'max' => 100],
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
            'guild_name' => 'Guild Name',
        ];
    }
}
