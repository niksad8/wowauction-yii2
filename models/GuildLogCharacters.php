<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "guild_log_characters".
 *
 * @property int $id
 * @property int $guild_id
 * @property string $char_name
 * @property string $joined_at
 * @property string $left_at
 * @property string $rank
 * @property int $char_level
 * @property string $race
 * @property int $is_online
 * @property string $last_seen
 * @property string $class_name
 */
class GuildLogCharacters extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'guild_log_characters';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['char_level','is_online'], 'integer'],
            [['joined_at', 'left_at'], 'safe'],
            [['char_name'], 'string', 'max' => 90],
            [['rank', 'class_name'], 'string', 'max' => 50],
            [['guild_id','race','last_seen'], 'string', 'max' => 40],
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
            'char_name' => 'Char Name',
            'joined_at' => 'Joined At',
            'left_at' => 'Left At',
            'rank' => 'Rank',
            'char_level' => 'Char Level',
            'race' => 'Race',
            'class_name' => 'Class Name',
        ];
    }
}
