<?php
/**
 * Created by PhpStorm.
 * User: nikhil
 * Date: 24-Jul-19
 * Time: 04:21
 */

namespace app\commands;


use app\models\ItemClass;
use app\models\ItemSubClass;
use yii\console\Controller;

class ImportFromCsvController extends Controller
{
    public function actionItemclass(){
        $filename = \Yii::$app->getBasePath()."/ItemClass.dbc.csv";
        $contents = file_get_contents($filename);
        $lines = explode("\r\n",$contents);
        for($i=1; $i < count($lines); $i++){
            $cols = str_getcsv($lines[$i]);
            if(count($cols) > 2) {
                $id = $cols[0];
                $name = $cols[3];
                if ($id != '') {
                    $class_test = ItemClass::findOne(['id' => $id]);
                    if ($class_test == NULL) {
                        $class_test = new ItemClass();
                        $class_test->id = $id;

                    }
                    if ($class_test->name != $name) {
                        $class_test->name = $name;
                        $class_test->save();
                        echo "class with name " . $name . " saved. \n ";
                    }
                }
            }
        }
    }

    public function actionItemsubclass(){
        $filename = \Yii::$app->getBasePath()."/ItemSubClass.dbc.csv";
        $contents = file_get_contents($filename);
        $lines = explode("\r\n",$contents);
        for($i=1; $i < count($lines); $i++){
            $cols = str_getcsv($lines[$i]);
            if(count($cols) > 2) {
                $id = $cols[1];
                $main_class = $cols[0];
                $name = $cols[10];
                echo $cols[1]."  ".$cols[0]." ".$cols[10]."\n";
                if ($id != '') {
                    $class_test = ItemSubClass::findOne(['id' => $id,'class_id'=>$main_class]);
                    if ($class_test == NULL) {
                        $class_test = new ItemSubClass();
                        $class_test->id = $id;
                        $class_test->class_id = $main_class;
                    }
                    if ($class_test->name != $name) {
                        $class_test->name = $name;
                        if(!$class_test->save()){
                            print_r($class_test->getErrors());
                        }
                        echo "class with name " . $name . " saved. \n ";
                    }
                }
            }
        }
    }
}