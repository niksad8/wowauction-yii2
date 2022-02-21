<?php
/**
 * Created by PhpStorm.
 * User: nikhil
 * Date: 22-Apr-19
 * Time: 04:32
 */

namespace app\commands;

use app\models\Users;
use yii\console\Controller;

class UpdatepasswordController extends Controller
{
    public function actionIndex(){
        $users = Users::find()->all();
        foreach($users as $user){
            $user->setPassword($user->password);
            if(!$user->save()){
                print_r($user->getErrors());
            }
            echo $user->username." saved\n";
        }
    }
}