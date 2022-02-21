<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\AhMainCat;
use app\models\AhSubCat;
use app\models\AuctionItem;
use app\models\Expansion;
use app\models\Factions;
use app\models\Realm;
use app\models\Servers;
use app\models\SlotMaster;
use app\models\SlotTranslation;
use yii\console\Controller;
use yii\console\ExitCode;
include("parser/Parser.php");
include("parser/parser2.php");


class ProcessahdataController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */

    public function actionIndex($file, $realmid, $inp_faction = "")
    {
        $filename = $file;
        $server =Realm::findOne(['id'=>$realmid]);
        if($server == NULL){
            echo "Server with id $realmid not found.";
            return ExitCode::UNSPECIFIED_ERROR;
        }
        if(!file_exists($filename)){
            echo "File not found ".$filename;
            return ExitCode::UNSPECIFIED_ERROR;
        }
        $faction_name = "";
        $factionrow = null;
        if($inp_faction == "")
            echo "Importing all factions!\n";
        else {
            $factionrow = Factions::findOne(['id' => $inp_faction]);
            $faction_name = $factionrow->name;
        }
        $dat = new \LUAParser();
        $dat->parseFile($filename);
        $data =$dat->data['AucScanData']['scans'];
        foreach($data as $realm => $val){
            echo $realm." Realm\n";
            $realmrow = Realm::findOne(['id'=>$realmid,'name'=>$realm]);
            if($realmrow != null) {

                foreach ($val as $faction => $val2) {
                    if ($faction == $faction_name || $inp_faction == "") {
                        echo "\t" . $faction . " faction\n";
                        $rows = [];
                        if($realmrow->expansion->version_no == Expansion::$EXPANSION_WOTLK)
                            $rows = $val2['ropes'];
                        else if($realmrow->expansion->version_no == Expansion::$EXPANSION_TBC)
                            $rows[] = $val2['image'];

                        $newfile = "";
                        if ($inp_faction == "") {
                            $factionrow = Factions::findOne(['name' => $faction]);
                            $faction_name = $factionrow->name;
                        }
                        $qry = "DELETE FROM old_auction_item where faction='" . $factionrow->id . "' and realm_id='" . $realmrow->id . "';";
                        $db = \Yii::$app->getDb();
                        $db->createCommand($qry)->execute();

                        $qry = "INSERT INTO old_auction_item select * from auction_item where faction='".$factionrow->id."' and realm_id='".$realmrow->id."'";
                        $db = \Yii::$app->getDb();
                        $db->createCommand($qry)->execute();

                        $qry = "DELETE FROM auction_item where faction='" . $factionrow->id . "' and realm_id='" . $realmrow->id . "';";
                        $db = \Yii::$app->getDb();
                        $db->createCommand($qry)->execute();

                        echo "Finished delete...";
                        //var_dump($rows);
                        for ($i = 0; $i < count($rows); $i++) {
                            $text = $rows[$i];
                            $text = str_replace("},{", "},\n{", str_replace('\"', '"', str_replace("return ", "\n", $text)));
                            $text = str_replace("{", "[", $text);
                            $text = str_replace("}", "]", $text);
                            $text = str_replace(",]", "]", $text);
                            $text = str_replace('\"', "\"", $text);
                            $text = str_replace("nil", "0", $text);
                            $text = str_replace("\", -- [" . ($i + 1) . "]", "", $text);

                            $allobj = json_decode($text, true);
                            $totalcount = count($allobj);
                            $saved = 0;
                            $counter = 0;
                            $batch_records = [];
                            for ($c = 0; $c < count($allobj); $c++) {
                                $obj = $allobj[$c];
                                $row = null;

                                //$row = AuctionItem::findAll(['auction_id'=>$obj[21],'name' => $obj[8], 'timescanned' => $obj[7], 'realm_id' => $realmrow->id, 'faction' => $factionrow->id]);
                                if ($row == null) {
                                    $row = new AuctionItem();
                                    $cat1 = $obj[2];
                                    $cat2 = $obj[3];
                                    $cat1row = AhMainCat::findOne(['name' => $cat1]);
                                    if ($cat1row == null) {
                                        $cat1row = new AhMainCat();
                                        $cat1row->name = $cat1;
                                        $cat1row->save();
                                    }
                                    $cat2row = AhSubCat::findOne(['name' => $cat2]);
                                    if ($cat2row == null) {
                                        $cat2row = new AhSubCat();
                                        $cat2row->name = $cat2;
                                        $cat2row->save();
                                    }
                                    $slottrans = SlotTranslation::findOne(['id' => $obj[4]]);
                                    $record = [];

                                    $record['cat1'] = $cat1row->id;
                                    $record['cat2'] = $cat2row->id;
                                    $record['item_list'] = $obj[0];
                                    $record['ilvl'] = $obj[1];
                                    $record['slot_id'] = $obj[4];
                                    $record['current_bid'] = $obj[5];
                                    $record['timeleft_id'] = $obj[6];
                                    $record['timescanned'] = $obj[7];
                                    $record['name'] = $obj[8];
                                    $record['icon'] = $obj[9];
                                    $record['stack'] = $obj[10];
                                    $record['quality'] = $obj[11];
                                    $record['level_required'] = $obj[13];
                                    $record['min_bid'] = $obj[14];
                                    $record['bid_up_amount'] = $obj[15];
                                    $record['buyout'] = $obj[16];
                                    $record['previous_bid_amount'] = $obj[17];
                                    $record['user'] = $obj[19];
                                    $record['auction_id'] = $obj[21];
                                    $record['itemid'] = $obj[22];
                                    $record['realm_id'] = $realmrow->id;
                                    $record['faction'] = $factionrow->id;
                                    $saved++;
                                    $batch_records[] =  $record;
                                }
                                $counter++;
                                if(($saved % 500) == 0 && $saved > 0){
                                    \Yii::$app->db->createCommand()->batchInsert('auction_item',array_keys($batch_records[0]),$batch_records)->execute();
                                    $batch_records = [];
                                    echo "inserting 500 records \n";
                                }
                                //echo $totalcount . " " . $counter . " (" . $saved . " saved)\n";
                            }
                            if(count($batch_records) > 0){
                                \Yii::$app->db->createCommand()->batchInsert('auction_item',array_keys($batch_records[0]),$batch_records)->execute();
                                echo "inserting remaining ".count($batch_records)." records \n";
                            }
                        }

                        //file_put_contents($realm."_".$faction."_CSV.txt",$newfile);
                    }
                }
            }
        }
        // this is now removed when we upload a new file
        //unlink($filename);
        echo "done";
        return 0;
    }
}
