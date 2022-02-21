<?php

namespace app\controllers;

use app\models\Realm;
use Yii;
use app\models\Servers;
use app\models\ServersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ServersController implements the CRUD actions for Servers model.
 */
class ServersController extends Controller
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
    public function actionGetrealmlist(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = \Yii::$app->request->get("id");
        $server = Servers::findOne(['id'=>$id]);
        if($server != null){
            $out = [];
            $realms = Realm::find()->where(['server_id'=>$server->id])->all();
            foreach($realms as $realm){
                $out[] = ['id'=>$realm->id,'name'=>$realm->name];
            }
            return ($out);
        }
        return json_encode([]);
    }
    public function actionBase(){

        $id = Yii::$app->request->get("id");
        $realms = Realm::findAll(['server_id'=>$id]);
        $server =Servers::findOne(['id'=>$id]);
        if($server == null )
            return "";
        Yii::$app->params['image'] = '/images/servers/'.$id;
        Yii::$app->session->set("server",$id);
        return $this->render("base",['realms'=>$realms,'server'=>$server]);
    }
}
