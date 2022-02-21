<?php
/* @var $server \app\models\Servers */
 /* @var $realms  \app\models\Realm[] */
$this->title = $server->name;
$summary = \app\models\ScanStats::findBySql("SELECT ss.* from scan_stats ss where realm_id in (select id from realm where server_id='".$server->id."') and `datetime`=(select max(datetime) from scan_stats ss2 where ss2.faction_id=ss.faction_id and ss2.realm_id=ss.realm_id) group by realm_id,faction_id;")->all();
$summary_arr = [];
foreach($summary as $ss){
    $summary_arr[$ss->realm_id][$ss->faction_id] = $ss;
}
?>
    <h1><b><?=$server->name."</b> selected." ?></h1>
    <h3><?=$server->desc ?></h3>

    <div class="card ">
        <div class="card-header">
            <h1>Select your Realm</h1>
        </div>
        <div class="card-body">
            <div class="row">
            <?php
            foreach($realms as $realm){
                ?>
                    <div class="col-sm-6 col-md-3">
                        <div class="card">
                            <div class="card-img" style="padding: 20px">
                                <div class="row">
                                    <div class="col">
                                    <?php
                                    if(isset($summary_arr[$realm->id]) && isset($summary_arr[$realm->id][1])) {
                                        $ss = $summary_arr[$realm->id][1];
                                        $listed = $ss->total_listed;
                                        echo '<span><h3 style="display:inline-block">H : ' . $listed . " </h3> auctions</span><br>
                                        <span>(scanned ".Yii::$app->wowutil->ago($ss->datetime).")</span>";
                                    }
                                    ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                    <?php
                                    if(isset($summary_arr[$realm->id]) && isset($summary_arr[$realm->id][2])) {
                                        $ss = $summary_arr[$realm->id][2];
                                        $listed = $ss->total_listed;
                                        echo '<span><h3 style="display: inline-block">A : ' .$listed. " </h3> auctions</span><br>
                                        <span>(scanned ".Yii::$app->wowutil->ago($ss->datetime).")</span>;";
                                    }
                                    ?>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <h3 class="card-title"><?=$realm->name ?></h3>
                                <p class="card-text">
                                    <a class='btn btn-primary btn-block' href="<?=Yii::$app->urlManager->createUrl(["realm/base",'id'=>$realm->id]); ?>">Go</a>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php
            }
            ?>
            </div>
        </div>
    </div>
    <?php

    if (count($summary) > 0) {
        ?>
        <div class="row">
            <div class="col-12">
                <table width="100%" class="table">
                    <thead>
                    <tr>
                        <th colspan="6" style="align-content: center; text-align: center"><h2>Statistics</h2>
                        </th>
                    </tr>
                    <tr>
                        <th>Realm</th>
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
                            <td><a href="<?=Yii::$app->urlManager->createUrl(["realm/base","id"=>$row->realm_id])?>"><?= $row->realm->name ?></a></td>
                            <td><a href="<?=Yii::$app->urlManager->createUrl(["realm/base","id"=>$row->realm_id,"faction"=>$row->faction_id])?>"><?= $row->faction->name ?></a></td>
                            <td><?= $row->datetime ?></td>
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
    ?>
</div>
