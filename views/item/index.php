<?php
/* @var $this yii\web\View */
/* @var $item \app\models\ItemTemplate */
/* @var $faction \app\models\Factions */
/* @var $realm \app\models\Realm */
$title = $item->name." Price Analysis on ";
if($realm != NULL && $faction != NULL)
    $title .= $realm->name."[".$realm->server->name."](".$faction->name.")";
$this->title = $title;
if($faction == null || $realm == null){
    ?>
    <div class="card card-default">
        <div class="card-header">
            <h3>Select your realm / faction</h3>
        </div>
        <div class="card-body">
            <?php
            if($realm == null){
                ?>
                <div class="row">
                    <div class="col"><b>Please select your realm : </b></div>
                    <div class="col"><?php
                        $realms = \app\models\Realm::findBySql("select * from realm order by server_id;")->all();
                        $outs = [];
                        foreach($realms as $realm_opt){
                            $outs[$realm_opt->server->name][] = ['id'=>$realm_opt->id,'name'=>$realm_opt->name];
                        }
                        echo "<select id='realm' class='form-control'>\n";
                        foreach($outs as $out => $val){
                            echo "<optgroup label='".$out."'>";
                            for($i =0; $i < count($val); $i++){
                                echo "<option value='".$val[$i]['id']."'>".$val[$i]['name']."</option>";
                            }
                            echo "</optgroup>";
                        }
                        echo "</select>\n";
                        ?></div>
                </div>
                <?php
            }
            if($faction == null){
                ?>
                <div class="row">
                    <div class="col"><b>Please select your faction : </b></div>
                    <div class="col"><?php
                        echo "<select id='faction' class='form-control'>\n";
                        $factions = \app\models\Factions::find()->all();
                        for($i=0; $i < count($factions); $i++){
                            echo "<option value='".$factions[$i]->id."'>".$factions[$i]->name."</option>";
                        }
                        echo "</select>\n";
                        ?></div>
                </div>
                <?php
            }

            ?>
            <div class="card-footer">
                <div class='row'>
                    <div class="col"></div>
                    <div class="col"><button onclick="gotoitem()" type="button" class="btn btn-primary btn-block">Save Settings</button></div>
                    <div class="col"></div>
                </div>
            </div>
        </div>
    </div>

    <?php
}
?>
<h1>Item Statistic</h1>
<?php if($realm != null) {
    echo "<h2>Realm :<a href='".Yii::$app->urlManager->createUrl(["realm/base",'id'=>$realm->id])."'>".$realm->name."</a></h2>";
     if($faction != null) {
        echo "<h2>Faction :".$faction->emblemCode(50,50);
        $factions = \app\models\Factions::find()->where('id!='.$faction->id.' and id!=3')->all();
        foreach($factions as $fac) {
            echo "<a href='".Yii::$app->urlManager->createUrl(["item/index",'id'=>$item->entry,'realm'=>$realm->id,'faction'=>$fac->id])."'>".$fac->emblemCode(30,30)."</a>";
        }
        echo "</h2>";
    }
}
?>
<br>
<center>
    <h1><?=Yii::$app->wowutil->printItem($item->entry) ?></h1>
