<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auction_timer".
 *
 * @property int $id
 * @property string $name
 * @property int $timestamp
 */
class AuctionTimer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auction_timer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'timestamp'], 'integer'],
            [['name'], 'string', 'max' => 10],
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
            'name' => 'Name',
            'timestamp' => 'Timestamp',
        ];
    }
}
