<?php
namespace app\commands;

use app\models\AuctionItem;
use app\models\Factions;
use app\models\ItemBuild;
use app\models\ItemPrices;
use app\models\NpcVendor;
use app\models\Realm;
use app\models\ScanStats;
use app\models\SpellMaster;
use yii\console\Controller;
use yii\console\ExitCode;
use Yii;

class ItemBuildController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
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
    public function actionProcessSales($realmid, $factionid){
        $qry = "SELECT current_bid,timeleft_id, itemid,stack,quantity,min_bid,user,previous_bid_amount,buyout from old_auction_item where faction=? and realm_id=?";
        $qry2 = "SELECT current_bid,timeleft_id, itemid,stack,quantity,min_bid,user,previous_bid_amount,buyout from auction_item where faction=? and realm_id=?";
        $current = Yii::$app->db->createCommand($qry,[$factionid,$realmid])->queryAll();
        $old = Yii::$app->db->createCommand($qry,[$factionid,$realmid])->queryAll();

    }
    public function actionProcessItems($realmid,$faction){
        $realm = Realm::findOne(['id'=>$realmid]);
        $faction = Factions::findOne(['id'=>$faction]);
        if($realm == NULL){
            echo "Server not found!\n";
            return;
        }
        if($faction == NULL){
            echo "faction not found!\n";
            return;
        }

        $items = AuctionItem::findBySql("SELECT * from auction_item where faction='".$faction->id."' and realm_id='".$realm->id."' order by itemid")->all();
        $maxts = Yii::$app->db->createCommand("SELECT max(timescanned) from auction_item where faction='".$faction->id."' and realm_id='".$realm->id."'")->queryScalar();
        $dt = new \DateTime(date('Y-m-d H:i:s',$maxts));
        $dtime = date('Y-m-d H:i:s',$maxts);
        echo "Time set as ".$dtime." \n";
        $max_dt = Yii::$app->db->createCommand("select max(`datetime`) from item_prices where faction_id='".$faction->id."' and realm_id='".$realm->id."';")->queryScalar();
        if($max_dt == null)
            $max_dt = date('Y-m-d H:i:s',$maxts);
        $last_rows = ItemPrices::findBySql("SELECT * from item_prices where faction_id='".$faction->id."' and realm_id='".$realm->id."' and `datetime`='".$max_dt."'")->indexBy('itemid')->all();
        $item_list= [];
        $total_listed = count($items);
        $counter = 0;
        $records = [];
        $calc_data = [];
        $cnt_price_inc = 0;
        $cnt_price_dec =0;
        $calc_data['bid_list'] = [];
        $calc_data['buyout_list'] = [];
        $calc_data['bid_average'] = 0;
        $calc_data['buyout_average'] = 0;
        $avg_price_change = 0;
        $price_change_num = 0;
        $total_amt_bid = 0;
        $total_bid_gold = 0;
        $total_buyout_gold = 0;
        $buyout_quantity = 0;
        $current_itemid = null;
        echo "Starting processing...\n";
        foreach($items as $item){
            if($current_itemid === null)
                $current_itemid = $item->itemid;

            if($current_itemid != $item->itemid || ($total_listed-1) == $counter) {
                echo "adding item mem used ".memory_get_usage()."\n";
                $arr =$calc_data;
                sort($arr['bid_list']);
                sort($arr['buyout_list']);
                $mid = (int)floor($arr['quantity'] / 2);
                $arr['bid_average'] = $arr['bid_average'] / $arr['quantity'];
                $arr['buyout_average'] = $arr['buyout_average'] / $arr['quantity'];
                //$item_price = new ItemPrices();
                $calc_data = $arr;
                $record = [];
                $record['itemid'] = $current_itemid;
                $record['datetime'] = $dt->format("Y-m-d H:i:s");
                $record['bid_mean'] = round($arr['bid_average'], 0);
                $record['bid_median'] = round($arr['bid_list'][$mid], 0);
                $record['bid_min'] = $arr['bid_list'][0];
                if(count($arr['buyout_list']) > 0)
                    $record['buyout_min'] = $arr['buyout_list'][0];
                else {
                    $record['buyout_min'] = 0;
                }
                $record['cost_price'] = 0;
                $record['quantity'] = $arr['quantity'];
                $record['buyout_mean'] = round($arr['buyout_average'], 0);
                if(count($arr['buyout_list']) > 0)
                    $record['buyout_median'] = $arr['buyout_list'][floor($buyout_quantity/2)];
                else
                    $record['buyout_median'] = 0;
                $buyout_quantity = 0;
                $record['realm_id'] = $realm->id;
                $record['faction_id'] = $faction->id;
                if (isset($last_rows[$current_itemid])) {
                    $last_row = $last_rows[$current_itemid];
                    $record['bid_mean_last_compare'] = $last_row->bid_mean > 0?(($arr['bid_average'] - $last_row->bid_mean)/$last_row->bid_mean):1;
                    $record['buyout_mean_last_compare'] = $last_row->buyout_mean > 0?(($arr['buyout_average'] - $last_row->buyout_mean)/$last_row->buyout_mean):1;
                    $record['bid_median_last_compare'] = $last_row->bid_median > 0?(($arr['bid_list'][$mid] - $last_row->bid_median)/$last_row->bid_median):1;
                    $record['buyout_median_last_compare'] = $last_row->buyout_median > 0?(($record['buyout_median'] - $last_row->buyout_median)/$last_row->buyout_median):1;
                    if($record['bid_median_last_compare'] > 0)
                        $cnt_price_inc++;
                    else if($record['bid_median_last_compare'] < 0)
                        $cnt_price_dec++;
                    $price_change_num++;
                    $avg_price_change+=$record['bid_median_last_compare'];

                } else {
                    $record['bid_mean_last_compare'] = 0;
                    $record['buyout_mean_last_compare'] = 0;
                    $record['bid_median_last_compare'] = 0;
                    $record['buyout_median_last_compare'] = 0;
                }

                $calc_data['bid_list'] = null;
                $calc_data['buyout_list'] = null;
                $arr = null;
                $calc_data = [];
                $calc_data['bid_list'] = [];
                $calc_data['buyout_list'] = [];
                $calc_data['bid_average'] = 0;
                $calc_data['buyout_average'] = 0;

                $current_itemid = $item->itemid;
                $item_list[$record['itemid']] = $record;
            }
            $calc_data['bid_average'] += $item->min_bid;
            $calc_data['buyout_average'] += $item->buyout;
            $total_amt_bid += $item->current_bid;
            $total_bid_gold+= $item->min_bid;
            $total_buyout_gold += $item->buyout;
            if(!isset($calc_data['quantity']))
                $calc_data['quantity'] = $item->stack;
            else
                $calc_data['quantity'] += $item->stack;
            for($c=0; $c < $item->stack; $c++){
                $calc_data['bid_list'][] = (int)$item->min_bid / $item->stack;
                if((int)$item->buyout > 0) {
                    $buyout_quantity++;
                    $calc_data['buyout_list'][] = (int)$item->buyout / $item->stack;
                }
            }

            $counter++;
        }
        /*if(count($records) > 0){
            \Yii::$app->db->createCommand()->batchInsert('item_prices',array_keys($records[0]),$records)->execute();
            echo "inserting 500 records \n";
            $records =[];
        }*/
        echo "Processing cost price now\n";
        $total_items = count($item_list);
        $counter = 0;
        foreach($item_list as $item){
            $total_cost = [];
            $builds = ItemBuild::findAll(['result_itemid'=>$item['itemid'],'source_type'=>'spell']);
            //$result_quantity = 0;
            if(count($builds) == 0){
                $builds = ItemBuild::findAll(['result_itemid'=>$item['itemid'],'source_type'=>'vendor']);
            }
            if($builds != NULL){
                foreach($builds as $build) {
                    $cost = 0;
                    //$result_quantity = $build->created_amount;
                    if(isset($item_list[$build->required_itemid])){
                        $cost = $item_list[$build->required_itemid]['buyout_median'] * $build->quantity;
                    }
                    else {
                        $itemprice = ItemPrices::findBySql("SELECT * from item_prices where itemid='".$build->required_itemid."' and `datetime`<='".$dt->format("Y-m-d H:i:s")."' and realm_id='".$realm->id."' and faction_id='".$faction->id."' order by `datetime` desc")->one();
                        if($itemprice == NULL){
                            if($build->requireditem != null){
                                $cost = floor(($build->requireditem->BuyPrice / $build->requireditem->BuyCount) * $build->quantity);
                            }
                            else {
                                echo "ITEM WITH ID ".$build->required_itemid." NOT FOUND\n";
                            }
                        }
                        else {
                            $cost = ($itemprice->buyout_median>0?$itemprice->buyout_median:$itemprice->bid_median) * $build->quantity;
                            $item_list[$build->required_itemid] = $itemprice->attributes;
                            $item_list[$build->required_itemid]['dont_insert'] = 1;
                        }
                    }
                    if(!isset($total_cost[$build->source_id]))
                        $total_cost[$build->source_id] = $cost;
                    else
                        $total_cost[$build->source_id] += $cost;
                }
            }
            $lowest_cost = null;
            foreach($total_cost as $idx => $cc){
                if($lowest_cost === null){
                    $lowest_cost = $cc;
                }
                if($lowest_cost > $cc)
                    $lowest_cost= $cc;
            }
            $item_list[$item['itemid']]['cost_price'] = round($lowest_cost,0);
            //$item_list[$item->itemid]->save();
            $counter++;
            //echo "Cost Price Item :".$item['itemid']." $lowest_cost done ($counter of $total_items) \n";
        }
        echo "All Processing Done Now Saving";
        $records = [];
        $insert_count = 0;
        foreach($item_list as $idx => $item){
            if(!isset($item['dont_insert'])){
                $records[] = $item;
                $insert_count++;
            }
            else
                echo $item['itemid']." skipped\n";
            if($insert_count > 500){
                \Yii::$app->db->createCommand()->batchInsert('item_prices',array_keys($records[0]),$records)->execute();
                echo "inserting 500 records \n";
                $records =[];
                $insert_count = 0;
            }
        }
        if(count($records) > 0){
            \Yii::$app->db->createCommand()->batchInsert('item_prices',array_keys($records[0]),$records)->execute();
            echo "inserting last records \n";
            $records =[];
        }
        echo "Saving Summary ... \n";
        $summary = new ScanStats();
        $summary->realm_id = $realmid;
        $summary->faction_id = $faction->id;
        $summary->datetime = $dtime;
        $summary->total_items = $total_items;
        $summary->total_listed = $total_listed;
        $summary->cnt_prices_decreased = $cnt_price_dec;
        $summary->cnt_prices_increased = $cnt_price_inc;
        $summary->total_amt_bid = $total_amt_bid;
        $summary->total_bid_gold = $total_bid_gold;
        $summary->total_buyout_gold = $total_buyout_gold;
        $summary->avg_price_change = $avg_price_change> 0?$avg_price_change/$price_change_num:0;
        $summary->save();
        echo "ALL DONE!!!";
    }
    private function getFromCsv($arr,$value){
        for($i=0; $i < count($arr); $i++){
            if($arr[$i][0] == $value)
                return $arr[$i];
        }
        return null;
    }
    public function actionSetupVendors(){
        $extended_cost = $this->parse_csv(file_get_contents(Yii::$app->getBasePath().'/dbc/ItemExtendedCost.dbc.csv'));
        $itemlist = NpcVendor::find()->all();
        foreach ($itemlist as $item){
            if($item->itemr != null){
                if($item->ExtendedCost != 0){
                    $row = $this->getFromCsv($extended_cost,$item->ExtendedCost);
                    if($row != NULL){
                        $item_ids =[];
                        $item_qtys = [];
                        if($row[4] != 0) {
                            $item_ids[] = $row[4];
                            $item_qtys[] = $row[9];
                        }
                        if($row[5] != 0){
                            $item_ids[] = $row[5];
                            $item_qtys[] = $row[10];
                        }

                        if($row[6] != 0){
                            $item_ids[] = $row[6];
                            $item_qtys[] = $row[11];
                        }

                        if($row[7] != 0){
                            $item_ids[] = $row[7];
                            $item_qtys[] = $row[12];
                        }

                        if($row[8] != 0){
                            $item_ids[] = $row[8];
                            $item_qtys[] = $row[13];
                        }

                        for($c=0; $c < count($item_ids); $c++){
                            $ib = ItemBuild::findOne(['result_itemid'=>$item->item,'required_itemid'=>$item_ids[$c],'source_type'=>'vendor','source_id'=>$item->entry]);
                            if($ib == NULL) {
                                $ib = new ItemBuild();
                                $ib->result_itemid = $item->item;
                                $ib->required_itemid = $item_ids[$c];
                                $ib->quantity = $item_qtys[$c];
                                $ib->source_type = 'vendor';
                                $ib->source_id = $item->entry;
                                $ib->save();
                                echo "NPC ".$item->creature->name." added for ".$item->itemr->name." qty ".$item_qtys[$c]."\n";
                            }
                        }
                    }
                }
                else {
                    $ib = new ItemBuild();
                    $ib->result_itemid = $item->item;
                    $ib->required_itemid = 0;
                    $ib->quantity = $item_qtys[$c];
                    $ib->source_type = 'vendor';
                    $ib->source_id = $item->entry;
                    $ib->save();
                }
            }
        }
    }
    public function actionSetupSpells()
    {
        $spellicons = $this->parse_csv(file_get_contents(Yii::$app->getBasePath().'/dbc/SpellIcon.dbc.csv'));
        $spelldbc = $this->parse_csv(file_get_contents(Yii::$app->getBasePath().'/dbc/Spell.dbc.csv'));
        for($i=1; $i < count($spelldbc); $i++){
            if($spelldbc[$i][0] != '') {
                $display_icon = $spelldbc[$i][19];
                $display_icon2 = $spelldbc[$i][20];
                $spell_check = SpellMaster::findOne(['id'=>$spelldbc[$i][0]]);
                if($spell_check == null) {
                    $spell = new SpellMaster();
                    $spell->id = $spelldbc[$i][0];
                    $spell->name = $spelldbc[$i][136];
                    $spell->desc = $spelldbc[$i][170];
                    $spell->icon = $spellicons[$display_icon + 1][1];
                    $spell->type = 'spell';
                    $spell->created_amount = $spelldbc[$i][74] + $spelldbc[$i][80];
                    $spell->save();
                    echo "added (".$spelldbc[$i][0].") ".$spelldbc[$i][136]."\n";
                }
                else {
                    $spell_check->created_amount = $spelldbc[$i][74] + $spelldbc[$i][80];
                    $spell_check->save();
                }
                echo "(".$spelldbc[$i][0].") ".$spelldbc[$i][136]."\n";
                $ib = ItemBuild::findOne(['source_type'=>'spell','source_id'=>$spelldbc[$i][0]]);
                if($ib == NULL){
                    $result_item = $spelldbc[$i][107];
                    $items = [];
                    $qty = [];
                    for($x =52; $x < 60; $x++){
                        if($spelldbc[$i][$x] != 0) {
                            $items[] = $spelldbc[$i][$x];
                            $qty[] = $spelldbc[$i][$x+8];
                        }
                    }
                    for($y = 0; $y < count($items); $y++){
                        $ib = new ItemBuild();
                        $ib->result_itemid = $result_item;
                        $ib->required_itemid = $items[$y];
                        $ib->quantity = $qty[$y];
                        $ib->source_type = 'spell';
                        $ib->source_id = $spelldbc[$i][0];
                        $ib->save();
                    }
                }
            }
        }
    }
}
