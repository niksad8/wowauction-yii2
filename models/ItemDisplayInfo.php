<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "item_display_info".
 *
 * @property int $id
 * @property string $icon_name
 */
class ItemDisplayInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item_display_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['icon_name'], 'string', 'max' => 70],
            [['id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'icon_name' => 'Icon Name',
        ];
    }
}
