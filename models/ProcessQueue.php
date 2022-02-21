<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "process_queue".
 *
 * @property int $id
 * @property string $datetime_posted
 * @property string $filename
 * @property int $faction_id
 * @property int $realm_id
 * @property string $datetime_completed
 * @property int $completed
 */
class ProcessQueue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'process_queue';
    }
    public function getRealm(){
        return $this->hasOne(Realm::class,["id"=>'realm_id']);
    }
    public function getFaction(){
        return $this->hasOne(Factions::class,["id"=>'faction_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['datetime_posted', 'datetime_completed'], 'safe'],
            [['completed','faction_id', 'realm_id'], 'integer'],
            [['filename'], 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'datetime_posted' => 'Datetime Posted',
            'filename' => 'Filename',
            'faction_id' => 'Faction ID',
            'realm_id' => 'Realm ID',
            'datetime_completed' => 'Datetime Completed',
            'completed' => 'Completed',
        ];
    }
}
