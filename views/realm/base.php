<?php
$this->title = $realm->name." ".$realm->server->name;
?>
<div class="row">
    <div class="col">
        <h1><?="<b><a href='".YIi::$app->urlManager->createUrl(["servers/base",'id'=>$realm->server_id])."'>".$realm->server->name."</a></b> server - <i><a href='".YIi::$app->urlManager->createUrl(["realm/base",'id'=>$realm->id])."'>".$realm->name."</a></i> realm ".($selected_faction != null?"<i>".$selected_faction->name."</i> faction":"") ?></h1>
        <h3><?=$realm->desc; ?></h3>
        <?php if($selected_faction == null) { ?>
            <div class="card">
                <div class="card-header">
                    <h3>Select Factions</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php
                        $factions = \app\models\Factions::find()->where(['id'=>[1,2]])->all();
                        foreach ($factions as $faction) {
                            ?>
                            <div class="col-sm-6 col-md-4">
                                <div class="thumbnail">
                                    <a href="<?= Yii::$app->urlManager->createUrl(["realm/base", 'id' => $realm->id, 'faction' => $faction->id]); ?>">
                                        <img src="/images/factions/<?= $faction->name ?>.png"
                                         style='height:400px; width:auto;' alt="<?= $faction->name ?>">
                                    </a>
                                    <div class="caption">
                                        <center><h3><?= $faction->name ?></h3></center>
                                        <p>
                                            <a class='btn btn-primary'
                                               href="<?= Yii::$app->urlManager->createUrl(["realm/base", 'id' => $realm->id, 'faction' => $faction->id]); ?>"> Go </a>
                                            <?php
                                            $fname = $realm->name."_".$faction->id."_aucdata";
                                            $fullpath = \Yii::$app->basePath.DIRECTORY_SEPARATOR."auc-data".DIRECTORY_SEPARATOR.$fname;

                                            if(file_exists($fullpath)) { ?>
                                                <a class='btn btn-success' href="<?=Yii::$app->urlManager->createUrl(["realm/getfile",'id'=>$realm->id,'faction'=>$faction->id]); ?>" title="This is the auctioneer scanned data you can use"><span class="fa fa-download"></span> Download Data</a>
                                            <?php } ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php
            $summary = \app\models\ScanStats::findBySql("SELECT ss.* from scan_stats ss where realm_id='".$realm->id."' and `datetime`=(select max(datetime) from scan_stats ss2 where ss2.faction_id=ss.faction_id and ss2.realm_id=ss.realm_id) group by realm_id,faction_id;")->all();
            if (count($summary) > 0) {
                ?>
                <div class="row">
                    <div class="col-12">
                        <table width="100%" class="table">
                            <thead>
                            <tr>
                                <th colspan="5" style="align-content: center; text-align: center"><h2>Statistics</h2>
                                </th>
                            </tr>
                            <tr>
                                <th>Faction</th>
                                <th>Last Scanned</th>
                                <th>Number Of Items</th>
                                <th>Total Auctions</th>
                                <th>Price Trend</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($summary as $row) { ?>
                                <tr>
                                    <td><a href="<?=Yii::$app->urlManager->createUrl(["realm/base","id"=>$realm->id,"faction"=>$row->faction_id])?>"><?= $row->faction->name ?></a></td>
                                    <td><?= Yii::$app->wowutil->ago($row->datetime) ?></td>
                                    <td><?= $row->total_items ?></td>
                                    <td><?= $row->total_listed ?></td>
                                    <td><?= ($row->avg_price_change > 0 ? "<span class='text-success'><div class='glyphicon glyphicon-arrow-up text-success'></div> Rising</span>" : (($row->avg_price_change < 0) ? "<span class='text-danger'><div class='glyphicon glyphicon-arrow-down'></div> Falling</span>" : "Stable")) ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php }
        }
        else {
            $summary = \app\models\ScanStats::find()->where(['realm_id'=>$realm->id,'faction_id'=>$selected_faction->id])->orderBy('`datetime` DESC')->one();
            if($summary != NULL){
            ?>
            <div class="row">
                <div class="col-1">
                </div>
                <div class="col-10">
                    <table width="100%" class="table">
                        <thead>
                        <tr><th colspan="2" style="align-content: center; text-align: center"><h2>Statistics</h2></th></tr>
                        </thead>
                        <tbody>
                            <tr><td>Last Scanned </td><td><?=date('d-m-Y H:i:s',strtotime($summary->datetime)); ?>(<?=Yii::$app->wowutil->ago($summary->datetime) ?>)</td></tr>
                            <tr><td>Number Of Items </td><td><?=$summary->total_items ?></td></tr>
                            <tr><td>Total Auctions</td><td><?=$summary->total_listed ?></td></tr>
                            <tr>
                                <td>Price Trend </td>
                                <td><?=($summary->avg_price_change>0?"<span class='text-success'><div class='glyphicon glyphicon-arrow-up text-success'></div> Rising</span>":(($summary->avg_price_change < 0)?"<span class='text-danger'><div class='glyphicon glyphicon-arrow-down'></div> Falling</span>":"Stable")) ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
                <hr>
                <center><h4>Professions</h4></center>
                <div class="row">
                    <?php
                    $proffs = \app\models\Professions::find()->all();
                    foreach($proffs as $proff){
                        echo "<div class='col-md-1 col-xs-2'><a title='".$proff->name."' href='".Yii::$app->urlManager->createUrl(["professions/index",'id'=>$proff->id,'faction'=>$selected_faction->id,'realm'=>$realm->id])."'><div class='bg-image trade-icon' style='background-image:url(/images/ICONS/".$proff->icon_name.".PNG)'>
                        </div></a></div>";
                    }
                    ?>
                </div>
                <hr>
                <div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-pills" role="tablist">
                        <li role="presentation" class="nav-item"><a class="nav-link active" href="#all" aria-controls="all" role="tab" data-toggle="tab">All Items</a></li>
                        <li role="presentation" class="nav-item"><a class="nav-link" href="#craft_profit" aria-controls="craft_profit" role="tab" data-toggle="tab">50 Profitable Craftable Items</a></li>
                        <li role="presentation" class="nav-item"><a class="nav-link" href="#valuable" aria-controls="valuable" role="tab" data-toggle="tab">Highest Valued Items</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="all">
                            <?php
                            $max_dt = \app\models\ItemPrices::findBySql("SELECT max(datetime) from item_prices where realm_id='".$realm->id."' and faction_id='".$selected_faction->id."';")->scalar();
                            $scansearch = new \app\models\ItemPricesSearch();
                            $scansearch->faction_id = $selected_faction->id;
                            $scansearch->realm_id = $realm->id;
                            if($max_dt != null)
                                $scansearch->datetime =$max_dt;
                            ?>
                            <?= \yii\grid\GridView::widget([
                                'dataProvider' => $scansearch->search(Yii::$app->request->queryParams),
                                'filterModel' => $scansearch,
                                'tableOptions'=>['class'=>'table table-bordered'],
                                'pager'=>[
                                    'options' => ['class' => 'pagination'],
                                    'firstPageCssClass' => 'first',
                                    'lastPageCssClass' => 'last',
                                    'prevPageCssClass' => 'prev',
                                    'nextPageCssClass' => 'next',
                                    'activePageCssClass' => 'active',
                                    'disabledPageCssClass' => 'disabled',
                                    'pageCssClass'=>'page-item',
                                    'linkContainerOptions'=>['class'=>'page-link']
                                ],
                                'columns' => [
                                    ['attribute'=>'itemid','format' => 'raw','value'=>function($data){ return Yii::$app->wowutil->printItem($data->itemid,$data->faction_id,$data->realm_id);}],
                                    ['attribute'=>'bid_median','format' => 'raw','value'=>function($data){return Yii::$app->wowutil->printCurrency($data->bid_median);}],
                                    ['attribute'=>'buyout_median','format' => 'raw','value'=>function($data){return Yii::$app->wowutil->printCurrency($data->buyout_median);}],
                                    ['attribute'=>'bid_mean','format' => 'raw','value'=>function($data){return Yii::$app->wowutil->printCurrency($data->bid_mean);}],
                                    ['attribute'=>'buyout_mean','format' => 'raw','value'=>function($data){return Yii::$app->wowutil->printCurrency($data->buyout_mean);}],
                                    'quantity',
                                    ['attribute'=>'cost_price','format' => 'raw','value'=>function($data){return Yii::$app->wowutil->printCurrency($data->cost_price);}],
                                    ['attribute'=>'buyout_median_last_compare','label'=>'Delta','format' => 'raw','value'=>function($data){return Yii::$app->wowutil->printTrend($data->buyout_median_last_compare);}],
                                ],
                            ]); ?>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="craft_profit">
                            <?php
                            $rows = \app\models\ItemPrices::findBySql("SELECT * from item_prices where realm_id='".$realm->id."' and faction_id='".$selected_faction->id."' and `datetime`='".$max_dt."' and cost_price>0 order by (buyout_median-cost_price) desc limit 50")->all();
                            if(count($rows) > 0) {
                                echo "<table class='table'>
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Bid Median</th>
                                                <th>Buyout Median</th>
                                                <th>Bid Mean</th>
                                                <th>Buyout Mean</th>
                                                <th>Quantity</th>
                                                <th>Cost Price</th>
                                                <th>Profit</th>
                                                <th>Delta</th>
                                            </tr>
                                        </thead><tbody>";
                                foreach ($rows as $row) {
                                    ?>
                                    <tr>
                                        <td><?=Yii::$app->wowutil->printItem($row->itemid); ?></td>
                                        <td><?=Yii::$app->wowutil->printCurrency($row->bid_median) ?></td>
                                        <td><?=Yii::$app->wowutil->printCurrency($row->buyout_median) ?></td>
                                        <td><?=Yii::$app->wowutil->printCurrency($row->bid_mean) ?></td>
                                        <td><?=Yii::$app->wowutil->printCurrency($row->buyout_mean) ?></td>
                                        <td><?=$row->quantity ?></td>
                                        <td><?=Yii::$app->wowutil->printCurrency($row->cost_price) ?></td>
                                        <td><?=Yii::$app->wowutil->printCurrency($row->buyout_median - $row->cost_price) ?></td>
                                        <td><?=Yii::$app->wowutil->printTrend($row->buyout_median_last_compare) ?></td>
                                    </tr>
                                    <?php
                                }
                                echo "</tbody></table>";
                            }
                            ?>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="valuable">
                            <?php
                            $rows = \app\models\ItemPrices::findBySql("SELECT * from item_prices where realm_id='".$realm->id."' and faction_id='".$selected_faction->id."' and `datetime`='".$max_dt."' order by (buyout_median) desc limit 50")->all();
                            if(count($rows) > 0) {
                                echo "<table class='table'>
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Bid Median</th>
                                                <th>Buyout Median</th>
                                                <th>Bid Mean</th>
                                                <th>Buyout Mean</th>
                                                <th>Quantity</th>
                                                <th>Cost Price</th>
                                                <th>Profit</th>
                                                <th>Delta</th>
                                            </tr>
                                        </thead><tbody>";
                                foreach ($rows as $row) {
                                    ?>
                                    <tr>
                                        <td><?=Yii::$app->wowutil->printItem($row->itemid); ?></td>
                                        <td><?=Yii::$app->wowutil->printCurrency($row->bid_median) ?></td>
                                        <td><?=Yii::$app->wowutil->printCurrency($row->buyout_median) ?></td>
                                        <td><?=Yii::$app->wowutil->printCurrency($row->bid_mean) ?></td>
                                        <td><?=Yii::$app->wowutil->printCurrency($row->buyout_mean) ?></td>
                                        <td><?=$row->quantity ?></td>
                                        <td><?=Yii::$app->wowutil->printCurrency($row->cost_price) ?></td>
                                        <td><?=Yii::$app->wowutil->printCurrency($row->buyout_median - $row->cost_price) ?></td>
                                        <td><?=Yii::$app->wowutil->printTrend($row->buyout_median_last_compare) ?></td>
                                    </tr>
                                    <?php
                                }
                                echo "</tbody></table>";
                            }
                            ?>
                        </div>
                    </div>

                </div>
        <?php }
        } ?>
    </div>
</div>
