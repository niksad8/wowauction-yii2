<?php

namespace app\controllers;

use app\models\Factions;
use Yii;
use app\models\Realm;
use app\models\RealmSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RealmController implements the CRUD actions for Realm model.
 */
class RealmController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionGetfile(){
        $id = Yii::$app->request->get("id");
        $factionid = Yii::$app->request->get("faction");
        $faction = Factions::findOne(['id'=>$factionid]);
        $realm = Realm::findOne(['id'=>$id]);
        $fname = $realm->name."_".$faction->id."_aucdata";
        $fullpath = \Yii::$app->basePath.DIRECTORY_SEPARATOR."auc-data".DIRECTORY_SEPARATOR.$fname;
        if(file_exists($fullpath)){
            header('Content-Description: File Transfer');
            header('Content-Type: plain/txt');
            header('Content-Disposition: attachment; filename="'.$fname.'-Auc-ScanData.lua"');
            header('Content-Transfer-Encoding: text');
            header('Content-Length: ' . filesize($fullpath)); //Absolute URL
            readfile($fullpath);
            die();
        }
    }
    /**
     * Lists all Realm models.
     * @return mixed
     */

    public function actionBase(){
        $id = Yii::$app->request->get("id");
        $factionid = Yii::$app->request->get("faction");
        $faction = Factions::findOne(['id'=>$factionid]);
        $realm = Realm::findOne(['id'=>$id]);
        if($realm == NULL){
            return "";
        }
        \Yii::$app->session->set("realm",$id);
        if($faction != null){
            \Yii::$app->session->set("faction",$factionid);
        }
        Yii::$app->params['image'] = '/images/expansions/'.$realm->expansion->id;
        return $this->render("base",['realm'=>$realm,'selected_faction'=>$faction]);
    }
}
