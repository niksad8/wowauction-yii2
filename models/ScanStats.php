<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "scan_stats".
 *
 * @property int $id
 * @property int $realm_id
 * @property int $faction_id
 * @property string $datetime
 * @property int $total_items
 * @property int $total_listed
 * @property int $cnt_prices_increased
 * @property int $cnt_prices_decreased
 * @property int $total_amt_bid
 * @property double $avg_price_change
 * @property double $total_bid_gold
 * @property int $total_buyout_gold
 * @property Factions $faction
 * @property Realm $realm
 */
class ScanStats extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'scan_stats';
    }
    public function getRealm(){
        return $this->hasOne(Realm::class,['id'=>'realm_id']);
    }

    public function getFaction(){
        return $this->hasOne(Factions::class,['id'=>'faction_id']);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['realm_id', 'faction_id', 'total_items', 'total_listed', 'cnt_prices_increased', 'cnt_prices_decreased', 'total_amt_bid', 'total_buyout_gold'], 'integer'],
            [['datetime'], 'safe'],
            [['avg_price_change', 'total_bid_gold'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'realm_id' => 'Realm ID',
            'faction_id' => 'Faction ID',
            'datetime' => 'Datetime',
            'total_items' => 'Total Items',
            'total_listed' => 'Total Listed',
            'cnt_prices_increased' => 'Cnt Prices Increased',
            'cnt_prices_decreased' => 'Cnt Prices Decreased',
            'total_amt_bid' => 'Total Amt Bid',
            'avg_price_change' => 'Avg Price Change',
            'total_bid_gold' => 'Total Bid Gold',
            'total_buyout_gold' => 'Total Buyout Gold',
        ];
    }
}
