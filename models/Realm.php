<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "realm".
 *
 * @property int $id
 * @property int $server_id
 * @property int $expansion_id
 * @property string $name
 * @property string $desc
 * @property Servers $server
 * @property int $update_schedule
 * @property Expansion $expansion
 */
class Realm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'realm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['server_id', 'expansion_id','update_schedule'], 'integer'],
            [['name'], 'string', 'max' => 45],
            [['desc'], 'string', 'max' => 145],
            [['data'],'safe']
        ];
    }

    public function getExpansion(){
        return $this->hasOne(Expansion::class,['id'=>'expansion_id']);
    }

    public function getServer(){
        return $this->hasOne(Servers::class,['id'=>'server_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'server_id' => 'Server ID',
            'expansion_id' => 'Expansion ID',
            'name' => 'Name',
            'desc' => 'Desc',
            'data'=>'JSON Data'
        ];
    }
}