</center>
<?php if($faction != NULL && $realm != NULL){
    $qry = "SELECT * from item_prices where itemid='".$item->entry."' and faction_id='".$faction->id."' and realm_id='".$realm->id."' order by `datetime` desc limit 1";
    $price_row = \app\models\ItemPrices::findBySql($qry)->one();

    $qry = "SELECT min(buyout_min) as min_bo,
       min(bid_min) as min_bid,
       min(bid_mean) as min_bid_mean,
       min(buyout_mean) as min_bo_mean,
       min(bid_median) as min_bid_median,
       min(buyout_median) as min_bo_median,
       max(buyout_min) as max_bo,
       max(bid_min) as max_bid,
       max(bid_mean) as max_bid_mean,
       max(buyout_mean) as max_bo_mean,
       max(bid_median) as max_bid_median,
       max(buyout_median) as max_bo_median from item_prices where itemid='".$item->entry."' and faction_id='".$faction->id."' and realm_id='".$realm->id."'";
    $lifetime_row = Yii::$app->db->createCommand($qry)->query()->read();

    if($price_row != null) {
        ?>
        <table class="table">
            <tbody>
            <tr>
                <td>Data Fetched at</td>
                <td><?= $price_row->datetime . " (" . Yii::$app->wowutil->ago($price_row->datetime) . ")" ?></td>
            </tr>
            <tr>
                <td>Quantity On AH</td>
                <td><?= $price_row->quantity ?></td>
            </tr>
            <tr>
                <td>Minimum Bid Price</td>
                <td><?= Yii::$app->wowutil->printCurrency($price_row->bid_min) ?></td>
            </tr>
            <tr>
                <td>Minimum Buyout Price</td>
                <td><?= Yii::$app->wowutil->printCurrency($price_row->buyout_min) ?></td>
            </tr>
            <tr>
                <td>Average Bid Price</td>
                <td><?= Yii::$app->wowutil->printCurrency($price_row->bid_mean) ?></td>
            </tr>
            <tr>
                <td>Average Buyout Price</td>
                <td><?= Yii::$app->wowutil->printCurrency($price_row->buyout_mean) ?></td>
            </tr>
            <tr>
                <td>Median Bid Price</td>
                <td><?= Yii::$app->wowutil->printCurrency($price_row->bid_median) ?></td>
            </tr>
            <tr>
                <td>Median Buyout Price</td>
                <td><?= Yii::$app->wowutil->printCurrency($price_row->buyout_median) ?></td>
            </tr>
            <tr>
                <td>Price Change</td>
                <td><?= Yii::$app->wowutil->printTrend($price_row->buyout_median_last_compare); ?></td>
            </tr>
            <?php if($lifetime_row != null){
                ?>
            <tr>
                <td>LifeTime Price History</td>
                <td><table>
                        <thead>
                        <tr>
                            <th>Type</th>
                            <th>Min</th>
                            <th>Max</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Min Buyout</td>
                            <td><?=Yii::$app->wowutil->printCurrency($lifetime_row['min_bo']) ?></td>
                            <td><?=Yii::$app->wowutil->printCurrency($lifetime_row['max_bo'])  ?></td>
                        </tr>
                        <tr>
                            <td>Min Bid</td>
                            <td><?=Yii::$app->wowutil->printCurrency($lifetime_row['min_bid']) ?></td>
                            <td><?=Yii::$app->wowutil->printCurrency($lifetime_row['max_bid'])  ?></td>
                        </tr>
                        <tr>
                            <td>Mean Buyout</td>
                            <td><?=Yii::$app->wowutil->printCurrency($lifetime_row['min_bo_mean']) ?></td>
                            <td><?=Yii::$app->wowutil->printCurrency($lifetime_row['max_bo_mean'])  ?></td>
                        </tr>
                        <tr>
                            <td>Mean Bid</td>
                            <td><?=Yii::$app->wowutil->printCurrency($lifetime_row['min_bid_mean']) ?></td>
                            <td><?=Yii::$app->wowutil->printCurrency($lifetime_row['max_bid_mean'])  ?></td>
                        </tr>
                        <tr>
                            <td>Median Buyout</td>
                            <td><?=Yii::$app->wowutil->printCurrency($lifetime_row['min_bo_median']) ?></td>
                            <td><?=Yii::$app->wowutil->printCurrency($lifetime_row['max_bo_median'])  ?></td>
                        </tr>
                        <tr>
                            <td>Median Bid</td>
                            <td><?=Yii::$app->wowutil->printCurrency($lifetime_row['min_bid_median']) ?></td>
                            <td><?=Yii::$app->wowutil->printCurrency($lifetime_row['max_bid_median'])  ?></td>
                        </tr>

                        </tbody>
                    </table></td>
            </tr>
            <?php } ?>
            <tr>
                <td>Cost Price</td>
                <td><?= Yii::$app->wowutil->printCurrency($price_row->cost_price); ?></td>
            </tr>
            <?php
            $builds = $item->getBuild();

            if (count($builds) > 0) { ?>
                <tr>
                    <td>Cost Price Breakdown</td>
                    <td>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty.</th>
                                <th>Price Ea.</th>
                                <th>Price Total</th>
                                <th>Source</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($builds as $build) {
                                echo "<tr>";
                                echo "<td>" . Yii::$app->wowutil->printItem($build->required_itemid, $faction->id,$realm->id) . "</td>";
                                echo "<td>x" . $build->quantity . "</td>";
                                $costprice = \app\models\ItemPrices::find()->where(['itemid' => $build->required_itemid, 'faction_id' => $faction->id, 'realm_id' => $realm->id])->orderBy('datetime desc')->one();
                                if ($costprice != null) {
                                    echo "<td>" . Yii::$app->wowutil->printCurrency($costprice->buyout_median) . "</td>";
                                    echo "<td>" . Yii::$app->wowutil->printCurrency($costprice->buyout_median * $build->quantity) . "</td>";
                                } else {
                                    if($build->requireditem->BuyPrice > 0){
                                        $cost_each = floor($build->requireditem->BuyPrice / $build->requireditem->BuyCount);
                                        echo "<td>".Yii::$app->wowutil->printCurrency($cost_each)."</td>";
                                        echo "<td>".Yii::$app->wowutil->printCurrency($cost_each * $build->quantity)."</td>";
                                    }
                                }
                                if($build->source)
                                    echo "<td>" . $build->source->name . "</td>";
                                
                            }
                            ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td>Average Profit From Bid/Buyout</td>
                <td><?= Yii::$app->wowutil->printCurrency($price_row->bid_median - $price_row->cost_price) . " / " . Yii::$app->wowutil->printCurrency($price_row->buyout_median - $price_row->cost_price);; ?></td>
            </tr>
            </tbody>
        </table>
        <?php
    $horde_data = \app\models\ItemPrices::find()->where(['faction_id'=>$faction->id,'realm_id'=>$realm->id,'itemid'=>$item->entry])->orderBy('datetime')->all();
    $data =['quantity'=>['data'=>[]],'bid_mean'=>['data'=>[]],'bid_median'=>['data'=>[]],'cost_price'=>['data'=>[]]];
    $count=0;
    $total = 0;
    foreach($horde_data as $row){
        $dt = new \DateTime($row->datetime);
        $data['quantity']['data'][] = [$dt->getTimestamp(),$row->quantity];
        $data['bid_mean']['data'][] = [$dt->getTimestamp(),$row->bid_mean/10000];
        $data['bid_median']['data'][] = [$dt->getTimestamp(),$row->bid_median/10000];
        $data['buyout_median']['data'][] = [$dt->getTimestamp(),$row->buyout_median/10000];
        $total += $row->buyout_median/10000;
        $count++;
        $x = $row->cost_price/10000;
        if($x > 0)
            $data['cost_price']['data'][] = [$dt->getTimestamp(),$x];
    }
    $distortion = 0;
    $distortion = ($total / $count) * 2;
    ?>
    <h3>Price Chart</h3>
        <center>
            <input class="checkboxes" type="checkbox" onclick="disable_enable(this,0)" checked="checked">Buyout
            <input class="checkboxes" type="checkbox" onclick="disable_enable(this,1)" checked="checked">Bid
            <input class="checkboxes" type="checkbox" onclick="disable_enable(this,2)" checked="checked">Cost Price
        </center>
    <div id="price_chart" style="width:100%; height:300px;">
    </div>
    <h3>Volume Chart</h3>
    <div id="volume_chart" style="width:100%; height:300px;">
    </div>
    <script>
        var all_data = <?=json_encode($data); ?>;
        var price_chart = null;
        var total_data = [
            {"label":"Median Buyout Price","data":all_data['buyout_median']['data'],"lines":{"show":true,"fill":true},"points":{show:true}},
            {"label":"Median Bid Price","data":all_data['bid_median']['data'],"lines":{"show":true,"fill":true},"points":{show:true}},
            {"label":"Cheapest Cost Price","data":all_data['cost_price']['data'],"lines":{"show":true,"fill":true},"points":{show:true}}
        ];
        function setupchart(data){
            price_chart = $.plot("#price_chart",data,{
                xaxis: {
                    show:true,
                    mode:'time',
                    'label':'Time',
                    tickFormatter:function(n){
                        return moment(n*1000).format("DD MMM");
                    }
                },
                grid: {
                    hoverable: true,
                    clickable: true
                },
                yaxis: {
                    show:true,
                    autoScale: 'loose',
                    min:0,
                    max:<?=$distortion ?>,
                    'label':'Price In Gold',
                    tickFormatter:function(n){
                        amt = n *10000;
                        c = Math.floor(amt % 100);
                        amt = Math.floor(amt/100);
                        s = Math.floor(amt % 100);
                        amt = Math.floor(amt/100);
                        g = Math.floor(amt);
                        str = "";
                        if(g > 0)
                            str += "<span class='currencygold'>"+g+"</span>";
                        else if(s > 0)
                            str += "<span class='currencysilver'>"+s+"</span>";
                        else if(c > 0)
                            str += "<span class='currencycopper'>"+c+"</span>";
                        return str;
                    }
                },
                zoom: {
                    interactive: true
                },
                pan: {
                    interactive: true
                }
            });
        }
        document.addEventListener("DOMContentLoaded",function(){

            setupchart(total_data);
            $.plot("#volume_chart",[{"label":"Volume","data":all_data['quantity']['data'],"lines":{"show":"true","fill":true},"points":{show:true}}],{
               xaxis: {
                   show:true,
                   mode:'time',
                   'label':'Time',
                   tickFormatter:function(n){
                       return moment(n*1000).format("DD MMM");
                   }
               },
                grid: {
                    hoverable: true,
                    clickable: true
                },
                yaxis: {
                   show:true,
                   'label':'Volume'
                }
            });
            $("<div id='tooltip'></div>").css({
                position: "absolute",
                display: "none",
                border: "1px solid #fdd",
                padding: "2px",
                "background-color": "#fee",
                opacity: 0.80
            }).appendTo("body");
            $("#volume_chart").bind("plothover", function (event, pos, item) {
                if (item) {
                    var x = item.datapoint[0],
                        y = item.datapoint[1];

                    $("#tooltip").html(y+" units on "+moment(x*1000).format("DD MMM YYYY HH:mm"))
                        .css({top: item.pageY+5, left: item.pageX+5})
                        .fadeIn(200);
                } else {
                    $("#tooltip").hide();
                }
            });
            $("#price_chart").bind("plothover", function (event, pos, item) {
                if (item) {
                    var x = item.datapoint[0],
                        y = item.datapoint[1];
                    amt =y*10000;
                    c = Math.floor(amt % 100);
                    amt = Math.floor(amt/100);
                    s = Math.floor(amt % 100);
                    amt = Math.floor(amt/100);
                    g = Math.floor(amt);
                    str = "";
                    if(g > 0)
                        str += "<span class='currencygold'>"+g+"</span>";
                    if(s > 0)
                        str += "<span class='currencysilver'>"+s+"</span>";
                    if(c > 0)
                        str += "<span class='currencycopper'>"+c+"</span>";

                    $("#tooltip").html(str+" on "+moment(x*1000).format("DD MMM YYYY HH:mm"))
                        .css({top: item.pageY+5, left: item.pageX+5})
                        .fadeIn(200);
                } else {
                    $("#tooltip").hide();
                }
            });

        });
        function disable_enable(o,index){

            var final_data = [];
            checks = $(".checkboxes");
            for(var i=0; i < checks.length; i++){
                if($(checks[i]).is(":checked")){
                    final_data.push(total_data[i]);
                }
            }
            setupchart(final_data);
        }
    </script>
        <?php
        if(!Yii::$app->user->isGuest) {
            ?>
            <div class="card">
                <div class="card-header bg-warning">
                    <h4>Set Alerts For This Item</h4>
                </div>
                <div class="card-body bg-light">
                    <b>
                        Item Alerts are alerts that are sent to you when a specific condition for this item is met.<br>
                        You can configure how you get this alert (Discord, email, Browser Notifications)
                        in your <a href="<?=Yii::$app->urlManager->createUrl("users/profile") ;?>">profile</a> page.

                    </b>
                    <table class="table" id="alert_table">
                        <thead>
                            <tr>
                                <th>Set At</th>
                                <th>Value to Match</th>
                                <th>Comparison Type</th>
                                <th>Value 1</th>
                                <th>Value 2</th>
                                <th>Alert Sent?</th>
                                <th>Sent At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $alerts1 = Yii::$app->user->identity->alerts;
                        $alerts = [];
                        for($i=0; $i < count($alerts1); $i++){
                            if($alerts1[$i]->item_id == $item->entry)
                                $alerts[] = $alerts1[$i];
                        }
                        foreach($alerts as $alert){
                            echo '<tr>
                                <td>'.$alert->datetime_set.'</td>
                                <td>'.$alert->pricetypetext.'</td>
                                <td>'.$alert->optext.'</td>
                                <td>'.Yii::$app->wowutil->printCurrency($alert->value1).'</td>
                                <td>'.Yii::$app->wowutil->printCurrency($alert->value2).'</td>
                                <td>'.($alert->alert_sent?'Yes':'No').'</td>
                                <td>'.$alert->sent_datetime.'</td>
                                <td><div class="fa fa-trash" onclick=\'delete_alert('.$alert->id.','.$alert->item_id.')\' style="cursor:pointer"></div></td>
                            </tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                    <button class="btn btn-success btn-block" onclick="popup_alert(<?=$item->entry; ?>,'<?=quoted_printable_encode($item->name) ?>',<?=$faction->id ?>,<?=$realm->id ?>)"><span class="fa fa-save"></span> Add New Alert</button>
                    <script>
                        function refresh_alerts(arr){
                            $("#alert_table > tbody > tr").remove();
                            for(i=0; i < arr.length; i++){
                                var str = "<tr>";
                                str += "<td>"+arr[i].datetime_set+"</td>";
                                str += "<td>"+arr[i].price_type_text+"</td>";
                                str += "<td>"+arr[i].op+"</td>";
                                str += "<td>"+arr[i].v1_text+"</td>";
                                str += "<td>"+arr[i].v2_text+"</td>";
                                str += "<td>"+(arr[i].alert_sent?"Yes":"No")+"</td>";
                                str += "<td>"+arr[i].sent_datetime+"</td>";
                                str += "<td><div class=\"fa fa-trash\" onclick='delete_alert("+arr[i].id+","+arr[i].item_id+")' style=\"cursor:pointer\"></div></td>";
                                str += "</tr>";
                                $("#alert_table > tbody").append(str);
                            }
                        }

                    </script>
                </div>
            </div>
            <?php
        } else {
            ?>
            <br>
            <div class='card'>
                <div class="card-header  bg-info">
                    <h4>Login To Set Item Alerts!</h4>
                </div>
                <div class="card-body bg-light">
                    <div class="row">
                        <div class="col-4" style="text-align: center;">
                            <span class="fa fa-exclamation fa-8x"></span>
                        </div>
                        <div class="col-8">
                            <h4>You can set alerts for this item!</h4>
                            <p>
                                An Alert is a watch that will trigger when a specific event is met. Eg:
                                <ul>
                                    <li>Alert me when this item has a average buyout price below a certain price</li>
                                    <li>Alert me when this item has a auction buyout price below a certain price</li>
                                </ul>
                            We support various ways to deliver these Alerts to you, we can send you an email, send a discord message, or a simple browser notification.<br>
                            To get started please <a href="<?=Yii::$app->urlManager->createUrl("site/login"); ?>">login</a> or <a href="<?=Yii::$app->urlManager->createUrl("site/register"); ?>">register</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    $auctions = \app\models\AuctionItem::find()->where(['itemid'=>$item->entry,'faction'=>$faction->id,'realm_id'=>$realm->id])->orderBy('buyout')->all();
    if(count($auctions)> 0){
    ?>
    <h3>Current Auctions</h3>
        <div style="max-height:600px; overflow-y: scroll">
    <table class="table">
        <thead>
            <tr>
                <th>Posted By</th>
                <th>Current Bid</th>
                <th>Stack Size</th>
                <th>Min Bid</th>
                <th>Buyout</th>
                <th>Profit Bid / Buyout</th>
                <th>Time Left</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach($auctions as $auction){
            ?>
            <tr>
                <td><?=$auction->user ?></td>
                <td><?=Yii::$app->wowutil->printCurrency($auction->current_bid); ?></td>
                <td><?=($auction->stack); ?></td>
                <td><?=Yii::$app->wowutil->printCurrency($auction->min_bid); ?></td>
                <td><?=Yii::$app->wowutil->printCurrency($auction->buyout); ?></td>
                <td>
                <?php
                $bid_profit = $auction->min_bid-$price_row->cost_price;
                $buyout_profit = $auction->buyout-$price_row->cost_price;
                if($bid_profit < 0){
                    echo "<span class='text-danger'>".Yii::$app->wowutil->printCurrency($bid_profit*-1)."</span>";
                }
                else if($bid_profit > 0){
                    echo "<span class='text-success'>".Yii::$app->wowutil->printCurrency($bid_profit)."</span>";
                }
                else {
                    echo Yii::$app->wowutil->printCurrency($bid_profit);
                }
                echo " / ";
                if($buyout_profit < 0){
                    echo "<span class='text-danger'>".Yii::$app->wowutil->printCurrency($buyout_profit*-1)."</span>";
                }
                else if($buyout_profit > 0){
                    echo "<span class='text-success'>".Yii::$app->wowutil->printCurrency($buyout_profit)."</span>";
                }
                else {
                    echo Yii::$app->wowutil->printCurrency($buyout_profit);
                }

                ?>
                </td>
                <td><?=$auction->timeleft->name; ?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
        </div>
    <?php }
    }
    else {
        echo "No pricing data found for this item on this realm";
    }
    ?>

<?php } else { ?>
    <script>
        function gotoitem(){
            faction = $("#faction").val();
            realm = $("#realm").val();
            itemid = <?=$item->entry; ?>;
            document.location.href="<?=Yii::$app->urlManager->createUrl("item/index"); ?>?id="+itemid+"&faction="+faction+"&realm="+realm;
        }
        function getrealms(o){
            var server = $(o).val();
            $.ajax({"url":"<?=Yii::$app->urlManager->createUrl("item/getrealms"); ?>&id="+server,"success":function(res){
                $("#realm > option").remove();
                for(i=0; i < res.options.length; i++){
                    $("#realm").append("<option value='"+res.options[i].id+"'>"+res.options[i].name+"</option>");
                }
                $("#faction > option").remove();
                for(i=0; i < res.factions.length; i++){
                    $("#faction").append("<option value='"+res.factions[i].id+"'>"+res.factions[i].name+"</option>");
                }
            }});
        }
    </script>
<?php } ?>
