<?php
/**
 * Created by PhpStorm.
 * User: nikhil
 * Date: 02-Feb-19
 * Time: 19:36
 */

namespace app\commands;


use app\models\ItemDisplayInfo;
use yii\Console\Controller;
use yii\console\ExitCode;

class ImportIconsController extends Controller
{
    function parse_csv ($csv_string, $delimiter = ",", $skip_empty_lines = true, $trim_fields = true)
    {
        $enc = preg_replace('/(?<!")""/', '!!Q!!', $csv_string);
        $enc = preg_replace_callback(
            '/"(.*?)"/s',
            function ($field) {
                return urlencode(utf8_encode($field[1]));
            },
            $enc
        );
        $lines = preg_split($skip_empty_lines ? ($trim_fields ? '/( *\R)+/s' : '/\R+/s') : '/\R/s', $enc);
        return array_map(
            function ($line) use ($delimiter, $trim_fields) {
                $fields = $trim_fields ? array_map('trim', explode($delimiter, $line)) : explode($delimiter, $line);
                return array_map(
                    function ($field) {
                        return str_replace('!!Q!!', '"', utf8_decode(urldecode($field)));
                    },
                    $fields
                );
            },
            $lines
        );
    }
    public function actionIndex(){
        $file = \Yii::$app->getBasePath().DIRECTORY_SEPARATOR."dbc".DIRECTORY_SEPARATOR."ItemDisplayInfo.dbc.csv";
        $contents = file_get_contents($file);
        $lines=  $this->parse_csv($contents);
        for($i=0; $i < count($lines); $i++){
            $id = $lines[$i][0];
            $icon_str = $lines[$i][5];
            $test = ItemDisplayInfo::findOne(['id'=>$id]);
            echo "imported ".$id. " " .$icon_str."\n";
            if($test == NULL){
                $icon = new ItemDisplayInfo();
                $icon->id= $id;
                $icon->icon_name = $icon_str;
                $icon->save();
            }
        }
    }
}