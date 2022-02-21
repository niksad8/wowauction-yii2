<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "email_subscriptions".
 *
 * @property int $id
 * @property int $userid
 * @property int $realm_id
 * @property int $faction_id
 * @property string $subscription_type
 * @property string $email
 */
class EmailSubscriptions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'email_subscriptions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid', 'realm_id', 'faction_id'], 'integer'],
            [['subscription_type', 'email'], 'string', 'max' => 90],
        ];
    }
    public function realm(){
        return $this->hasOne(Realm::class,['realm_id'=>'id']);
    }
    public function faction(){
        return $this->hasOne(Factions::class,['faction_id'=>'id']);
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => 'Userid',
            'realm_id' => 'Realm ID',
            'faction_id' => 'Faction ID',
            'subscription_type' => 'Subscription Type',
            'email' => 'Email',
        ];
    }
}
