<h1><?=$model->username ?> your profile.</h1><br>
<div class="card">
    <div class="card-header">
        <h2>Change Password</h2>
    </div>
    <div class="card-body">
        <?php
        echo \yii\bootstrap\Html::beginForm(["users/changepassword"]);
        ?>
        <div class="row">
            <div class="col">
                <b>Current Password : </b>
            </div>
            <div class="col">
                <input type="text" class="form-control" name="old_password">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <b>New Password : </b>
            </div>
            <div class="col">
                <input type="text" class="form-control" name="new_password">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <b>Reenter New Password : </b>
            </div>
            <div class="col">
                <input type="text" class="form-control" name="renew_password">
            </div>
        </div>
        <div class="row">
            <div class="col"></div>
            <div class="col">
                <button type="submit" class="btn btn-success">Change Password</button>
            </div>
        </div>
    </div>
</div>
<?php
echo \yii\bootstrap\Html::endForm();
?>
<br><br>
<?php
$current_realm = \app\models\Realm::findOne(['id'=>Yii::$app->session->get("realm")]);
$current_faction = \app\models\Factions::findOne(['id'=>Yii::$app->session->get("faction")]);
echo \yii\bootstrap\Html::beginForm(["users/setrealmfaction"]);
?>

<div class="card">
    <div class="card-header">
        <h3>Change default Realm and Faction</h3>
    </div>
    <div class="card-body">
        <b>Current Selections</b><br>
        <b>Server : </b><?=($current_realm)?$current_realm->server->name:""; ?><br>
        <b>Realm : </b><?=($current_realm)?$current_realm->name:""; ?><br>
        <b>Faction : </b><?=($current_faction)?$current_faction->name:""; ?><br>
        <table class="table">
            <tbody>
            <tr>
                <td><b>Select Server</b></td>
                <td><select class="form-control" name="server" onchange="getrealms(this)"><option>Select One</option><?php
                        $servers = \app\models\Servers::find()->all();
                        foreach($servers as $server){
                            echo "<option value='".$server->id."'>".$server->name."</option>\n";
                        }
                ?></select></td>
            </tr>
            <tr>
                <td><b>Select Realm</b></td>
                <td><select class="form-control" id="realm" name="realm"><option>Select Server First</option></select></td>
            </tr>
            <tr>
                <td><b>Select Faction</b></td>
                <td><select class="form-control" name="faction">
                        <?php
                        $factions = \app\models\Factions::find()->all();
                        foreach($factions as $faction){
                            echo "<option value='".$faction->id."'>".$faction->name."</option>\n";
                        }
                        ?>
                    </select></td>
            </tr>
            <tr><td colspan="2">
                    <button class="btn btn-success btn-block" type="submit">Save</button>
                </td></tr>
            </tbody>
        </table>
    </div>
</div>
<?php
echo \yii\bootstrap\Html::endForm();
?>
<script>
    function getrealms(o){
        v = $(o).val();
        $.ajax({"url":"<?=Yii::$app->urlManager->createUrl("servers/getrealmlist"); ?>?id="+v,"success":function(res){
            if(typeof res == "object"){
                $("#realm").find("option").remove();
                for(i=0; i < res.length; i++){
                    $("#realm").append("<option value='"+res[i].id+"'>"+res[i].name+"</option>");
                }
            }
        }});
    }
