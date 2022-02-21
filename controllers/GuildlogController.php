<?php
/**
 * Created by PhpStorm.
 * User: nikhil
 * Date: 08-Aug-19
 * Time: 17:40
 */

namespace app\controllers;

use app\models\GuildLogCharacters;
use app\models\GuildLogEvents;
use app\models\GuildLogSettings;
use app\models\Realm;
use yii\web\Controller;
use yii\web\Response;

class GuildlogController extends Controller
{
    public function actionIndex(){

    }
    public function actionSaveguild(){
        $gid = \Yii::$app->request->get("gid");
        $cid = \Yii::$app->request->get("cid");
        $gname = \Yii::$app->request->get("gname");
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if($gid == "" || $cid == "" || $gname == ""){
            return ['result'=>"ERROR",'message'=>$gid." ".$cid." ".$gname.'Channel ID or Guild id or Guild name cannot be blank'];
        }
        $realm = "Icecrown";
        $words = explode(",",$gname);
        if(count($words) > 1){ // more than 1 word could be a realm name
            $realm = trim($words[0]);
            array_shift($words);
            $gname = trim(implode(",",$words));
        }
        $url = "http://armory.warmane.com/api/guild/$gname/$realm/summary/";
        $contents = file_get_contents($url);
        $json = json_decode($contents,true);

        if(isset($json['error'])){
            return ['result'=>"ERROR",'message'=>'Guild Not Found on warmane \n Usage : ?gl guild *realm name* *guild name*'];
        }
        $factionid = 1; // horde by default
        $check = GuildLogSettings::findOne(['guild_id'=>$gid,'realm_name'=>$realm]);
        if($check == null){
            $check = new GuildLogSettings();
        }
        $check->realm_name = $realm;
        $check->channel_id = $cid;
        $check->guild_id = $gid;
        $check->guild_name = $gname;
        $check->save();
        return ['result'=>'OK','name'=>$gname,'realm'=>$realm];
    }
    public function actionGetevents(){
        $events = GuildLogEvents::findAll(['sent'=>0]);
        $out = [];
        foreach($events as $event){
            $extra = '';
            if($event->operation == "LEFT"){
                $extra = "**Ranked : **".$event->char->rank."
**Was With Guild for : ** ".str_replace('ago','',\Yii::$app->wowutil->ago($event->char->joined_at));
            }
            else if($event->operation == "RANK_CHANGED"){
                $data = json_decode($event->extra_data,true);
                if($data != null){
                    $extra = "**Rank Changed** : from **".$data['old_rank']."** to **".$data['new_rank']."**";
                }
            }
            $out[] = [
                'cid'=>$event->channel_id,
                'gid'=>$event->guild_id,
                'char_name'=>$event->char_name,
                'datetime'=>$event->datetime,
                'operation'=>$event->operation,
                'extra'=>$extra,
                'char'=>[
                    'level'=>$event->char->char_level,
                    'rank'=>$event->char->rank,
                    'race'=>$event->char->race,
                    'class'=>$event->char->class_name
                ]
            ];
            $event->sent = 1;
            $event->save();
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $out;
    }
    public function actionOnlinelist(){
        $gid = \Yii::$app->request->get("gid");
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $check = GuildLogSettings::findOne(['guild_id'=>$gid]);
        if($check == null){
            return ['result'=>'error','message'=>'Your guild was not found on the server. Please run ?gl setup first'];
        }

        $chars = GuildLogCharacters::findAll(['guild_id'=>$gid,'is_online'=>1]);
        $out = [];
        foreach($chars as $char){
            $out[] = ['name'=>$char->char_name,'rank'=>$char->rank];
        }
        return ['result'=>'OK','response'=>$out];
    }
    public function actionStartchannel(){
        $gid = \Yii::$app->request->get("gid");
        $cid = \Yii::$app->request->get("cid");
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $check = GuildLogSettings::findOne(['guild_id'=>$gid]);
        if($check == null){
            return ['result'=>'error','message'=>'Your guild was not found on the server. Please run ?gl setup first'];
        }

        $check->channel_id = $cid;
        $check->save();
        return ['result'=>'OK'];
    }
    public function actionStopchannel(){
        $gid = \Yii::$app->request->get("gid");
        $cid = \Yii::$app->request->get("cid");
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $check = GuildLogSettings::findOne(['guild_id'=>$gid]);
        if($check == null){
            return ['result'=>'error','message'=>'Your guild was not found on the server. Please run ?gl setup first'];
        }

        $qry = "DELETE FROM guild_log_events where guild_id='".$gid."';";
        \Yii::$app->db->createCommand($qry)->execute();

        $qry = "DELETE FROM guild_log_characters where guild_id='".$gid."';";
        \Yii::$app->db->createCommand($qry)->execute();

        $check->delete();

        return ['result'=>'OK'];
    }
}