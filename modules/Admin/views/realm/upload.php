<?php

?>
<h1>Upload Auction Data Auc-ScanData.lua</h1>
<br>
<h4><?=$message ?></h4>
<form method="POST" enctype="multipart/form-data" action="<?=Yii::$app->urlManager->createUrl("Admin/realm/uploadauctiondata") ?>">
<h3>Please Upload the file: </h3>
    <input type="file" name="auction_data"><button class="btn btn-success">Upload</button>
    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
</form>
<?php
exec("ls -1 ".Yii::$app->getBasePath().DIRECTORY_SEPARATOR."auc-data".DIRECTORY_SEPARATOR,$output);
if(count($output) > 0){
    ?>
    <br>
    <h3>File Following Files are on the server Select a file :</h3>
    <select class="form-control" id="file_name">
        <?php
        foreach($output as $line){
            echo "<option>".$line."</option>";
        }
        ?>
    </select>
    <br>
    <br>
    <?php
    $processes = \app\models\ProcessQueue::find()->where(['completed'=>0])->all();
    if(count($processes) > 0) { ?>
        <div class="row">
            <div class="col-xs-12">
                <b>Current Pending Processes</b><br>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Posted at </th>
                        <th>Filename</th>
                        <th>Realm</th>
                        <th>Faction</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($processes as $process ){
                        echo "<tr>";
                        echo "<td>".$process->datetime_posted."</td>";
                        echo "<td>".$process->filename."</td>";
                        echo "<td>".$process->realm->name."</td>";
                        echo "<td>".$process->faction->name."</td>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php } ?>
    <br>
    <?php
    $processes = \app\models\ProcessQueue::find()->where(['completed'=>1])->limit(5)->orderBy('datetime_completed DESC')->all();
    if(count($processes) > 0) { ?>
        <div class="row">
            <div class="col-xs-12">
                <b>Last 5 Completed Imports</b><br>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Posted at </th>
                        <th>Completed At</th>
                        <th>Filename</th>
                        <th>Realm</th>
                        <th>Faction</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($processes as $process ){
                        echo "<tr>";
                        echo "<td>".$process->datetime_posted."</td>";
                        echo "<td>".$process->datetime_completed."</td>";
                        echo "<td>".$process->filename."</td>";
                        echo "<td>".$process->realm->name."</td>";
                        echo "<td>".$process->faction->name."</td>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php } ?>
    <div class="row">
        <div class="col-xs-6">
            <b>Select Realm to import to :</b> <br>
            <select id="realm" class="form-control">
                <?php
                $realms = \app\models\Realm::find()->orderBy("server_id")->all();
                foreach($realms as $realm){
                    echo "<option value='".$realm->id."'>".$realm->server->name."-".$realm->name." [".$realm->expansion->name."]"."</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-xs-6">
            <b>Select Faction : </b><br>
            <select id="faction" class="form-control">
                <?php
                $factions = \app\models\Factions::find()->all();
                foreach($factions as $faction){
                    echo "<option value='".$faction->id."'>".$faction->name."</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <br>
            <button class="btn btn-success" type="button" onclick="startimport();">Post</button>
        </div>
    </div>
    <script>
        function startimport(){
            file_name = $("#file_name").val();
            realm = $("#realm").val();
            faction = $("#faction").val();
            if(file_name != "" && realm != "" && faction != ""){
                $.ajax({"url":"<?=Yii::$app->urlManager->createUrl("Admin/realm/posttobackend"); ?>","type":"POST","data":{"filename":file_name,"realm":realm,"faction":faction},"success":function(res){
                    if(res.status == "success"){
                        //document.location.reload();
                    }
                    else
                        alert("Error :"+res.message);
                }});
            }
        }
    </script>
<?php
}
?>
