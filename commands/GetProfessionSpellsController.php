<?php
/**
 * Created by PhpStorm.
 * User: nikhil
 * Date: 14-Apr-19
 * Time: 22:15
 */

namespace app\commands;
use app\models\Professions;
use app\models\ProfessionSpells;
use yii\console\Controller;
use yii\console\ExitCode;

class GetProfessionSpellsController extends Controller
{
    public $item_urls = [
        '3'=>'https://www.wowhead.com/items?filter=68:82;1:4;0:30305', // enchanting
        '12'=>'https://www.wowhead.com/items?filter=69:82;1:4;0:30305', // fishing
        '5'=>'https://www.wowhead.com/items?filter=70:82;1:4;0:30305', //herbalism
        '6'=>'https://www.wowhead.com/items?filter=143:82;1:4;0:30305', // inscription
        '7'=>'https://www.wowhead.com/items?filter=88:82;1:4;0:30305', // prospecting
        '9'=>'https://www.wowhead.com/items?filter=73:82;1:4;0:30305', // mining
        '10'=>'https://www.wowhead.com/items?filter=76:82;1:4;0:30305' // skinning
    ];
    public $urls = [
        '1' =>'https://www.wowhead.com/alchemy-spells?filter=21;4;30305',
        '2' =>'https://www.wowhead.com/blacksmithing-spells?filter=21;4;30305',
        '3' =>'https://www.wowhead.com/enchanting-spells?filter=21;4;30305',
        '4'=>'https://www.wowhead.com/engineering-spells?filter=21;4;30305',
        '5'=>'https://www.wowhead.com/herbalism-spells?filter=21;4;30305',
        '6'=>'https://www.wowhead.com/inscription-spells?filter=21;4;30305',
        '7'=>'https://www.wowhead.com/jewelcrafting-spells?filter=21;4;30305',
        '8'=>'https://www.wowhead.com/leatherworking-spells?filter=21;4;30305',
        '9'=>'https://www.wowhead.com/mining-spells?filter=21;4;30305',
        '10'=>'https://www.wowhead.com/skinning-spells?filter=21;4;30305',
        '11'=>'https://www.wowhead.com/tailoring-spells?filter=21;4;30305'
    ];
    public function actionIndex()
    {
        $expansion = 3;
        $result_index = [];

        foreach($this->urls as $idx => $url){
            echo $idx." ".$url."\n";

            $contents = file_get_contents($url);
            $lines=  explode("\n",$contents);
            $obj3 = null;
            $proff = Professions::findOne(['id'=>$idx]);
            $result_index[$idx] = [];
            if($proff != NULL) {
                echo "deleteing previous profession spells\n";
                $qry = "DELETE FROM profession_spells where profession_id='".$idx."' and expansion_id='".$expansion."'";
                \Yii::$app->db->createCommand($qry)->query();
                for ($i = 0; $i < count($lines); $i++) {
                    /*if(strstr($lines[$i],"WH.Gatherer.addData(3")){
                        $str = substr($lines[$i],strlen("WH.Gatherer.addData(3, \"live\", "),-2);
                        $obj = json_decode($str,true);
                    }
                    if(strstr($lines[$i],"WH.Gatherer.addData(6")){
                        $str = substr($lines[$i],strlen("WH.Gatherer.addData(6, \"live\", "),-2);
                        $obj2= json_decode($str,true);
                    }*/
                    if (strstr($lines[$i], "var listviewspells = ")) {
                        $str = substr($lines[$i], strlen("var listviewspells = "), -1);
                        $obj3 = json_decode($str, true);
                    }
                }
                if ($obj3 != NULL) {
                    for($i=0; $i < count($obj3); $i++){
                        $obj = $obj3[$i];
                        if(isset($obj['creates'])) { // only get spells which result in to something
                            $ps = new ProfessionSpells();
                            $ps->spell_id = $obj['id'];
                            $ps->profession_id = $proff->id;
                            $ps->result_item_id = $obj['creates'][0];
                            $ps->result_item_quantity = $obj['creates'][1];
                            $ps->expansion_id = $expansion;
                            $ps->save();
                            $result_index[$idx][] = $ps->result_item_id;
                            echo $obj['name'] ." saved \n";
                        }
                        else {
                            echo $obj['name']. " skipped \n";
                        }
                    }
                } else {
                    echo "failed to get $url\n";
                }
            }
            else {
                echo "Opps profession id $idx not found";
            }
        }
        echo "Now getting profession items \n";
        foreach($this->item_urls as $idx => $url){
            echo $idx." ".$url."\n";

            $contents = file_get_contents($url);
            $lines=  explode("\n",$contents);
            $obj3 = null;
            for ($i = 0; $i < count($lines); $i++) {
                /*if(strstr($lines[$i],"WH.Gatherer.addData(3")){
                    $str = substr($lines[$i],strlen("WH.Gatherer.addData(3, \"live\", "),-2);
                    $obj = json_decode($str,true);
                }
                if(strstr($lines[$i],"WH.Gatherer.addData(6")){
                    $str = substr($lines[$i],strlen("WH.Gatherer.addData(6, \"live\", "),-2);
                    $obj2= json_decode($str,true);
                }*/
                if (strstr($lines[$i], "var listviewitems = ")) {
                    $str = substr($lines[$i], strlen("var listviewitems = "), -1);
                    $obj3 = json_decode(str_replace("firstseenpatch","\"firstseenpatch\"",$str), true);
                    echo $str."\n";
                }
            }
            if ($obj3 != NULL) {
                for($i=0; $i < count($obj3); $i++){
                    $obj = $obj3[$i];
                    if(!isset($result_index[$idx]) || !array_search($obj['id'],$result_index[$idx])) { // only get spells which result in to something
                        $ps = new ProfessionSpells();
                        $ps->spell_id = $idx;
                        $ps->profession_id = $idx;
                        $ps->result_item_id = $obj['id'];
                        $ps->result_item_quantity = 1;
                        $ps->expansion_id = $expansion;
                        $ps->save();
                        $result_index[$idx][] = $ps->result_item_id;
                        echo $obj['name'] ." saved \n";
                    }
                    else {
                        echo $obj['name']. " skipped already exists in list \n";
                    }
                }
            } else {
                echo "failed to get $url\n";
            }
        }
        return ExitCode::OK;
    }
}