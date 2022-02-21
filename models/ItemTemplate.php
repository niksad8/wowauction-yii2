<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "item_template".
 *
 * @property int $entry
 * @property int $class
 * @property int $subclass
 * @property int $SoundOverrideSubclass
 * @property string $name
 * @property int $displayid
 * @property int $Quality
 * @property int $Flags
 * @property int $FlagsExtra
 * @property int $BuyCount
 * @property int $BuyPrice
 * @property int $SellPrice
 * @property int $InventoryType
 * @property int $AllowableClass
 * @property int $AllowableRace
 * @property int $ItemLevel
 * @property int $RequiredLevel
 * @property int $RequiredSkill
 * @property int $RequiredSkillRank
 * @property int $requiredspell
 * @property int $requiredhonorrank
 * @property int $RequiredCityRank
 * @property int $RequiredReputationFaction
 * @property int $RequiredReputationRank
 * @property int $maxcount
 * @property int $stackable
 * @property int $ContainerSlots
 * @property int $StatsCount
 * @property int $stat_type1
 * @property int $stat_value1
 * @property int $stat_type2
 * @property int $stat_value2
 * @property int $stat_type3
 * @property int $stat_value3
 * @property int $stat_type4
 * @property int $stat_value4
 * @property int $stat_type5
 * @property int $stat_value5
 * @property int $stat_type6
 * @property int $stat_value6
 * @property int $stat_type7
 * @property int $stat_value7
 * @property int $stat_type8
 * @property int $stat_value8
 * @property int $stat_type9
 * @property int $stat_value9
 * @property int $stat_type10
 * @property int $stat_value10
 * @property int $ScalingStatDistribution
 * @property int $ScalingStatValue
 * @property double $dmg_min1
 * @property double $dmg_max1
 * @property int $dmg_type1
 * @property double $dmg_min2
 * @property double $dmg_max2
 * @property int $dmg_type2
 * @property int $armor
 * @property int $holy_res
 * @property int $fire_res
 * @property int $nature_res
 * @property int $frost_res
 * @property int $shadow_res
 * @property int $arcane_res
 * @property int $delay
 * @property int $ammo_type
 * @property double $RangedModRange
 * @property int $spellid_1
 * @property int $spelltrigger_1
 * @property int $spellcharges_1
 * @property double $spellppmRate_1
 * @property int $spellcooldown_1
 * @property int $spellcategory_1
 * @property int $spellcategorycooldown_1
 * @property int $spellid_2
 * @property int $spelltrigger_2
 * @property int $spellcharges_2
 * @property double $spellppmRate_2
 * @property int $spellcooldown_2
 * @property int $spellcategory_2
 * @property int $spellcategorycooldown_2
 * @property int $spellid_3
 * @property int $spelltrigger_3
 * @property int $spellcharges_3
 * @property double $spellppmRate_3
 * @property int $spellcooldown_3
 * @property int $spellcategory_3
 * @property int $spellcategorycooldown_3
 * @property int $spellid_4
 * @property int $spelltrigger_4
 * @property int $spellcharges_4
 * @property double $spellppmRate_4
 * @property int $spellcooldown_4
 * @property int $spellcategory_4
 * @property int $spellcategorycooldown_4
 * @property int $spellid_5
 * @property int $spelltrigger_5
 * @property int $spellcharges_5
 * @property double $spellppmRate_5
 * @property int $spellcooldown_5
 * @property int $spellcategory_5
 * @property int $spellcategorycooldown_5
 * @property int $bonding
 * @property string $description
 * @property int $PageText
 * @property int $LanguageID
 * @property int $PageMaterial
 * @property int $startquest
 * @property int $lockid
 * @property int $Material
 * @property int $sheath
 * @property int $RandomProperty
 * @property int $RandomSuffix
 * @property int $block
 * @property int $itemset
 * @property int $MaxDurability
 * @property int $area
 * @property int $Map
 * @property int $BagFamily
 * @property int $TotemCategory
 * @property int $socketColor_1
 * @property int $socketContent_1
 * @property int $socketColor_2
 * @property int $socketContent_2
 * @property int $socketColor_3
 * @property int $socketContent_3
 * @property int $socketBonus
 * @property int $GemProperties
 * @property int $RequiredDisenchantSkill
 * @property double $ArmorDamageModifier
 * @property int $duration
 * @property int $ItemLimitCategory
 * @property int $HolidayId
 * @property string $ScriptName
 * @property int $DisenchantID
 * @property int $FoodType
 * @property int $minMoneyLoot
 * @property int $maxMoneyLoot
 * @property int $flagsCustom
 * @property int $VerifiedBuild
 * @property ItemDisplayInfo $icon
 */
