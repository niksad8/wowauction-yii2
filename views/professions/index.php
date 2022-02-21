<?php
 /*  @var $model \app\models\Professions */
 /*  @var $realm \app\models\Realm */
 /*  @var $faction \app\models\Factions */

?>
<style>

</style>
<h1><?=$model? $model->name:""; ?></h1>

<?php if($realm == NULL || $faction == NULL){ ?>
    <hr>
    <form method="get" action="<?=\Yii::$app->urlManager->createUrl("professions/index") ?>">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>Select Realm and Filter</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-6">
                        <b>Realm</b><br>
                        <select name="realm" class='form-control' id="realm">
                            <?php
                                $realms = \app\models\Realm::find()->all();
                                foreach($realms as $r){
                                    echo "<option value='".$r->id."'>".$r->name."</option>\n";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-xs-6">
                        <b>Faction</b><br>
                        <select name="faction" class='form-control'  id="faction">
                            <?php
                            $realms = \app\models\Factions::find()->all();
                            foreach($realms as $r){
                                echo "<option value='".$r->id."'>".$r->name."</option>\n";
                            }
                            ?>
                        </select>

                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <br>
                        <button type="submit" class="btn btn-block btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <hr>
<?php } else { ?>
    <h1><?="<b><a href='".YIi::$app->urlManager->createUrl(["servers/base",'id'=>$realm->server_id])."'>".$realm->server->name."</a></b> server - <i><a href='".YIi::$app->urlManager->createUrl(["realm/base",'id'=>$realm->id])."'>".$realm->name."</a></i> realm ".($faction != null?"<i>".$faction->name."</i> faction":"") ?></h1>
    <hr>
    <div class="row">
        <?php
        $proffs = \app\models\Professions::find()->all();
        foreach($proffs as $proff){
            $s = "";
            if($model && $proff->id == $model->id)
                $s = "active";
            echo "<div class='col-md-1 col-xs-2'><a title='".$proff->name."' href='".Yii::$app->urlManager->createUrl(["professions/index",'id'=>$proff->id])."'><div class='$s bg-image trade-icon' style='background-image:url(/images/ICONS/".$proff->icon_name.".PNG)'>
            </div></a></div>";
        }
        ?>
    </div>
    <hr>
    <br><br>
    <?php /*
    <div class="row">
        <div class="col-xs-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Result Quantity</th>
                        <th>Bid Median On AH</th>
                        <th>BO Median On AH</th>
                        <th>Quantity On AH</th>
                        <th>Cost Price</th>
                        <th>Profit</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $spells = $model->spells;
                foreach($spells as $spell){
                    if($spell->item != null) {
                        $item = $spell->item->getItemPrice();
                        if ($item != NULL) {
                            echo "<tr>\n";
                            echo "<td>" . \Yii::$app->wowutil->printItem($spell->result_item_id) . "</a></td>\n";
                            echo "<td>" . $spell->result_item_quantity . "</td>\n";
                            echo "<td>" . \Yii::$app->wowutil->printCurrency($item->bid_median) . "</td>\n";
                            echo "<td>" . \Yii::$app->wowutil->printCurrency($item->buyout_median) . "</td>\n";
                            echo "<td>" . $item->quantity . "</td>\n";
                            echo "<td>" . \Yii::$app->wowutil->printCurrency($item->cost_price) . "</td>\n";
                            echo "<td>" . \Yii::$app->wowutil->printCurrency($item->buyout_median - $item->cost_price) . "</td>\n";
                            echo "</tr>\n";
                        }
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
 */ ?>
<?php } ?>

<?php
    $sort = new \yii\data\Sort();
    $sort->attributes = [
            'item'=>['asc'=>['item_name'=>SORT_ASC],'desc'=>['item_name'=>SORT_DESC]],
            'quantity'=>['asc'=>['quantity'=>SORT_ASC],'desc'=>['quantity'=>SORT_DESC]],
            'bid_median'=>['asc'=>['bid_median_amt'=>SORT_ASC],'desc'=>['bid_median_amt'=>SORT_DESC]],
            'buyout_median'=>['asc'=>['buyout_median_amt'=>SORT_ASC],'desc'=>['buyout_median_amt desc'=>SORT_DESC]],
            'quantity_ah'=>['asc'=>['quantity_ah'=>SORT_ASC],'desc'=>['quantity_ah'=>SORT_DESC]],
            'cost_price'=>['asc'=>['cost_price_amt'=>SORT_ASC],'desc'=>['cost_price_amt'=>SORT_DESC]],
            'profit'=>['asc'=>['profit_amt'=>SORT_ASC],'desc'=>['profit_amt'=>SORT_DESC]]
        ];
    $dp = new \app\models\ProfessionSpellsProvider(['model'=>$model,'pagination'=>false,'sort'=>$sort]);
    echo \yii\grid\GridView::widget([
        'dataProvider' => $dp,

        'columns' => [
            'item:raw:Item',
            'quantity:text:Result Quantity',
            'bid_median:raw:Bid Median On AH',
            'buyout_median:raw:BO Median On AH',
            'quantity_ah:text:Quantity On AH',
            'cost_price:raw:Cost Price',
            'profit:raw:Profit'
        ]
    ]);
?>