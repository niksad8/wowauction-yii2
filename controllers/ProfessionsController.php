<?php
/**
 * Created by PhpStorm.
 * User: nikhil
 * Date: 15-Apr-19
 * Time: 03:51
 */

namespace app\controllers;


use app\models\Factions;
use app\models\Professions;
use app\models\Realm;
use app\models\Servers;
use yii\base\Controller;

class ProfessionsController extends Controller
{
    public function actionIndex()
    {
        if(\Yii::$app->request->get("realm") != ""){
            \Yii::$app->session->set("realm",\Yii::$app->request->get("realm"));
        }
        if(\Yii::$app->request->get("faction") != ""){
            \Yii::$app->session->set("faction",\Yii::$app->request->get("faction"));
        }
        $id = \Yii::$app->request->get("id");
        $realmid = \Yii::$app->session->get("realm");
        $factionid  =\Yii::$app->session->get("faction");
        $profession = Professions::findOne(['id'=>$id]);
        $realm = Realm::findOne(['id'=>$realmid]);
        $faction = Factions::findOne(['id'=>$factionid]);

        return $this->render("index",['model'=>$profession,'realm'=>$realm,'faction'=>$faction]);
    }

    public function actionGetprofessionlist(){

    }
}