class ItemTemplate extends \yii\db\ActiveRecord
{
    private $_price_row = null;
    /**
     * @inheritdoc
     */
    static public $item_quality_names = ['Poor','Common','Uncommon','Rare','Epic','Legendary','Artifact','Heirloom'];
    private $item_colors = ['#9d9d9d','#000000','#1eff00','#0070dd','#a335ee','#ff8000','#e6cc80','#e6cc80'];
    public function quality_name(){
        $q = $this->Quality;
        return $this->item_quality_names[$q];
    }

    public function getColor(){
        $q = $this->Quality;
        return $this->item_colors[$q];
    }
    public function getItemprice($realm=0,$faction=0){
        if($this->_price_row != null)
            return $this->_price_row;
        if($realm == 0)
            $realm = \Yii::$app->session->get('realm');
        if($faction == 0)
            $faction = \Yii::$app->session->get('faction');
        $qry = 'select * from item_prices where itemid='.$this->entry;
        $qry2 = "SELECT max(datetime) from item_prices where itemid=".$this->entry;
        if($realm != "") {
            $qry .= ' and realm_id=' . $realm;
            $qry2 .= ' and realm_id=' . $realm;
        }
        if($faction != '') {
            $qry .= ' and faction_id=' . $faction;
            $qry2 .= ' and faction_id=' . $faction;
        }
        $fqry = $qry." and datetime=($qry2)";
        $ip = ItemPrices::findBySql($fqry)->one();
        $this->_price_row = $ip;
        return $this->_price_row;
    }
    public static function tableName()
    {
        return 'item_template';
    }
    public function getItemclass(){
        return $this->hasOne(ItemClass::class,['id'=>'class']);
    }
    public function getItemsubclass(){
        return $this->hasOne(ItemSubClass::class,['id'=>'subclass']);
    }
    public function getBuild(){
        return ItemBuild::find()->where(['result_itemid'=>$this->entry])->all();
    }
    public function getUsedin(){
        return ItemBuild::find()->where(['required_itemid'=>$this->entry])->all();
    }
    public function getIcon(){
        return $this->hasOne(ItemDisplayInfo::class,['id'=>'displayid']);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entry'], 'required'],
            [['entry', 'displayid', 'Flags', 'FlagsExtra', 'BuyPrice', 'SellPrice', 'AllowableClass', 'AllowableRace', 'ItemLevel', 'RequiredSkill', 'RequiredSkillRank', 'requiredspell', 'requiredhonorrank', 'RequiredCityRank', 'RequiredReputationFaction', 'RequiredReputationRank', 'maxcount', 'stackable', 'stat_value1', 'stat_value2', 'stat_value3', 'stat_value4', 'stat_value5', 'stat_value6', 'stat_value7', 'stat_value8', 'stat_value9', 'stat_value10', 'ScalingStatDistribution', 'ScalingStatValue', 'armor', 'delay', 'spellid_1', 'spellcharges_1', 'spellcooldown_1', 'spellcategory_1', 'spellcategorycooldown_1', 'spellid_2', 'spellcharges_2', 'spellcooldown_2', 'spellcategory_2', 'spellcategorycooldown_2', 'spellid_3', 'spellcharges_3', 'spellcooldown_3', 'spellcategory_3', 'spellcategorycooldown_3', 'spellid_4', 'spellcharges_4', 'spellcooldown_4', 'spellcategory_4', 'spellcategorycooldown_4', 'spellid_5', 'spellcharges_5', 'spellcooldown_5', 'spellcategory_5', 'spellcategorycooldown_5', 'PageText', 'startquest', 'lockid', 'RandomProperty', 'RandomSuffix', 'block', 'itemset', 'MaxDurability', 'area', 'Map', 'BagFamily', 'TotemCategory', 'socketContent_1', 'socketContent_2', 'socketContent_3', 'socketBonus', 'GemProperties', 'RequiredDisenchantSkill', 'duration', 'ItemLimitCategory', 'HolidayId', 'DisenchantID', 'minMoneyLoot', 'maxMoneyLoot', 'flagsCustom', 'VerifiedBuild'], 'integer'],
            [['dmg_min1', 'dmg_max1', 'dmg_min2', 'dmg_max2', 'RangedModRange', 'spellppmRate_1', 'spellppmRate_2', 'spellppmRate_3', 'spellppmRate_4', 'spellppmRate_5', 'ArmorDamageModifier'], 'number'],
            [['class', 'subclass', 'SoundOverrideSubclass', 'Quality', 'BuyCount', 'InventoryType', 'RequiredLevel', 'ContainerSlots', 'StatsCount', 'stat_type1', 'stat_type2', 'stat_type3', 'stat_type4', 'stat_type5', 'stat_type6', 'stat_type7', 'stat_type8', 'stat_type9', 'stat_type10', 'dmg_type1', 'dmg_type2', 'holy_res', 'fire_res', 'nature_res', 'frost_res', 'shadow_res', 'arcane_res', 'ammo_type', 'spelltrigger_1', 'spelltrigger_2', 'spelltrigger_3', 'spelltrigger_4', 'spelltrigger_5', 'bonding', 'LanguageID', 'PageMaterial', 'sheath', 'FoodType'], 'string', 'max' => 3],
            [['name', 'description'], 'string', 'max' => 255],
            [['Material', 'socketColor_1', 'socketColor_2', 'socketColor_3'], 'string', 'max' => 4],
            [['ScriptName'], 'string', 'max' => 64],
            [['entry'], 'unique'],
        ];
    }
    public function getBasicInformation(){
        $out = [];
        $out['id'] = $this->entry;
        $out['name'] = $this->name;
        $out['color'] = $this->getColor();
        $out['icon_name'] = "/images/ICONS/".$this->icon->icon_name.".PNG";
        return $out;
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'entry' => 'Entry',
            'class' => 'Class',
            'subclass' => 'Subclass',
            'SoundOverrideSubclass' => 'Sound Override Subclass',
            'name' => 'Name',
            'displayid' => 'Displayid',
            'Quality' => 'Quality',
            'Flags' => 'Flags',
            'FlagsExtra' => 'Flags Extra',
            'BuyCount' => 'Buy Count',
            'BuyPrice' => 'Buy Price',
            'SellPrice' => 'Sell Price',
            'InventoryType' => 'Inventory Type',
            'AllowableClass' => 'Allowable Class',
            'AllowableRace' => 'Allowable Race',
            'ItemLevel' => 'Item Level',
            'RequiredLevel' => 'Required Level',
            'RequiredSkill' => 'Required Skill',
            'RequiredSkillRank' => 'Required Skill Rank',
            'requiredspell' => 'Requiredspell',
            'requiredhonorrank' => 'Requiredhonorrank',
            'RequiredCityRank' => 'Required City Rank',
            'RequiredReputationFaction' => 'Required Reputation Faction',
            'RequiredReputationRank' => 'Required Reputation Rank',
            'maxcount' => 'Maxcount',
            'stackable' => 'Stackable',
            'ContainerSlots' => 'Container Slots',
            'StatsCount' => 'Stats Count',
            'stat_type1' => 'Stat Type1',
            'stat_value1' => 'Stat Value1',
            'stat_type2' => 'Stat Type2',
            'stat_value2' => 'Stat Value2',
            'stat_type3' => 'Stat Type3',
            'stat_value3' => 'Stat Value3',
            'stat_type4' => 'Stat Type4',
            'stat_value4' => 'Stat Value4',
            'stat_type5' => 'Stat Type5',
            'stat_value5' => 'Stat Value5',
            'stat_type6' => 'Stat Type6',
            'stat_value6' => 'Stat Value6',
            'stat_type7' => 'Stat Type7',
            'stat_value7' => 'Stat Value7',
            'stat_type8' => 'Stat Type8',
            'stat_value8' => 'Stat Value8',
            'stat_type9' => 'Stat Type9',
            'stat_value9' => 'Stat Value9',
            'stat_type10' => 'Stat Type10',
            'stat_value10' => 'Stat Value10',
            'ScalingStatDistribution' => 'Scaling Stat Distribution',
            'ScalingStatValue' => 'Scaling Stat Value',
            'dmg_min1' => 'Dmg Min1',
            'dmg_max1' => 'Dmg Max1',
            'dmg_type1' => 'Dmg Type1',
            'dmg_min2' => 'Dmg Min2',
            'dmg_max2' => 'Dmg Max2',
            'dmg_type2' => 'Dmg Type2',
            'armor' => 'Armor',
            'holy_res' => 'Holy Res',
            'fire_res' => 'Fire Res',
            'nature_res' => 'Nature Res',
            'frost_res' => 'Frost Res',
            'shadow_res' => 'Shadow Res',
            'arcane_res' => 'Arcane Res',
            'delay' => 'Delay',
            'ammo_type' => 'Ammo Type',
            'RangedModRange' => 'Ranged Mod Range',
            'spellid_1' => 'Spellid 1',
            'spelltrigger_1' => 'Spelltrigger 1',
            'spellcharges_1' => 'Spellcharges 1',
            'spellppmRate_1' => 'Spellppm Rate 1',
            'spellcooldown_1' => 'Spellcooldown 1',
            'spellcategory_1' => 'Spellcategory 1',
            'spellcategorycooldown_1' => 'Spellcategorycooldown 1',
            'spellid_2' => 'Spellid 2',
            'spelltrigger_2' => 'Spelltrigger 2',
            'spellcharges_2' => 'Spellcharges 2',
            'spellppmRate_2' => 'Spellppm Rate 2',
            'spellcooldown_2' => 'Spellcooldown 2',
            'spellcategory_2' => 'Spellcategory 2',
            'spellcategorycooldown_2' => 'Spellcategorycooldown 2',
            'spellid_3' => 'Spellid 3',
            'spelltrigger_3' => 'Spelltrigger 3',
            'spellcharges_3' => 'Spellcharges 3',
            'spellppmRate_3' => 'Spellppm Rate 3',
            'spellcooldown_3' => 'Spellcooldown 3',
            'spellcategory_3' => 'Spellcategory 3',
            'spellcategorycooldown_3' => 'Spellcategorycooldown 3',
            'spellid_4' => 'Spellid 4',
            'spelltrigger_4' => 'Spelltrigger 4',
            'spellcharges_4' => 'Spellcharges 4',
            'spellppmRate_4' => 'Spellppm Rate 4',
            'spellcooldown_4' => 'Spellcooldown 4',
            'spellcategory_4' => 'Spellcategory 4',
            'spellcategorycooldown_4' => 'Spellcategorycooldown 4',
            'spellid_5' => 'Spellid 5',
            'spelltrigger_5' => 'Spelltrigger 5',
            'spellcharges_5' => 'Spellcharges 5',
            'spellppmRate_5' => 'Spellppm Rate 5',
            'spellcooldown_5' => 'Spellcooldown 5',
            'spellcategory_5' => 'Spellcategory 5',
            'spellcategorycooldown_5' => 'Spellcategorycooldown 5',
            'bonding' => 'Bonding',
            'description' => 'Description',
            'PageText' => 'Page Text',
            'LanguageID' => 'Language ID',
            'PageMaterial' => 'Page Material',
            'startquest' => 'Startquest',
            'lockid' => 'Lockid',
            'Material' => 'Material',
            'sheath' => 'Sheath',
            'RandomProperty' => 'Random Property',
            'RandomSuffix' => 'Random Suffix',
            'block' => 'Block',
            'itemset' => 'Itemset',
            'MaxDurability' => 'Max Durability',
            'area' => 'Area',
            'Map' => 'Map',
            'BagFamily' => 'Bag Family',
            'TotemCategory' => 'Totem Category',
            'socketColor_1' => 'Socket Color 1',
            'socketContent_1' => 'Socket Content 1',
            'socketColor_2' => 'Socket Color 2',
            'socketContent_2' => 'Socket Content 2',
            'socketColor_3' => 'Socket Color 3',
            'socketContent_3' => 'Socket Content 3',
            'socketBonus' => 'Socket Bonus',
            'GemProperties' => 'Gem Properties',
            'RequiredDisenchantSkill' => 'Required Disenchant Skill',
            'ArmorDamageModifier' => 'Armor Damage Modifier',
            'duration' => 'Duration',
            'ItemLimitCategory' => 'Item Limit Category',
            'HolidayId' => 'Holiday ID',
            'ScriptName' => 'Script Name',
            'DisenchantID' => 'Disenchant ID',
            'FoodType' => 'Food Type',
            'minMoneyLoot' => 'Min Money Loot',
            'maxMoneyLoot' => 'Max Money Loot',
            'flagsCustom' => 'Flags Custom',
            'VerifiedBuild' => 'Verified Build',
        ];
    }
}
