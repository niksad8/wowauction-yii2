<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "servers".
 *
 * @property int $id
 * @property string $name
 * @property string $desc
 * @property string $url
 * @property string $connect_url
 */
class Servers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'servers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['desc'], 'string'],
            [['name', 'url'], 'string', 'max' => 145],
            [['connect_url'], 'string', 'max' => 45],
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
            'url' => 'Url',
            'connect_url' => 'Connect Url',
        ];
    }
}
