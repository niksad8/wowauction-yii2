<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ahscan_attempts".
 *
 * @property int $realm_id
 * @property int $faction_id
 * @property string $created_at
 * @property string $completed_at
 * @property string $pinged_at
 * @property int $status
 */
class AhscanAttempts extends \yii\db\ActiveRecord
{
    const STATUS_PENDING = 0;
    const STATUS_STARTED = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_FAILED = 3;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ahscan_attempts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['realm_id', 'faction_id'], 'required'],
            [['realm_id', 'faction_id', 'status'], 'integer'],
            [['created_at', 'completed_at', 'pinged_at'], 'safe'],
            [['id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'realm_id' => 'Realm ID',
            'faction_id' => 'Faction ID',
            'created_at' => 'Created At',
            'completed_at' => 'Completed At',
            'pinged_at' => 'Pinged At',
            'status' => 'Status',
        ];
    }
}