</script>
<br>
<div class="card">
    <div class="card-header">
        <h3>Your Item Alerts</h3><br>
    </div>
    <div class="card-body">
        <?php
            if(count($model->alerts) > 0){
            ?>
            <table class="table" id="alert_table">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Realm</th>
                        <th>Faction</th>
                        <th>Set At</th>
                        <th>Alert Sent?</th>
                        <th>Alert Sent At</th>
                        <th>Compare to</th>
                        <th>Comparision Type</th>
                        <th>First Value</th>
                        <th>Second Value</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for($i=0; $i < count($model->alerts); $i++){
                        echo "<tr>
                                <td>".Yii::$app->wowutil->printItemname($model->alerts[$i]->item_id)."</td>
                                <td>".$model->alerts[$i]->realm->name."</td>
                                <td>".$model->alerts[$i]->faction->name."</td>
                                <td>".$model->alerts[$i]->datetime_set."</td>
                                <td>".(($model->alerts[$i]->alert_sent)?"Yes":"No")."</td>
                                <td>".$model->alerts[$i]->sent_datetime."</td>
                                <td>".$model->alerts[$i]->pricetypetext."</td>
                                <td>".$model->alerts[$i]->optext."</td>
                                <td>".Yii::$app->wowutil->printCurrency($model->alerts[$i]->value1)."</td>
                                <td>".Yii::$app->wowutil->printCurrency($model->alerts[$i]->value2)."</td>
                                <td>
                                    <span class='fa fa-trash' style='cursor:pointer;' onclick='delete_alert(".$model->alerts[$i]->id.",".$model->alerts[$i]->item_id.")'></span>
                                    ".(($model->alerts[$i]->alert_sent)?"<span class='fa fa-retweet' style='cursor:pointer;' onclick='reset_alert(".$model->alerts[$i]->id.",".$model->alerts[$i]->item_id.")'></span>":"")."
                                </td>
                                </tr>";
                    }
                    ?>
                </tbody>
            </table>
            <?php
            }
        ?>
    </div>
