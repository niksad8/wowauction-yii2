<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "spell_master".
 *
 * @property int $id
 * @property string $name
 * @property string $desc
 * @property string $icon
 * @property string $type
 */
class SpellMaster extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'spell_master';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['desc'], 'string', 'max' => 160],
            [['icon'], 'string', 'max' => 250],
            [['type'], 'string', 'max' => 50],
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
            'desc' => 'Desc',
            'icon' => 'Icon',
            'type' => 'Type',
        ];
    }
}
