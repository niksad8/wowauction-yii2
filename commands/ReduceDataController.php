<?php


namespace app\commands;


use app\models\ItemPrices;
use yii\console\Controller;
use yii\console\ExitCode;

class ReduceDataController extends Controller
{
    public function actionIndex($fromdate = "", $todate = "")
    {
        if($fromdate == "")
            $fromdate = date('Y-m-01');
        if($todate == "")
            $todate = date('Y-m-d');
        echo "Reducing data from $fromdate to $todate \n";
        $fdate = new \DateTime($fromdate);
        $tdate = new \DateTime(($todate));
        $diff = $fdate->diff($tdate);
        while(!$diff->invert){
            echo "Processing ".$fdate->format("Y-m-d")."\n";
            $item_query = "SELECT itemid,realm_id,faction_id,`datetime` from item_prices where `datetime`>='".$fdate->format('Y-m-d')." 00:00:00' and `datetime`<='".$fdate->format("Y-m-d")." 23:59:59' order by itemid,realm_id,faction_id,`datetime`;";
            $rows = \Yii::$app->db->createCommand($item_query)->queryAll();
            echo count($rows)." found on ".$fdate->format('Y-m-d')."\n";
            $counter = 0;
            $realm_id = 0;
            $faction_id = 0;
            $item_id = 0;
            $item_datetimes = [];
            $wheres = [];
            $where_cnt = 0;
            foreach($rows as $row){
                if($item_id == 0)
                    $item_id =  $row['itemid'];
                if($realm_id == 0)
                    $realm_id = $row['realm_id'];
                if($faction_id == 0)
                    $faction_id = $row['faction_id'];

                if($faction_id != $row['faction_id'] || $realm_id != $row['realm_id'] || $item_id != $row['itemid']){
                    $dt_string = '';
                    for($i=0; $i < count($item_datetimes)-1; $i++){
                        $dt_string .= '\''.$item_datetimes[$i].'\''; // delete everything but the last one
                        if($i < count($item_datetimes)-2)
                            $dt_string .= ',';
                    }

                    //$dt_string .= '\'';
                    $where_str = "";
                    if($dt_string != ""){
                        $where_str = "(itemid=".$item_id." and realm_id=$realm_id and faction_id=$faction_id";
                        $where_str .=  " and `datetime` in (".$dt_string.")";
                        $where_str.= ")";
                    }
                    if(count($wheres) >= 100){
                        $qry = "DELETE FROM item_prices where ".implode(" or ",$wheres);
                        $wheres = [];
                        echo "deleteing 100 first";
                        \Yii::$app->db->createCommand($qry)->execute();
                    }
                    if($where_str != "")
                        $wheres[] = $where_str;
                    $item_datetimes = [];
                    $item_datetimes[] = $row['datetime'];
                }
                else {
                    $item_datetimes[] = $row['datetime'];
                }
                $item_id =  $row['itemid'];
                $realm_id = $row['realm_id'];
                $faction_id = $row['faction_id'];
            }
            if(count($wheres) > 0){
                $qry = "DELETE FROM item_prices where ".implode(" or ",$wheres);
                $wheres = [];
                \Yii::$app->db->createCommand($qry)->execute();
            }
            $fdate->modify('+1 day');
            $diff = $fdate->diff($tdate);

        }
        return ExitCode::OK;
    }

}