<?php
/**
 * Created by PhpStorm.
 * User: nikhil
 * Date: 08-Aug-19
 * Time: 09:26
 */

namespace app\commands;


use app\models\GuildLogCharacters;
use app\models\GuildLogEvents;
use app\models\GuildLogSettings;
use yii\console\Controller;

class GuildLoggerController extends Controller
{
    private $base_url = "http://armory.warmane.com/guild/%guild%/%realm%/summary/";
    private $base_api_url = "http://armory.warmane.com/api/guild/%guild%/%realm%/summary";
    public function actionIndex() {

        while(1) {
            $all_guilds = GuildLogSettings::find()->all();
            $content_cache = [];
            $api_cache = [];
            foreach ($all_guilds as $guild){
                $url = str_replace("%guild%",urlencode($guild->guild_name),$this->base_url);
                $url = str_replace("%realm%",urlencode($guild->realm_name),$url);
                $api_url = str_replace("%guild%",urlencode($guild->guild_name),$this->base_api_url);
                $api_url = str_replace("%realm%",urlencode($guild->realm_name),$api_url);

                if(!isset($content_cache[$guild->guild_name]) || $content_cache[$guild->guild_name] != null){
                    $contents = @file_get_contents($url);
                    $content_cache[$guild->guild_name] = $contents;
                    $api_contents = @file_get_contents($api_url);
                    $api_cache[$guild->guild_name] = $api_contents;
                }
                else {
                    $contents = $content_cache[$guild->guild_name];
                    $api_contents = $api_cache[$guild->guild_name];
                }

                $api_json = json_decode($api_contents,true);

                $online_status = [];
                if($api_json != null && !isset($api_json['error'])){
                    $roster = $api_json['roster'];
                    if($roster != null) {
                        for ($c = 0; $c < count($roster); $c++) {
                            $api_char = $roster[$c];
                            if ($api_char['online'])
                                $online_status[$api_char['name']] = 1;
                        }
                    }
                }
                $cnt_check = GuildLogCharacters::find()->where(['guild_id'=>$guild->guild_id])->count();
                if($contents != ""){
                    $doc = new \DOMDocument();
                    @$doc->loadHTML($contents);
                    $xpath = new \DOMXPath($doc);
                    $nodes = $xpath->query('//tbody[@id="data-table-list"]/tr');
                    $char_names = [];
                    for($x=0; $x < $nodes->length; $x++){
                        $node = $nodes[$x];
                        $tds = $xpath->query('td',$node);
                        $name = "";
                        $class = "";
                        $race = "";
                        $level = "";
                        $rank = "";
                        $is_online = 0;
                        for($c=0; $c < $tds->length; $c++) {
                            $td = $tds[$c];
                            if ($c == 0) { // name
                                $name = trim($td->textContent);
                            } else if ($c == 1) { // race
                                $img = $xpath->query("span/img", $td);
                                if ($img) {
                                    $race = $img[0]->attributes->getNamedItem("alt")->nodeValue;
                                }
                            } else if ($c == 2) { // class
                                $img = $xpath->query("span/img", $td);
                                if ($img) {
                                    $class = $img[0]->attributes->getNamedItem("alt")->nodeValue;
                                }
                            } else if ($c == 4) { // level
                                $level = trim($td->textContent);
                            } else if ($c == 5) { // rank
                                $rank = trim($td->textContent);
                            }
                        }
                        if($name != ""){
                            $char_names[] = "'".$name."'";
                            $is_online = (int)isset($online_status[$name]);
                            echo $name ."' $race $class $level $rank \n";
                            $glc = GuildLogCharacters::find()->where(['guild_id'=>$guild->guild_id,'char_name'=>$name])->andWhere('left_at is null')->one();
                            if($glc == NULL){
                                echo "new \n";
                                $glc = new GuildLogCharacters();
                                $glc->guild_id = $guild->guild_id;
                                $glc->char_name = $name;
                                $glc->is_online = $is_online;
                                $glc->joined_at = date('Y-m-d H:i:s');
                                $glc->rank = $rank;
                                $glc->char_level = $level;
                                $glc->race = $race;
                                $glc->class_name = $class;
                                if (!$glc->save()) {
                                    print_r($glc->getErrors());
                                }

                                if($cnt_check > 0) {
                                    $gle = new GuildLogEvents();
                                    $gle->guild_id = $guild->guild_id;
                                    $gle->channel_id = $guild->channel_id;
                                    $gle->char_name = $name;
                                    $gle->datetime = date('Y-m-d H:i:s');
                                    $gle->sent = 0;
                                    $gle->extra_data = '';
                                    $gle->operation = 'JOINED';
                                    $gle->save();
                                }
                            }
                            else {
                                if($glc->rank != $rank){
                                    echo "Rank changed ".$glc->rank." $rank !\n";
                                    $gle = new GuildLogEvents();
                                    $gle->guild_id = $guild->guild_id;
                                    $gle->channel_id = $guild->channel_id;
                                    $gle->char_name = $name;
                                    $gle->datetime = date('Y-m-d H:i:s');
                                    $gle->sent = 0;
                                    $gle->operation = 'RANK_CHANGED';
                                    $gle->extra_data = json_encode(['new_rank'=>$rank,'old_rank'=>$glc->rank]);
                                    $gle->save();
                                    $glc->rank = $rank;
                                    $glc->save();
                                }
                                if($is_online != $glc->is_online){
                                    $glc->is_online = $is_online;
                                    if(!$is_online){
                                        $glc->last_seen = date('Y-m-d H:i:s');
                                    }
                                    $glc->save();
                                }
                            }
                        }
                    }
                    if(count($char_names) > 0) {
                        echo "processing left chars\n";
                        $qry = "select * from guild_log_characters where left_at is null and guild_id='" . $guild->guild_id . "' and char_name not in (" . implode(",", $char_names) . ")";
                        $left_chars = GuildLogCharacters::findBySql($qry)->all();
                        foreach ($left_chars as $char) {
                            echo $char->char_name . " has left \n";

                            $gle = new GuildLogEvents();
                            $gle->guild_id = $guild->guild_id;
                            $gle->channel_id = $guild->channel_id;
                            $gle->char_name = $char->char_name;
                            $gle->datetime = date('Y-m-d H:i:s');
                            $gle->sent = 0;
                            $gle->extra_data = '';
                            $gle->operation = 'LEFT';
                            $gle->save();

                            $char->left_at = date('Y-m-d H:i:s');
                            $char->save();
                        }
                    }
                }
            }
            sleep(60); // check every minute
        }
    }
}