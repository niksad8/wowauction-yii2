<?php

namespace app\commands;


use app\models\ProcessQueue;
use yii\console\Controller;

class RunImportsController extends Controller
{
    public function actionIndex(){
        while(1) {
            $pq = ProcessQueue::find()->where(['completed'=>0])->all();
            foreach($pq as $q){
                $end_part = "\"".\Yii::$app->getBasePath().DIRECTORY_SEPARATOR."auc-data".DIRECTORY_SEPARATOR.$q->filename."\" ".$q->realm_id." ".$q->faction_id;
                $cmd = "php ".\Yii::$app->getBasePath().DIRECTORY_SEPARATOR."yii processahdata ".$end_part;
                echo "running ".$cmd."\n";
                system($cmd,$ret);
                echo "return ".var_dump($ret)."\n";
                $cmd = "php ".\Yii::$app->getBasePath().DIRECTORY_SEPARATOR."yii item-build/process-items ".$q->realm_id." ".$q->faction_id;
                echo "running ".$cmd."\n";
                system($cmd,$ret2);
                echo "return ".var_dump($ret2)."\n";
                echo "running alerts scan ";
                $cmd = "php ".\Yii::$app->getBasePath().DIRECTORY_SEPARATOR."yii process-alerts/index ".$q->realm_id." ".$q->faction_id;
                $q->completed = 1;
                $q->datetime_completed = date('Y-m-d H:i:s');
                $q->save();
                echo "Task Completed \n";
            }
            sleep(1);
        }
    }
}