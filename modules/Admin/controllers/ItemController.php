<?php

namespace app\modules\Admin\controllers;

use app\models\AuctionItem;
use app\models\Factions;
use app\models\ItemTemplate;
use app\models\Realm;
use yii\helpers\Json;
use yii\web\Response;

class ItemController extends \yii\web\Controller
{
    public function actionSearch(){
        $term = \Yii::$app->request->get("term");
        $out = ["items"=>[]];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if($term == "" or strlen($term) < 3){
            return $out;
        }
        else {
            $items = ItemTemplate::find()->where("name like :name",[":name"=>"%".$term."%"])->all();
            foreach($items as $item){
                $out['items'][] = ['id'=>$item->entry,'display'=>\Yii::$app->wowutil->printItem($item->entry)];
            }
        }
        return $out;
    }
    public function actionGetrealms(){
        $id = \Yii::$app->request->get("id");
        $realms = Realm::find()->where(['server_id'=>$id])->all();
        $out = [];
        $out['options']=[];
        foreach ($realms as $realm){
            $out['options'][] = ['id'=>$realm->id,'name'=>$realm->name];
        }
        $factions = Factions::find()->all();
        $out['factions'] = [];
        foreach($factions as $faction){
            $out['factions'][] = ['id'=>$faction->id,'name'=>$faction->name];
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $out;
    }
    public function actionIndex()
    {
        $id = \Yii::$app->request->get("id");
        $faction_id = \Yii::$app->request->get("faction");
        $realm_id = \Yii::$app->request->get("realm");

        if($faction_id == "")
            $faction_id = \Yii::$app->session->get("faction",0);
        if($realm_id == "")
            $realm_id = \Yii::$app->session->get("realm",0);

        $item = ItemTemplate::findOne(['entry'=>$id]);
        if($item == NULL){
            return "Item not found";
        }
        $faction = Factions::findOne(['id'=>$faction_id]);
        $realm = Realm::findOne(['id'=>$realm_id]);
        if($faction != null && $realm != null) {
            \Yii::$app->session->set("faction",$faction_id);
            \Yii::$app->session->set("realm",$realm_id);
        }
        return $this->render('index',['item'=>$item,'faction'=>$faction,'realm'=>$realm]);
    }

}