</div>
<br>
<div class="card" id="email_notifcations">
    <div class="card-header">
        <h4>Setup and manage Email Notifications</h4>
    </div>
    <div class="card-body">
        <b>Please Enter Your Email Address To Receive Email Notifications</b><br>
        <div class="row">
            <div class="col-xs-8 col-md-11">
                <input required type="email" id="email" class="form-control" value="<?=\app\models\UserSettings::get("email_address"); ?>">
            </div>
            <div class="col-xs-4 col-md-1">
                <button type="button" class="btn btn-success btn-block" onclick="save_email_address(this)"><span class="fa fa-save"></span></button>
            </div>
        </div>
        <br>
        <b><input type="checkbox" <?=(\app\models\UserSettings::get("email_notification") == "1"?"checked='checked'":""); ?> id="email_notification_check" onclick="updatenotification_setting(this,'email')"> Email Notifications Enabled</b>
        <br><br>
        <b>Your Daily Emails for Realm Summary</b> (these can be set in the individual realm pages)
        <table class="table">
            <thead>
                <tr><th>Realm Name</th><th>Faction</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php
                $subs = \app\models\EmailSubscriptions::findAll(['userid'=>Yii::$app->user->getid()]);
                for($i=0; $i < count($subs); $i++){
                    echo '<tr>';
                    echo "<td>".$subs[$i]->realm->server->name." (".$subs[$i]->realm->name.")</td>";
                    echo "<td>".$subs[$i]->faction->name."</td>";
                    echo "<td><span class='fa fa-trash-o' style='cursor:pointer;' onclick='delete_sub(this,".$subs[$i]->id.")'></span> </td>";
                    echo "</tr>\n";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<br>
<div class="card" id="desktop_notification">
    <div class="card-header">
        <h4>Setup Desktop notifications</h4>
    </div>
    <div class="card-body">
        <b>Setup notifications for your Browser.</b>
        <ol>
            <li><b>First Step please uauthorize the website to send you the notifications <button id='perm_request' type='button' onclick='request_perm()' class="btn btn-primary">Setup Permissions</button></b></li>
            <li><b>Test this popup! <button type='button' onclick='test_notification()' class="btn btn-primary">Test Notifications!</button></b></li>
        </ol>

        <b><input type="checkbox" <?=(\app\models\UserSettings::get("desktop_notification") == "1"?"checked='checked'":""); ?> id="desktop_notification_check" onclick="updatenotification_setting(this,'desktop')"> Desktop Notifications Enabled</b>
    </div>
</div>
<br>
<div class="card" id="discord_notification">
    <div class="card-header">
        <h4>Setup Discord Notifications</h4>
    </div>
    <div class="card-body">
        <b>We can message you the notifications on discord too.</b>
        <p>
            <u>A couple of things to remember our bot can only message you if the bot has been added to your server.</u><br>
            <br>
            <b>Please enter your user tag here</b>
            <div class="row">
                <div class="col-xs-8 col-md-11">
                    <input type="text" id="discord_user_tag" class="form-control" value="<?=\app\models\UserSettings::get("discord_name"); ?>">
                </div>
                <div class="col-xs-4 col-md-1">
                    <button type="button" class="btn btn-success btn-block" onclick="save_discord_name(this)"><span class="fa fa-save"></span></button>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col">
                    <b>Clicking on this button will make you receive a message from our bot.</b>
                    <br>
                    <button class="btn btn-success btn-block" type="button" onclick="test_discord_message()">Test Discord Notification.</button>
                </div>
            </div>
        </p>
        <b><input type="checkbox" <?=(\app\models\UserSettings::get("discord_notification") == 1?"checked='checked'":""); ?> onchange="updatenotification_setting(this,'discord')"> Enable sending me discord messages.</b>
    </div>
</div>
<script>
    function daily_sub(o,id){
        var _o =o;
        $.ajax({"url":"<?=Yii::$app->urlManager->createUrl(["users/deletesub"]); ?>&id="+id,"success":function(res){
            
        }});
        $(o).parent().parent()
    }
    function save_discord_name(o){
        var name =$("#discord_user_tag").val();
        $(o).attr("disabled","disabled");
        $.ajax({"url":"<?=Yii::$app->urlManager->createUrl("users/savediscordname"); ?>","type":"POST","data":{"tag":name}});
    }
    function updatenotification_setting(o,setting){
        v = $(o)[0].checked;
        $.ajax({"url":"<?=Yii::$app->urlManager->createUrl("users/changenotificationoption"); ?>","type":"POST","data":{"value":v,"setting":setting}
        });

    }
    document.addEventListener('DOMContentLoaded',function(){
        if(Notification){
            $("#desktop_notification").show();
        }
        else
            $("#desktop_notification").hide();
        var int = setInterval(function(){
            if(Notification.permission == "granted"){
                clearInterval(int);
                $("#perm_request").attr("disabled","disabled");
            }
            else {
                $("#perm_request").removeAttr("disabled");
            }
        },1000)
    });
    function save_email_address(o){
        var name =$("#email").val();
        if(!$("#email")[0].checkValidity()){
            alert("Email is not valid! Please enter a valid response.");
            return;
        }
        $(o).attr("disabled","disabled");
        $.ajax({"url":"<?=Yii::$app->urlManager->createUrl("users/saveemail"); ?>","type":"POST","data":{"email":name}});

    }
    function test_discord_message(){
        $.ajax({"url":"<?=Yii::$app->urlManager->createUrl("users/testdiscord") ;?>","success":function (res) {
           if(res == "OK")
               alert('Alert sent');
        }});
    }
    function test_notification(){
        notifyme("This is a Test","/images/wowicon.png","This is a test notification");
    }
    function notification_success(t){
        if(t){
            $("#perm_request").attr("disabled","disabled");
            $("#perm_request").html("Permission Revieved");
        }
        else {
            $("#perm_request").html("Setup Permission");
        }
    }
    function refresh_alerts(arr){
        $("#alert_table > tbody > tr").remove();
        for(i=0; i < arr.length; i++){
            var str = "<tr>";
            str += "<td>"+arr[i].item_name+"</td>";
            str += "<td>"+arr[i].realm+"</td>";
            str += "<td>"+arr[i].faction+"</td>";
            str += "<td>"+arr[i].datetime_set+"</td>";
            str += "<td>"+(arr[i].alert_sent?"Yes":"No")+"</td>";
            str += "<td>"+arr[i].sent_datetime+"</td>";
            str += "<td>"+arr[i].price_type_text+"</td>";
            str += "<td>"+arr[i].op+"</td>";
            str += "<td>"+arr[i].v1_text+"</td>";
            str += "<td>"+arr[i].v2_text+"</td>";
            str += "<td><div class=\"fa fa-trash\" onclick='delete_alert("+arr[i].id+")' style=\"cursor:pointer\"></div>";
            if(arr[i].alert_sent){
                str += "<div class=\"fa fa-retweet\" onclick='reset_alert("+arr[i].id+")' style=\"cursor:pointer\"></div>";
            }
            str += "</td>";
            str += "</tr>";
            $("#alert_table > tbody").append(str);
        }
    }
</script>

