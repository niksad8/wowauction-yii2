<?php
/**
 * Created by PhpStorm.
 * User: nikhil
 * Date: 21-Apr-19
 * Time: 03:36
 */

namespace app\models;


use yii\data\ArrayDataProvider;

class ProfessionSpellsProvider extends ArrayDataProvider
{
    /* @var  $model \app\models\ProfessionSpells */
    public $model;
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    public function init()
    {
        $obj = [];
        $spells = $this->model->getAHSpells();
        foreach($spells as $spell){
            if($spell->item != null) {
                $item = $spell->item->getItemPrice();
                if ($item != NULL) {
                    $arr = [];
                    $arr['item'] = \Yii::$app->wowutil->printItem($spell->result_item_id);
                    $arr['item_name'] = $spell->item->name;
                    $arr['quantity'] = $spell->result_item_quantity;
                    $arr['bid_median'] = \Yii::$app->wowutil->printCurrency($item->bid_median);
                    $arr['bid_median_amt'] = $item->bid_median;
                    $arr['buyout_median'] = \Yii::$app->wowutil->printCurrency($item->buyout_median);
                    $arr['buyout_median_amt'] = $item->buyout_median;
                    $arr['quantity_ah'] = $item->quantity;
                    $arr['cost_price'] = \Yii::$app->wowutil->printCurrency($item->cost_price);
                    $arr['cost_price_amt'] = $item->cost_price;
                    $arr['profit'] = \Yii::$app->wowutil->printCurrency($item->buyout_median - $item->cost_price);
                    $arr['profit_amt'] = $item->buyout_median - $item->cost_price;
                    $obj[] = $arr;
                }
            }
        }
        $this->allModels = $obj;
    }
}