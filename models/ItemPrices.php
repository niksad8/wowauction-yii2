<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "item_prices".
 *
 * @property int $itemid
 * @property string $datetime
 * @property double $bid_mean
 * @property double $bid_median
 * @property double $cost_price
 * @property int $quantity
 * @property double $bid_median_last_compare
 * @property double $bid_mean_last_compare
 * @property double $buyout_mean
 * @property double $buyout_median
 * @property int $realm_id
 * @property int $faction_id
 * @property double $buyout_median_last_compare
 * @property double $buyout_mean_last_compare
 */
class ItemPrices extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item_prices';
    }
    public function getItem(){
        return $this->hasOne(ItemTemplate::class,['entry'=>'itemid']);
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
            [['itemid', 'datetime', 'realm_id', 'faction_id'], 'required'],
            [[ 'quantity', 'realm_id', 'faction_id'], 'integer'],
            [['itemid','datetime'], 'safe'],
            [['bid_mean', 'bid_median', 'cost_price', 'bid_median_last_compare', 'bid_mean_last_compare', 'buyout_mean', 'buyout_median', 'buyout_median_last_compare', 'buyout_mean_last_compare'], 'number'],
            [['itemid', 'datetime', 'realm_id', 'faction_id'], 'unique', 'targetAttribute' => ['itemid', 'datetime', 'realm_id', 'faction_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'itemid' => 'Itemid',
            'datetime' => 'Datetime',
            'bid_mean' => 'Bid Mean',
            'bid_median' => 'Bid Median',
            'cost_price' => 'Cost Price',
            'quantity' => 'Quantity',
            'bid_median_last_compare' => 'Bid Median Last Compare',
            'bid_mean_last_compare' => 'Bid Mean Last Compare',
            'buyout_mean' => 'Buyout Mean',
            'buyout_median' => 'Buyout Median',
            'realm_id' => 'Realm ID',
            'faction_id' => 'Faction ID',
            'buyout_median_last_compare' => 'Buyout Median Last Compare',
            'buyout_mean_last_compare' => 'Buyout Mean Last Compare',
        ];
    }
}
