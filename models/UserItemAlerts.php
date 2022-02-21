<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_item_alerts".
 *
 * @property int $id
 * @property string $datetime_set
 * @property int $user_id
 * @property int $item_id
 * @property int $realm_id
 * @property int $faction_id
 * @property string $op
 * @property int $value1
 * @property int $value2
 * @property int $alert_sent
 * @property string $sent_datetime
 * @property string $send_to
 * @property string $price_type
 * @property string $pricetypetext
 */
class UserItemAlerts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_item_alerts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['datetime_set', 'sent_datetime'], 'safe'],
            [['alert_sent','user_id', 'item_id', 'realm_id', 'faction_id', 'value1', 'value2'], 'integer'],
            [['op'], 'string', 'max' => 10],
            [['send_to'], 'string', 'max' => 30],
        ];
    }
    public function getOptext(){
        $out = "";
        $op = $this->op;
        if($op == ">="){
            $out = "greater than or equal";
        }
        else if($op == "<="){
            $out = "less than or equal";
        }
        else if($op == "><"){
            $out = "is between";
        }
        return $out;
    }

    public function cointotext($amt){
        $c = $amt%100;
        $c2 = floor($amt/100);
        $s = $c2%100;
        $c2 = floor($c2/100);
        $g = $c2;
        $out = "";
        if($g > 0)
            $out .= $g.":small_orange_diamond: ";
        if($s > 0)
            $out .= $s.":small_blue_diamond: ";
        if($c > 0 || ($g==0 && $s ==0))
            $out .= $c."C";
        return $out;
    }

    public function cointogsc($amt){
        $c = $amt%100;
        $c2 = floor($amt/100);
        $s = $c2%100;
        $c2 = floor($c2/100);
        $g = $c2;

        $out = "";
        if($g > 0)
            $out .= $g."G ";
        if($s > 0)
            $out .= $s."S ";
        if($c > 0 || ($g==0 && $s ==0))
            $out .= $c."C";
        return $out;
    }

    public function generateDesktopNotifcation(){
        $alert = $this;
        $not = new Notifications();
        $not->userid = $alert->user_id;
        $not->icon = "/images/ICONS/".$alert->item->icon->icon_name.".PNG";
        $not->title = "Alert for ".$alert->item->name;
        $price = $alert->item->getItemPrice($alert->realm_id,$alert->faction_id);
        $attr = $alert->pricetypecolumn;
        $not->message = "Price of Item on ".$alert->realm->name."(".$alert->faction->name.") is ".$this->cointogsc($price->$attr)." which is ".$alert->optext." ".$this->cointogsc($alert->value1). " " . ($alert->op == "><"?' and '.$this->cointogsc($alert->value2):"");
        $not->sent = 0;
        $not->datetime_created = date('Y-m-d H:i:s');
        $not->notification_type = 'desktop';
        $not->item_id = $alert->item_id;
        $not->url = Yii::$app->urlManager->createAbsoluteUrl(['item/index','id'=>$not->item_id,'faction'=>$alert->faction_id,'relam'=>$alert->realm_id]);
        $not->alert_id = $alert->id;
        $not->save();
    }
    public function generateDiscordNotifcation(){
        $alert = $this;
        $not = new Notifications();
        $not->userid = $alert->user_id;
        $not->icon = "/images/ICONS/".$alert->item->icon->icon_name.".PNG";
        $not->title = "Hello **".$alert->user->username."**\nYour alert for **".$alert->item->name."** on **".$alert->realm->name."(".$alert->faction->name.")** realm has been triggered\n";
        $price = $alert->item->getItemPrice($alert->realm_id,$alert->faction_id);
        $attr = $alert->pricetypecolumn;
        $not->message = $alert->pricetypetext." of Item is **".$this->cointotext($price->$attr)."** which is **".$alert->optext."** **".$this->cointogsc($alert->value1). "** " . ($alert->op == "><"?' and **'.$this->cointogsc($alert->value2)."**":"");
        $not->sent = 0;
        $not->datetime_created = date('Y-m-d H:i:s');
        $not->notification_type = 'discord';
        $not->item_id = $alert->item_id;
        $not->url = Yii::$app->urlManager->createAbsoluteUrl(['item/index','id'=>$not->item_id,'faction'=>$alert->faction_id,'relam'=>$alert->realm_id]);
        $not->alert_id = $alert->id;
        $not->save();
    }

    public function generateEmailNotification(){
        $alert = $this;
        $not = new Notifications();
        $not->userid = $alert->user_id;
        $not->icon = Yii::$app->urlManager->getBaseUrl()."/images/ICONS/".$alert->item->icon->icon_name.".PNG";
        $price = $alert->item->getItemPrice($alert->realm_id,$alert->faction_id);
        $attr = $alert->pricetypecolumn;
        $data = [
            'username'=>$alert->user->username,
            'item_name'=>$alert->item->name,
            'realm_name'=>$alert->realm->name,
            'faction_name'=>$alert->faction->name,
            'item_name_color'=>'<span style=\'color:'.$alert->item->getColor().'\'>'.$alert->item->name."</span>",
            'item_icon'=>$not->icon,
            'price'=>Yii::$app->wowutil->printCurrency($price->$attr),
            'compared_to'=>Yii::$app->wowutil->printCurrency($alert->value1),
            'compared_to2'=>(($alert->op == '><')?" and <br> ".Yii::$app->wowutil->printCurrency($alert->value2):""),
            'compare_type'=>$alert->optext,
            'price_type'=>$alert->pricetypetext,
            'item_link'=>Yii::$app->urlManager->createAbsoluteUrl(['item/index','id'=>$not->item_id,'faction'=>$alert->faction_id,'relam'=>$alert->realm_id]),
        ];
        $ret = EmailTemplates::fillUp('ALERT_TEMPLATE',$data);
        if($ret == NULL)
            return null;
        $not->title = $ret['title'];
        $not->message = $ret['body'];
        $not->sent = 0;
        $not->datetime_created = date('Y-m-d H:i:s');
        $not->notification_type = 'email';
        $not->item_id = $alert->item_id;
        $not->url = Yii::$app->urlManager->createAbsoluteUrl(['item/index','id'=>$not->item_id,'faction'=>$alert->faction_id,'relam'=>$alert->realm_id]);
        $not->alert_id = $alert->id;
        $not->save();
    }
    public function generateAppropriateNotifications(){
        if($this->user->getSetting('desktop_notification') == 1)
            $this->generateDesktopNotifcation();
        if($this->user->getSetting('discord_notification') == 1)
            $this->generateDiscordNotifcation();
        if($this->user->getSetting('email_notification') == 1)
            $this->generateEmailNotification();
    }
    public function isTriggered(){
        $price = $this->item->getItemPrice($this->realm_id,$this->faction_id);
        $attr = $this->pricetypecolumn;
        if($this->op == ">=" && $price->$attr >= $this->value1)
            return true;
        if($this->op == "<=" && $price->$attr <= $this->value1)
            return true;
        if($this->op == "><" && $price->$attr >= $this->value1 && $price->$attr <= $this->value2)
            return true;
        return false;
    }
    public function getPricetypecolumn(){
        $ptype = $this->price_type;
        if($ptype == "median_buyout")
            return "buyout_median";
        else if($ptype == "mean_buyout")
            return "buyout_mean";
        else if($ptype == "median_bid")
            return "bid_median";
        else if($ptype == "mean_bid")
            return "bid_mean";
        else if($ptype == "min_buyout")
            return "buyout_min";
        else if($ptype == "min_bid")
            return "bid_min";
        else
            return "";
    }
    public function getPricetypetext(){
        $ptype = $this->price_type;
        if($ptype == "median_buyout")
            return "Median Buyout ";
        else if($ptype == "mean_buyout")
            return "Average Buyout ";
        else if($ptype == "median_bid")
            return "Median Bid ";
        else if($ptype == "mean_bid")
            return "Average Bid ";
        else if($ptype == "min_buyout")
            return "Min Buyout ";
        else if($ptype == "min_bid")
            return "Min Bid";
        else
            return "";
    }

    public function getJson(){
        $arr = $this->getAttributes();
        $arr['op'] = $this->optext;
        $arr['price_type'] = $this->price_type;
        $arr['price_type_text'] = $this->pricetypetext;
        $arr['item_name'] = Yii::$app->wowutil->printItemname($this->item_id);
        $arr['realm'] = $this->realm->name;
        $arr['faction'] = $this->faction->name;
        $arr['v1_text'] = \Yii::$app->wowutil->printCurrency($this->value1);
        $arr['v2_text'] = \Yii::$app->wowutil->printCurrency($this->value2);
        return $arr;
    }
    public function getUser(){
        return $this->hasOne(Users::class,['id'=>'user_id']);
    }
    public function getFaction(){
        return $this->hasOne(Factions::class,['id'=>'faction_id']);
    }

    public function getRealm(){
        return $this->hasOne(Realm::class,['id'=>'realm_id']);
    }
    public function getItem(){
        return $this->hasOne(ItemTemplate::class,['entry'=>'item_id']);
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'datetime_set' => 'Datetime Set',
            'user_id' => 'User ID',
            'item_id' => 'Item ID',
            'realm_id' => 'Realm ID',
            'faction_id' => 'Faction ID',
            'op' => 'Op',
            'value1' => 'Value1',
            'value2' => 'Value2',
            'alert_sent' => 'Alert Sent',
            'sent_datetime' => 'Sent Datetime',
            'send_to' => 'Send To',
        ];
    }
}
