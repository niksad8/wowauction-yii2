<?php
/**
 * Created by PhpStorm.
 * User: nikhil
 * Date: 01-Mar-19
 * Time: 02:08
 */

namespace app\controllers;


use app\models\DiscordBotSettings;
use app\models\Factions;
use app\models\ItemPrices;
use app\models\ItemTemplate;
use app\models\Notifications;
use app\models\ProcessQueue;
use app\models\Realm;
use yii\base\Controller;
use yii\helpers\Json;
use yii\web\Response;

class PrivateApiController extends Controller
{

    public function actionDiscordnotifications(){
        $nots = Notifications::find()->where(['sent'=>0,'notification_type'=>'discord'])->all();
        $final = ['status'=>1,'response'=>[]];
        for($i=0;$i < count($nots); $i++){
            $not = $nots[$i];
            $output = [];
            $output['user'] = $not->user->getSetting('discord_name');
            $output['body'] = $not->title.$not->message."\n ".$not->url;
            $final['response'][] = $output;
            $nots[$i]->sent = 1;
            $nots[$i]->datetime_sent = date('Y-m-d H:i:s');
            $nots[$i]->save();
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $final;
    }

    public function actionTakefile(){
        if(!isset($_GET['faction']))
            die("no faction");

        if(!isset($_GET['id']))
            die("no id");
        $id = $_GET['id'];
        $faction = $_GET['faction'];
        if($faction == "h")
            $faction = 1;
        else
            $faction = 2;

        $realm = Realm::findOne(['id'=>$id]);
        if($realm == NULL){
            die("realm not found");
        }

        if(isset($_FILES['file']) && $_FILES['file']['tmp_name'] != ''){

            $fname = $realm->name."_".$faction."_aucdata";
            $fullpath = \Yii::$app->basePath.DIRECTORY_SEPARATOR."auc-data".DIRECTORY_SEPARATOR.$fname;
            if(file_exists($fullpath)){
                unlink($fullpath);
            }
            move_uploaded_file($_FILES['file']['tmp_name'],$fullpath);
            $process = new ProcessQueue();
            $process->completed = 0;
            $process->datetime_posted = date('Y-m-d H:i:s');
            $process->faction_id = $faction;
            $process->filename = $fname;
            $process->realm_id = $realm->id;
            $process->save();
            die("OK");
        }
        else {
            die("ERROR IN FILE UPLOAD");
        }
    }
    public function actionGetrealmdata(){
        $ids = isset($_GET['id'])?$_GET['id']:"";
        $faction = isset($_GET['f'])?$_GET['f']:"";
        $arr = explode(",",trim($ids));

        if(count($arr) > 0 && $ids != "") {
            $realms = Realm::findAll(['id' => $arr]);
        }
        else
            $realms = Realm::find()->all();
        $out = [];
        foreach($realms as $realm){
            if($realm->data != ''){
                $arr = json_decode($realm->data,true);
                if(isset($arr['a']) && ($faction == "" || $faction == 'a'))
                    $out[] = ['id'=>$realm->id,'expansion'=>$realm->expansion->version_no,'name'=>$realm->name,'connection_url'=>$realm->server->connect_url,'faction'=>'a','data'=>$arr['a']];
                if(isset($arr['h']) && ($faction == "" || $faction == 'h'))
                    $out[] = ['id'=>$realm->id,'expansion'=>$realm->expansion->version_no,'name'=>$realm->name,'connection_url'=>$realm->server->connect_url,'faction'=>'h','data'=>$arr['h']];
            }
        }
        return json_encode($out);
    }
    public function actionSetrealm(){
        $gid = \Yii::$app->request->get("gid");

        $realmid = \Yii::$app->request->get("realmid");
        $realm = Realm::findOne(['id'=>$realmid]);
        if($realm == null) {
            $realm = Realm::findOne(['name'=>$realmid]);
        }
        if($realm == NULL){
            return json_encode(['result'=>'error','message'=>'Realm not found!']);
        }
        $setting = DiscordBotSettings::findOne(['serverid'=>$gid,'setting_name'=>'realm_id']);
        if($setting == null){
            $setting = new DiscordBotSettings();
            $setting->serverid = $gid;
            $setting->setting_name = 'realm_id';
        }
        $setting->value = (string)$realm->id;
        if(!$setting->save()){
            print_r($setting->getErrors());
        }
        return json_encode(['result'=>'OK','name'=>$realm->name]);
    }
    public function actionSetfaction(){
        $gid = \Yii::$app->request->get("gid");
        $faction = \Yii::$app->request->get("faction");
        $faction = strtolower($faction);
        $facid = ($faction == 'h'?'1':'2');
        $facrow = Factions::findOne(['id'=>$facid]);
        if($facrow == NULL){
            return json_encode(['result'=>'error','message'=>'Faction Not Found']);
        }
        $setting = DiscordBotSettings::findOne(['serverid'=>$gid,'setting_name'=>'faction']);
        if($setting == null){
            $setting = new DiscordBotSettings();
            $setting->serverid = $gid;
            $setting->setting_name = 'faction';
        }
        $setting->value = $facid;
        if(!$setting->save()){
            print_r($setting->getErrors());
        }
        return json_encode(['result'=>'OK','name'=>$facrow->name]);
    }
    public function actionGetAhPrice(){
        $name = \Yii::$app->request->get("name");
        $gid = \Yii::$app->request->get("gid");
        $settings = DiscordBotSettings::findOne(['serverid'=>$gid,'setting_name'=>'realm_id']);
        if(strlen($name) < 3){
            return json_encode(['result'=>'error','message'=>'Umm the item name is too short please atleast type 3 characters']);
        }
        if($settings == NULL){
            return json_encode(['result'=>'error','message'=>'Realm not set for this guild!']);
        }
        $realm_id = $settings->value;
        $realm= Realm::findOne(['id'=>$realm_id]);
        if($realm == NULL){
            return json_encode(['result'=>'error','message'=>'Opps Realm Not Found!']);
        }
        $settings = null;

        $settings = DiscordBotSettings::findOne(['serverid'=>$gid,'setting_name'=>'faction']);
        if($settings == NULL){
            return json_encode(['result'=>'error','message'=>'Faction Not Set For this Guild']);
        }
        $factionid = $settings->value;
        $faction= Factions::findOne(['id'=>$factionid]);
        if($faction == NULL){
            return json_encode(['result'=>'error','message'=>'Opps Faction Not Found!']);
        }
        $itemids = ItemTemplate::findBySql("SELECT * from item_template where entry in (select itemid from item_prices where realm_id=:realm and faction_id=:faction and itemid=entry) and name like :itemname limit 3",[':realm'=>$realm_id,':itemname'=>$name,':faction'=>$factionid])->all();
        if(count($itemids) == 0) {
            $itemids = ItemTemplate::findBySql("SELECT * from item_template where entry in (select itemid from item_prices where realm_id=:realm and faction_id=:faction and itemid=entry) and name like :itemname limit 3", [':realm' => $realm_id, ':itemname' => '%' . $name . '%', ':faction' => $factionid])->all();
            if (count($itemids) == 0)
                return json_encode(['result' => 'error', 'message' => 'Item Not Found in our records or its not on the Auction House! a']);
        }
        $id_arr = [];
        $item_arr = [];
        for($i=0; $i < count($itemids); $i++){
            $id_arr[] = $itemids[$i]->entry;
            $item_arr[$itemids[$i]->entry] = $itemids[$i];
        }
        $qry = "select ip.* from item_prices ip where ip.itemid in (".implode(",",$id_arr).") and ip.realm_id=:realm and `datetime`=(select max(datetime) from item_prices where realm_id=:realm and itemid=ip.itemid and faction_id=:faction) and faction_id=:faction group by ip.itemid";
        $ip = ItemPrices::findBySql($qry,[':realm'=>$realm_id,':faction'=>$factionid])->all();
        if(count($ip)==0){
            return json_encode(['result'=>'error','message'=>'We dont have prices for this item!']);
        }
        else {
            $out = [];
            $out['result'] = 'OK';
            $out['realm_name'] = $realm->name;
            $out['server_name'] = $realm->server->name;
            $out['faction'] = $faction->name;
            $out['matches'] = [];
            foreach($ip as $price){
                $item['name'] = $item_arr[$price->itemid]->name;
                $dt = new \DateTime($price->datetime);
                $item['datetime'] = $dt->getTimestamp();
                $item['ago'] = \Yii::$app->wowutil->ago($price->datetime);
                $item['bid_mean'] = $price->bid_mean;
                $item['bid_median'] = $price->bid_median;
                $item['cost_price'] = $price->cost_price;
                $item['quantity'] = $price->quantity;
                $item['buyout_mean'] = $price->buyout_mean;
                $item['buyout_median'] = $price->buyout_median;
                $item['bid_min'] = $price->bid_min;
                $item['buyout_min'] = $price->buyout_min;
                $item['trend'] = $price->buyout_median_last_compare;
                $item['link'] = \Yii::$app->urlManager->createAbsoluteUrl(["item/index",'id'=>$price->itemid,'realm'=>$price->realm_id,'faction'=>$price->faction_id]);
                $out['matches'][] = $item;
            }
            return json_encode($out);
        }
    }
}