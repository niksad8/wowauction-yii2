<?php

namespace app\commands;


use app\models\Notifications;
use app\models\UserItemAlerts;
use yii\console\Controller;

class ProcessAlertsController extends Controller
{
    public function actionIndex($realm_id, $faction_id){
        /* @var $alerts UserItemAlerts[] */
        $alerts = UserItemAlerts::findAll(['alert_sent'=>0,'realm_id'=>$realm_id,'faction_id'=>$faction_id]);

        foreach($alerts  as $alert){
            if($alert->isTriggered()){
                $alert->generateAppropriateNotifications();
                $alert->alert_sent = 1;
                $alert->save();
            }
        }
    }
    public function actionEmailProcessor(){
        while(1){
            $nots = Notifications::findAll(['notification_type'=>'email','sent'=>0]);
            foreach($nots as $not){
                $not->sent = 1;
                $email = $not->user->getSetting("email_address");
                if($email != "" && strstr($email,"@") !== false) {
                    $message = \Yii::$app->mailer->compose();
                    $message->setTo($email)->setFrom(\Yii::$app->params['fromEmail']);
                    $message->setSubject($not->title)->setHtmlBody($not->message);
                    try {
                        $not->sent = 1;
                        $not->datetime_sent = date('Y-m-d H:i:s');
                        $not->save();
                        $message->send();
                    }
                    catch(\Exception $e){
                        echo "Opps something went wrong : ".$e->getMessage()."\n";
                    }
                    echo "sent email for ".$not->id." ".$not->user->username."($email)\n";
                }
            }
            sleep(1);
        }
    }
}
