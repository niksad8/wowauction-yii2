<?php
/**
 * Created by PhpStorm.
 * User: nikhil
 * Date: 05-Aug-19
 * Time: 05:26
 */

namespace app\controllers;


use app\models\AhscanAttempts;
use app\models\Factions;
use app\models\Realm;
use yii\web\Controller;
use yii\web\Response;

class SchedulerController extends Controller
{
    public function actionIndex() {
        $realms = Realm::find()->all();
        $out = [];
        $factions = ['','h','a'];
        foreach($realms as $realm) {
            for($i=1; $i < 3; $i++) {
                $json = json_decode($realm->data,true);
                if($json != NULL && isset($json[$factions[$i]])) {
                    $scan = AhscanAttempts::find()->where(['=', 'realm_id', $realm->id])->andWhere(['=', 'faction_id', $i])->orderBy('created_at desc')->one();
                    if ($scan == null) { // nothing found in scan attempts start for realm and faction id
                        $out[] = ['realm_id' => $realm->id, 'faction_id' => $factions[$i]];
                    } else {
                        if ($scan->status == AhscanAttempts::STATUS_SUCCESS) {
                            $dt = new \DateTime($scan->created_at);
                            $timeout = $realm->update_schedule;
                            $diff = (time() - $dt->getTimestamp());
                            if (($diff + (5 * 60)) >= $timeout) // add 5 mins to make it start more consistently
                            {
                                $out[] = ['realm_id' => $realm->id, 'faction_id' => $factions[$i]];
                            }
                        } else if ($scan->status == AhscanAttempts::STATUS_STARTED) {
                            $dt = new \DateTime($scan->pinged_at == null ? $scan->created_at : $scan->pinged_at);
                            $diff = (time() - $dt->getTimestamp());
                            $xx = 30 * 60; // 30 mins
                            if ($diff >= $xx) {
                                $scan->status = AhscanAttempts::STATUS_FAILED;
                                $scan->save();
                                $out[] = ['realm_id' => $realm->id, 'faction_id' => $factions[$i]];
                            }
                        } else if ($scan->status == AhscanAttempts::STATUS_FAILED) {
                            $out[] = ['realm_id' => $realm->id, 'faction_id' => $factions[$i]];
                        }
                    }
                }
            }
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $out;
    }
    public function actionStart(){
        $realmid = \Yii::$app->request->get("realm_id");
        $factionid = \Yii::$app->request->get("faction_id");

        if(!is_int($factionid)) {
            if ($factionid == 'h')
                $factionid = 1;
            else
                $factionid = 2;
        }

        $realm = Realm::findOne(['id'=>$realmid]);
        $faction = Factions::findOne(['id'=>$factionid]);

        if($realmid != "" && $factionid != "" && $realm != null && $faction != null){
            $scan = new AhscanAttempts();
            $scan->created_at = date('Y-m-d H:i:s');
            $scan->realm_id = $realmid;
            $scan->faction_id = $factionid;
            $scan->status = AhscanAttempts::STATUS_STARTED;
            if(!$scan->save()){
                print_r($scan->getErrors());
            }
            return "OK";
        }
        else {
            return "ERROR : realmid or factionid not valid";
        }
    }
    public function actionPing(){
        $realmid = \Yii::$app->request->get("realm_id");
        $factionid = \Yii::$app->request->get("faction_id");

        if(!is_int($factionid)) {
            if ($factionid == 'h')
                $factionid = 1;
            else
                $factionid = 2;
        }

        $realm = Realm::findOne(['id'=>$realmid]);
        $faction = Factions::findOne(['id'=>$factionid]);

        if($realmid != "" && $factionid != "" && $realm != null && $faction != null) {
            $scan = AhscanAttempts::findOne(['realm_id'=>$realmid,'faction_id'=>$factionid,'status'=>AhscanAttempts::STATUS_STARTED]);
            if($scan == NULL){
                return "ERROR : scan not found!";
            }
            else {
                $scan->pinged_at = date('Y-m-d H:i:s');
                $scan->save();
                return "OK";
            }
        }
        else {
            return "ERROR : realmid or factionid not valid";
        }
    }
    public function actionEnd(){
        $realmid = \Yii::$app->request->get("realm_id");
        $factionid = \Yii::$app->request->get("faction_id");

        if(!is_int($factionid)) {
            if ($factionid == 'h')
                $factionid = 1;
            else
                $factionid = 2;
        }

        $realm = Realm::findOne(['id'=>$realmid]);
        $faction = Factions::findOne(['id'=>$factionid]);

        if($realmid != "" && $factionid != "" && $realm != null && $faction != null) {
            $scan = AhscanAttempts::findOne(['realm_id'=>$realmid,'faction_id'=>$factionid,'status'=>AhscanAttempts::STATUS_STARTED]);
            if($scan == NULL){
                return "ERROR : scan not found!";
            }
            else {
                $scan->completed_at = date('Y-m-d H:i:s');
                $scan->status = AhscanAttempts::STATUS_SUCCESS;
                $scan->save();
                return "OK";
            }
        }
        else {
            return "ERROR : realmid or factionid not valid";
        }

    }
    public function actionFail(){
        $realmid = \Yii::$app->request->get("realm_id");
        $factionid = \Yii::$app->request->get("faction_id");
        $reason = \Yii::$app->request->get("reason");
        if(!is_int($factionid)) {
            if ($factionid == 'h')
                $factionid = 1;
            else
                $factionid = 2;
        }

        $realm = Realm::findOne(['id'=>$realmid]);
        $faction = Factions::findOne(['id'=>$factionid]);

        if($realmid != "" && $factionid != "" && $realm != null && $faction != null) {
            $scan = AhscanAttempts::findOne(['realm_id'=>$realmid,'faction_id'=>$factionid,'status'=>AhscanAttempts::STATUS_STARTED]);
            if($scan == NULL){
                return "ERROR : scan not found!";
            }
            else {
                $scan->completed_at = date('Y-m-d H:i:s');
                $scan->status = AhscanAttempts::STATUS_FAILED;
                $scan->save();
                return "OK";
            }
        }
        else {
            return "ERROR : realmid or factionid not valid";
        }
    }
}