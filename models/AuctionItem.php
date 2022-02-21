<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auction_item".
 *
 * @property int $id
 * @property string $item_list
 * @property int $ilvl
 * @property int $cat1
 * @property int $cat2
 * @property int $slot_id
 * @property double $current_bid
 * @property int $timeleft_id
 * @property int $timescanned
 * @property string $name
 * @property string $icon
 * @property int $stack
 * @property int $quality
 * @property int $level_required
 * @property int $min_bid
 * @property int $bid_up_amount
 * @property int $previous_bid_amount
 * @property string $user
 * @property int $auction_id
 * @property int $itemid
 * @property int $realm_id
 * @property int $buyout
 * @property int $faction
 */
class AuctionItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auction_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ilvl', 'cat1', 'cat2', 'slot_id', 'timeleft_id','buyout', 'timescanned', 'stack', 'quality', 'level_required', 'min_bid', 'bid_up_amount', 'previous_bid_amount', 'auction_id', 'itemid', 'realm_id','faction'], 'integer'],
            [['current_bid'], 'number'],
            [['item_list','name'], 'string', 'max' => 245],
            [['icon'], 'string', 'max' => 145],
            [['user'], 'string', 'max' => 45],
        ];
    }
    public function getItem(){
        return $this->hasOne(ItemTemplate::class,['entry'=>'itemid']);
    }
    public function getFactionrow(){
        return $this->hasOne(Factions::class,['id'=>'faction']);
    }

    public function getRealm(){
        return $this->hasOne(Realm::class,['id'=>'realm_id']);
    }
    public function getCat2row(){
        return $this->hasOne(AhSubCat::class,['id'=>'cat2']);
    }
    public function getSlot(){
        return $this->hasOne(SlotMaster::class,['id'=>'slot_id']);
    }
    public function getTimeleft(){
        return $this->hasOne(AuctionTimer::class,['id'=>'timeleft_id']);
    }

    public function getCat1row(){
        return $this->hasOne(AhMainCat::class,['id'=>'cat1']);
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_list' => 'Item List',
            'ilvl' => 'Ilvl',
            'cat1' => 'Cat1',
            'cat2' => 'Cat2',
            'slot_id' => 'Slot ID',
            'current_bid' => 'Current Bid',
            'timeleft_id' => 'Timeleft ID',
            'timescanned' => 'Timescanned',
            'name'=>'Item Name',
            'buyout'=>'Buyout Price',
            'icon' => 'Icon',
            'stack' => 'Stack',
            'quality' => 'Quality',
            'level_required' => 'Level Required',
            'min_bid' => 'Min Bid',
            'bid_up_amount' => 'Bid Up Amount',
            'previous_bid_amount' => 'Previous Bid Amount',
            'user' => 'User',
            'auction_id' => 'Auction ID',
            'itemid' => 'Itemid',
            'realm_id' => 'Realm ID',
            'faction'=>'Faction'
        ];
    }
}
