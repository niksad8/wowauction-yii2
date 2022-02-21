<?php
/* @var $query string */
$faction_id = \Yii::$app->request->get("faction");
$realm_id = \Yii::$app->request->get("realm");

if($faction_id == "")
    $faction_id = \Yii::$app->session->get("faction",0);
if($realm_id == "")
    $realm_id = \Yii::$app->session->get("realm",0);

if($realm_id == "" || $faction_id == ""){
    ?>
    <div class="card card-default">
        <div class="card-header">
            <h3>Select your realm / faction</h3>
        </div>
        <div class="card-body">
            <?php
            if($realm_id == ""){
                ?>
                <div class="row">
                    <div class="col"><b>Please select your realm : </b></div>
                    <div class="col"><?php
                        $realms = \app\models\Realm::findBySql("select * from realm order by server_id;")->all();
                        $outs = [];
                        foreach($realms as $realm){
                            $outs[$realm->server->name][] = ['id'=>$realm->id,'name'=>$realm->name];
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
            if($faction_id == ""){
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
                    <div class="col"><button type="button" class="btn btn-primary btn-block">Save Settings</button></div>
                    <div class="col"></div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>
<?php
$classes = \app\models\ItemSubClass::find()->all();
$out = [];
foreach($classes as $class){
    $out[$class->class_id][] = ['id'=>$class->id,'name'=>$class->name];
}
?>

<script>
    var sub_classes = <?=json_encode($out); ?>;
    function getsubclass(o){
        v = $(o).val();
        $("#sub_class > option").remove();
        $("#sub_class").append("<option value=''>Any</option>");
        if(sub_classes[v]){
            for(i =0; i < sub_classes[v].length; i++){
                $("#sub_class").append("<option value='"+sub_classes[v][i].id+"'>"+sub_classes[v][i].name+"</option>");
            }
        }
    }

    function changepage(o){
        var page = $(o).attr('data-page');
        var url = $(o).attr('href');
        $.ajax({"url":url,"success":function(res){
            $("#results").html(res);
        }});
        return false;
    }

    function search_items(o){
        $(o).addClass("disabled");
        var query = $("#query_string").val();
        var qclass = $("#class").val();
        var sub_class = $("#sub_class").val();
        var lvl_from = $("#level_from").val();
        var lvl_to = $("#level_to").val();
        var ilvl_from = $("#ilvl_from").val();
        var ilvl_to = $("#ilvl_to").val();
        var proff = $("#profession").val();
        var quality = $("#quality").val();
        $.ajax({
            "url":"<?=Yii::$app->urlManager->createUrl("site/searchonly") ?>",
            "type":"GET",
            "data":{
                "query":query,
                "class":qclass,
                "sub_class":sub_class,
                "lvl_from" : lvl_from,
                "lvl_to" : lvl_to,
                "ilvl_from" : ilvl_from,
                "ilvl_to" : ilvl_to,
                "profession" : proff,
                'quality': quality
            },"success":function(res){
                $("#results").html(res);
                $(o).removeClass("disabled");
            },
            "error":function(){
                $(o).removeClass("disabled");
            }
        });
    }
    <?php if($query != "") {
        ?>
        window.addEventListener("load",function(){
            search_items($("#search_button"));
        });
        <?php
    }
    ?>
</script>
<p style="text-align: center">
    <div class="card">
        <div class="card-header">
            <h1>Search For Item</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <b>Search By Name</b>
                </div>
                <div class="col">
                    <input type="text" class="form-control" id="query_string" value="<?=$query ?>">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <b>Select Class</b>
                </div>
                <div class="col">
                    <select class="form-control" id="class" onchange="getsubclass(this)">
                        <option value="">Any</option>
                        <?php
                        $classes = \app\models\ItemClass::find()->all();
                        foreach($classes as $class){
                            echo '<option value="'.$class->id.'">'.$class->name."</option>\n";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <b>Select Sub-Class</b>
                </div>
                <div class="col">
                    <select class="form-control" id="sub_class">
                        <option value="">Any</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <b>Level Of Item</b>
                </div>
                <div class="col">
                    <div class="row">
                        <div class="col">
                            <input type="number" id="level_from" class="form-control">
                        </div>
                        <div class="col">
                            <h1 style="text-align: center">-</h1>
                        </div>
                        <div class="col">
                            <input type="number" id="level_to" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <b>Item iLvl</b>
                </div>
                <div class="col">
                    <div class="row">
                        <div class="col">
                            <input type="number" id="ilvl_from" class="form-control">
                        </div>
                        <div class="col">
                            <h1 style="text-align: center">-</h1>
                        </div>
                        <div class="col">
                            <input type="number" id="ilvl_to" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <b>Profession</b>
                </div>
                <div class="col">
                    <select class="form-control" id="profession">
                        <option value="">All</option>
                        <?php
                        $proffs = \app\models\Professions::find()->all();
                        foreach($proffs as $proff){
                            echo "<option value='".$proff->id."'>".$proff->name."</option>\n";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <b>Item Quality</b>
                </div>
                <div class="col">
                    <select class="form-control" id="quality">
                        <option value="">Any</option>
                        <?php
                            $names = \app\models\ItemTemplate::$item_quality_names;
                            for($i=0; $i < count($names); $i++){
                                echo "<option value='".$i."'>".$names[$i]."</option>\n";
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col">
                    <button id='search_button' type="button" onclick='search_items(this)' class="btn btn-success btn-block"><span class="fa fa-search"></span> Search</button>
                </div>
            </div>
        </div>
    </div>
<br>
<div id="results"></div>
</p>
