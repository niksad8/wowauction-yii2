<?php
/**
 * Created by PhpStorm.
 * User: nikhil
 * Date: 22-Apr-19
 * Time: 01:32
 */

namespace app\controllers;


use app\models\EmailSubscriptions;
use app\models\Factions;
use app\models\ItemTemplate;
use app\models\Notifications;
use app\models\Realm;
use app\models\User;
use app\models\UserItemAlerts;
use app\models\Users;
use app\models\UserSettings;
use app\widgets\Alert;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class UsersController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['profile','saveemail','getdesktopnotification','testdiscord','savediscordname','changenotificationoption','changepassword','setrealmfaction','savealert','deletealert','resetalert'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions'=>['register'],
                        'allow'=>true
                    ]
                ],
            ],
        ];
    }

    public function actionSaveemail(){
        $email = \Yii::$app->request->post('email');
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if($email == "" || strstr($email,'@') === false){
            return ['result'=>'error','message'=>'EMail is not Valid!'];
        }
        else {
            UserSettings::set('email_address',$email);
            $email_subs = EmailSubscriptions::findAll(['userid'=>null,'email'=>$email]);
            for($i=0; $i < count($email_subs); $i++){
                $email_subs[$i]->userid = \Yii::$app->user->getId();
                $email_subs[$i]->save();
            }
            return ['result'=>'ok'];
        }
    }

    public function actionGetdesktopnotification(){
        $nots = Notifications::findAll(['notification_type'=>'desktop','sent'=>0,'userid'=>\Yii::$app->user->getId()]);
        $out = ['status'=>'SUCCESS','response'=>[]];

        foreach ($nots as $not){
            $res= [
                'title'=>$not->title,
                'body'=>$not->message,
                'icon'=>\Yii::$app->urlManager->createAbsoluteUrl("").$not->icon,
                'url'=>\Yii::$app->urlManager->createAbsoluteUrl(["item/index",'id'=>$not->item_id,'faction'=>$not->alert->faction_id,'realm'=>$not->alert->realm_id])
            ];
            $not->sent = 1;
            $not->datetime_sent = date('Y-m-d H:i:s');
            $not->save();
            $out['response'][] = $res;
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $out;
    }
    public function actionChangenotificationoption(){
        $setting = \Yii::$app->request->post("setting");
        if($setting == "")
            return;
        $value = \Yii::$app->request->post("value");
        if($setting == "desktop" || $setting == "discord" || $setting == 'email'){
            UserSettings::set($setting."_notification",$value == "true"?"1":null);
        }
    }
    public function actionTestdiscord(){
        $user = Users::findOne(['id'=>\Yii::$app->user->getId()]);
        $discord_user = $user->getSetting('discord_name');
        if($discord_user == NULL){
            return "Your discord tag name is not set. ";
        }
        $not = new Notifications();
        $not->userid = \Yii::$app->user->getId();
        $not->icon ='';
        $not->title = 'This is TEST';
        $not->message = 'This is a test message from web-auctioneer.com';
        $not->sent = 0;
        $not->datetime_created = date('Y-m-d H:i:s');
        $not->notification_type = 'discord';
        $not->item_id= 0;
        $not->url = '';
        $not->alert_id = 0;
        $not->save();
        return "OK";
    }
    public function actionSavediscordname(){
        $tag = \Yii::$app->request->post("tag");
        UserSettings::set("discord_name",$tag);
    }
    public function actionSavealert(){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $id = \Yii::$app->request->post('id');

        $v1 = \Yii::$app->request->post('v1');
        $v2 = \Yii::$app->request->post('v2');
        $op = \Yii::$app->request->post('op');
        $ptype = \Yii::$app->request->post('ptype');

        $realm = \Yii::$app->request->post('realm');
        $faction = \Yii::$app->request->post('faction');

        if($realm == "")
            $realm = \Yii::$app->session->get('realm');
        if($faction == "")
            $faction = \Yii::$app->session->get('faction');

        $op_arr = ['>=', '<=', '><'];
        $ptype_arr= ['median_buyout','mean_buyout','median_bid','mean_bid','min_buyout','min_bid'];
        if(array_search($ptype,$ptype_arr) === false){
            return (['status'=>'ERROR','message'=>'Price value is not correct please select one']);
        }

        if(array_search($op,$op_arr) === false){
            return (['status'=>'ERROR','message'=>'Operation type is not valid.']);
        }
        $item = ItemTemplate::findOne(['entry'=>$id]);
        if($item == NULL){
            return (['status'=>'ERROR','message'=>'Item Not Found']);
        }
        if(!is_int((int)$v1) || $v1 < 0){
            return (['status'=>'ERROR','message'=>'Entered first value is not a valid number it should be an integer and greater than 0']);
        }
        if($op == '><'  && (!is_int((int)$v2) || $v2 < 0 || $v2 < $v1)){
            return (['status'=>'ERROR','message'=>'Entered second value is not a valid number it should be an integer and greater than 0 and greater than first value']);
        }
        $alert = new UserItemAlerts();
        $alert->datetime_set = date('Y-m-d H:i:s');
        $alert->user_id = \Yii::$app->user->getId();
        $alert->item_id = $id;
        $alert->price_type =$ptype;
        $alert->realm_id = $realm;
        $alert->faction_id = $faction;
        $alert->op = $op;
        $alert->value1 = $v1;
        $alert->value2 = $v2;
        $alert->alert_sent = 0;
        $alert->sent_datetime = null;
        $alert->send_to = '';
        if(!$alert->save()){
            print_r($alert->getErrors());
        }
        $data = [];
        $alerts = \Yii::$app->user->identity->alerts;
        foreach($alerts as $aa){
            if($id != '' && $aa->item_id == $id)
                $data[] = $aa->getJson();
            else if($id == '')
                $data[] = $aa->getJson();
        }
        return (['status'=>'SUCCESS','data'=>$data]);
    }
    public function actionDeletealert(){
        $id = \Yii::$app->request->post('id');
        $itemid = \Yii::$app->request->post('itemid');

        $alert = UserItemAlerts::findOne(['id'=>$id,'user_id'=>\Yii::$app->user->getId()]);
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if($alert == NULL){
            return (['status'=>'ERROR','message'=>'Alert Not found!']);
        }
        else {
            $alert->delete();
            $data = [];
            $alerts = \Yii::$app->user->identity->alerts;
            foreach($alerts as $aa){
                if($itemid != '' && $aa->item_id == $itemid)
                    $data[] = $aa->getJson();
                else if($itemid == '')
                    $data[] = $aa->getJson();
            }
            return (['status'=>'SUCCESS','data'=>$data]);
        }

    }
    public function actionResetalert(){
        $id = \Yii::$app->request->post('id');

        $alert = UserItemAlerts::findOne(['id'=>$id,'user_id'=>\Yii::$app->user->getId()]);
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if($alert == NULL){
            return (['status'=>'ERROR','message'=>'Alert Not found!']);
        }
        else {
            $alert->alert_sent = 0;
            $alert->sent_datetime = null;
            $alert->save();
            $data = [];
            $alerts = \Yii::$app->user->identity->alerts;
            foreach($alerts as $aa){
                $data[] = $aa->getJson();
            }
            return (['status'=>'SUCCESS','data'=>$data]);
        }
    }
    public function actionSetrealmfaction(){
        $realm_id = \Yii::$app->request->post("realm");
        $faction_id  =\Yii::$app->request->post("faction");
        $realm = Realm::findOne(['id'=>$realm_id]);
        $faction  =Factions::findOne(['id'=>$faction_id]);
        if($realm == NULL || $faction == NULL){
            \Yii::$app->session->setFlash("error","The realm or faction that you have selected is not valid!");
            return \Yii::$app->response->redirect(["users/profile"]);
        }
        $user = Users::findOne(['id'=>\Yii::$app->user->getId()]);
        $user->server_id = $realm->server_id;
        $user->realm_id = $realm->id;
        $user->faction_id = $faction->id;
        \Yii::$app->session->set('realm',$realm->id);
        \Yii::$app->session->set('faction',$faction->id);
        $user->save();
        \Yii::$app->session->setFlash("success","Updated successfully!");
        return \Yii::$app->response->redirect(["users/profile"]);
    }

    public function actionRegister(){

        $username = \Yii::$app->request->post("username");
        $password = \Yii::$app->request->post("password");
        $repassword = \Yii::$app->request->post("repassword");
        if($password != $repassword){
            \Yii::$app->session->setFlash("error","The passwords dont match");
            return \Yii::$app->response->redirect(["users/profile"]);
        }
        $user = Users::findOne(['username'=>$username]);
        if($user != NULL){
            \Yii::$app->session->setFlash("error","This username `".$user->username."` already exists.");
            return \Yii::$app->response->redirect(["users/profile"]);
        }
        $user = new Users();
        $user->created_at = date('Y-m-d H:i:s');
        $user->enabled = 1;
        $user->is_admin = 0;
        $user->username = $username;
        $user->setPassword($password);
        if(!$user->save()){
            print_r($user->getErrors());
            die();
        }
        \Yii::$app->session->setFlash("success","Your account was created successfully created. You can login now.");
        return $this->redirect(["site/login"]);
    }

    public function actionChangepassword(){
        $oldpassword = \Yii::$app->request->post("old_password");
        $newpassword = \Yii::$app->request->post("new_password");
        $renewpassword = \Yii::$app->request->post("renew_password");
        if($newpassword == "" || $newpassword != $renewpassword){
            \Yii::$app->session->setFlash("error","The new passwords dont match");
            return \Yii::$app->response->redirect(["users/profile"]);
        }
        $user = Users::findOne(['id'=>\Yii::$app->user->getId()]);
        if($user == NULL){
            \Yii::$app->session->setFlash("error","User not found");
            return \Yii::$app->response->redirect(["users/profile"]);
        }

        if(!$user->checkPassword($oldpassword)){
            \Yii::$app->session->setFlash("error","The Old Password is incorrect");
            return \Yii::$app->response->redirect(["users/profile"]);
        }
        $user->setPassword($newpassword);
        $user->save();
        \Yii::$app->session->setFlash("success","The password has been changed successfully");
        return \Yii::$app->response->redirect(["users/profile"]);
    }
    public function actionProfile()
    {
        return $this->render('profile',['model'=>\Yii::$app->user->identity]);
    }
}