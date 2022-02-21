<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "expansion".
 *
 * @property int $id
 * @property string $name
 * @property string $version_no
 * @property string $short_name
 */
class Expansion extends \yii\db\ActiveRecord
{
    public static $EXPANSION_WOTLK = '3.3.5';
    public static $EXPANSION_TBC = '2.4.3';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'expansion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'version_no'], 'string', 'max' => 45],
            [['short_name'], 'string', 'max' => 15],
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
            'version_no' => 'Version No',
            'short_name' => 'Short Name',
        ];
    }
}
