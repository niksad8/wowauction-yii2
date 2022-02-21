<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <script data-ad-client="ca-pub-6764712011535007" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <style>
        .bg-image {
            background-position: center;
            background-repeat: no-repeat;
            background-size: contain;
        }
        .trade-icon {
            width:70px;
            height:70px;
        }
        .logo-horde {
            background-image: url(/images/horde.png);
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }
        .logo-alliance {
            background-image: url(/images/alliance.png);
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }
        .currencygold {
            padding-left:5px;
            text-align:left;
            padding-right:15px;
            background:no-repeat right center;
            background-image:url(/images/general/money-gold.gif);
        }
        .currencysilver {
            padding-left:5px;
            text-align:left;
            padding-right:15px;
            background:no-repeat right center;
            background-image:url(/images/general/money-silver.gif);
        }
        .currencycopper {
            padding-left:5px;
            padding-right:15px;
            text-align:left;
            background:no-repeat right center;
            background-image:url(/images/general/money-copper.gif);
        }
        .mediumicon div {
            background-size:cover;
            background-repeat: no-repeat;
            background-position: center;
        }
    </style>
    <meta charset="<?= Yii::$app->charset ?>">
    <?php if(isset(Yii::$app->params['image']) && Yii::$app->params['image'] != "") { ?>
        <meta content="<?=Yii::$app->params['image'] ?>" itemprop="image">
        <meta content="<?=Yii::$app->params['image'] ?>" property="og:image">
    <?php } else {?>
        <meta content="/images/wowicon.png" itemprop="image">
        <meta content="/images/wowicon.png" property="og:image">
    <?php } ?>
    <meta name="title" content="<?=Html::encode($this->title) ?>">
    <meta property="og:title" content="<?=Html::encode($this->title) ?>">
    <meta name="description" content="A website that analyzes the WoW Auction house on private servers, allows individual item price analysis with graphs.">
    <meta property="og:description" content="A website that analyzes the WoW Auction house on private servers, allows individual item price analysis with graphs.">
    <meta name="keywords" content="WoW, World Of Warcraft,Auction House,World Of Warcraft Private servers, WoW Economy, WoW Private Servers">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?>- Web Auctioneer, World of warcraft auction house Analysis for private servers</title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div>
    <?php

    \yii\bootstrap4\NavBar::begin([
        'brandLabel' => "Web Auctioneer",
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => ['navbar-dark', 'bg-dark', 'navbar-expand-md'],
        ],
    ]);
    echo "<div class='col'>
            <input aria-haspopup='true' aria-expanded='false' placeholder='Search for item' type='text' id='search_box' class='form-control'>
            <div class='dropdown-menu' aria-labelledby='search_box' style='left:15px; width:100%' id='item_list'></div>
        </div>";
    echo "<div class='col'>
            <span style='color:white; font-size:18px;'>
                <a href='".Yii::$app->urlManager->createUrl('site/discord')."'><div class='fab fa-discord' style='color:blue'></div> Discord Bot!</a>
            </span>
    </div>";

    echo \yii\bootstrap4\Nav::widget([
        'options' => ['class' => 'justify-content-end'],
        'items' => [
            ['label' => 'Masters', 'visible'=>!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin(),'url' =>'#','items'=>[
                ['label' => 'Servers', 'url' => ['/Admin/servers/index']],
                ['label' => 'Expansions', 'url' => ['/Admin/expansion/index']],
                ['label' => 'Realms', 'url' => ['/Admin/realm/index']],
                ['label' => 'Users', 'url' => ['/Admin/users/index']],
                ['label' => 'Upload Auction Data', 'url' => ['/Admin/realm/uploadauctiondata']],
                ['label' => 'Professions Data', 'url' => ['/Admin/professions/index']]
            ]],
            ['label'=>'AH Management', 'visible'=>!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin(),'url'=>'#','items'=>[
                ['label' => 'Slot Translation', 'url' => ['/Admin/slot-translation/index']],
                ['label' => 'Auction Timer', 'url' => ['/Admin/auction-timer/index']],
                ['label' => 'AH Main Category', 'url' => ['/Admin/ah-main-cat/index']],
                ['label' => 'AH Sub Category', 'url' => ['/Admin/ah-sub-cat/index']],
                ['label' => 'Slot Definitions', 'url' => ['/Admin/slot-master/index']]
            ]],
            !Yii::$app->user->isGuest ?
            ['class'=>'justify-content-end','label'=>Yii::$app->user->identity->username, 'visible'=>!Yii::$app->user->isGuest,'url'=>'#','items'=>[
                ['label' => 'Profile', 'url' => ['/users/profile']],
                ['label' => 'Logout', 'url' => ['/site/logout']]
            ]] : ['label'=>'Register','url'=>['site/register']],
            ['label' => 'Login','visible'=>Yii::$app->user->isGuest, 'url' => ['/site/login']]
        ],
    ]);

    NavBar::end();
    ?>

    <div class="container">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>
<div class="modal fade" id="set_alert_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                Create a new Alert!
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            </div>
            <div class="modal-body">
                <h4 id="alert_item_name"></h4>
                <input type="hidden" id="alert_item_id">
                <input type="hidden" id="alert_realm_id">
                <input type="hidden" id="alert_faction_id">
                <div class="row">
                    <div class="col">
                        <b>Select Amount Type To Monitor</b>
                        <select id="alert_price_type" class="form-control">
                            <option value="median_buyout">Median Buyout</option>
                            <option value="mean_buyout">Average Buyout</option>
                            <option value="median_bid">Median Bid</option>
                            <option value="mean_bid">Average Bid</option>
                            <option value="min_buyout">Min Buyout</option>
                            <option value="min_bid">Min Bid</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <b>Select Comparison Type</b>
                        <select id="op_type" class="form-control">
                            <option value="<=">Less Than Or Equal To</option>
                            <option value=">=">Greater Than Or Equal To</option>
                            <option value="><">Is Between</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <div class="col">
                                <b>First Value</b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="input-group">
                                    <input type="text" id='value1_g' class="form-control">
                                    <div class="input-group-append">
                                        <span class="input-group-text currencygold"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="input-group">
                                    <input type="text" id='value1_s' class="form-control">
                                    <div class="input-group-append">
                                        <span class="input-group-text currencysilver"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="input-group">
                                    <input type="text" id='value1_c' class="form-control">
                                    <div class="input-group-append">
                                        <span class="input-group-text currencycopper"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <div class="col">
                                <b>Second Value(only used for between comparison)</b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="input-group">
                                    <input type="text" id='value2_g' class="form-control">
                                    <div class="input-group-append">
                                        <span class="input-group-text currencygold"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="input-group">
                                    <input type="text" id='value2_s' class="form-control">
                                    <div class="input-group-append">
                                        <span class="input-group-text currencysilver"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="input-group">
                                    <input type="text" id='value2_c' class="form-control">
                                    <div class="input-group-append">
                                        <span class="input-group-text currencycopper"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="save_alert()">Save changes</button>
            </div>
        </div>
    </div>
</div>
<?php $this->endBody() ?>
<script src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://wotlk.evowow.com/static/widgets/power.js"></script>
<script src="/js/moment.js"></script>
<script type="text/javascript" src="/js/flot/jquery.flot.js"></script>
<script type="text/javascript" src="/js/flot/jquery.flot.time.js"></script>
<script type="text/javascript" src="/js/flot/jquery.flot.navigate.min.js"></script>
<script>
    var calling = 0;
    var needtocall = 0;
    var called_value = "";
    function tocopper(g,s,c){
        var ret = 0;
        ret = !isNaN(g*1)?g*10000:0;
        ret += !isNaN(s*1)?s*100:0;
        ret += !isNaN(c*1)?c*1:0;
        if(!isNaN(ret))
            return ret;
        else
            return 0;
    }

    function request_perm(){
        Notification.requestPermission();
    }
    <?php
            if(!Yii::$app->user->isGuest) { ?>
            function checkdesktopnotification(){
                $.ajax({"url":"<?=Yii::$app->urlManager->createUrl("users/getdesktopnotification") ?>","success":function (res) {
                    if(res.status == "SUCCESS"){
                        if(res.response.length > 0){
                            for(var i=0; i < res.response.length; i++){
                                notifyme(res.response[i].title,res.response[i].icon,res.response[i].body,res.response[i].url);
                            }
                        }
                    }
                    setTimeout(checkdesktopnotification,60000);
                },error:function(){
                        setTimeout(checkdesktopnotification,60000);
                }})
            }
            setTimeout(checkdesktopnotification,5000);
    <?php }
    ?>
    function reset_alert(id){
        $.ajax({"url":"<?=Yii::$app->urlManager->createUrl(['users/resetalert',]); ?>","type":"POST","data":{id:id},"success":function(res){
            if(res.status == "SUCCESS"){
                refresh_alerts(res.data);
            }
        }});
    }
    function notifyme(heading,icon,body,url){
        var not = new Notification(heading,{body:body,icon:icon,'data':url});
        var _url = url;
        not.onclick = function(e){
            document.location.href= e.target.data;
        };
    }

    function popup_alert(id,item_name,realm,faction){
        $("#value1_g").val("");
        $("#value1_s").val("");
        $("#value1_c").val("");
        $("#value2_g").val("");
        $("#value2_s").val("");
        $("#value2_c").val("");
        $("#op_type").val("");
        $("#alert_realm_id").val(realm);
        $("#alert_faction_id").val(faction);
        $("#alert_price_type").val("");
        $("#alert_item_id").val(id);
        $("#alert_item_name").html("Set new alert for "+item_name);
        $("#set_alert_modal").modal("show");
    }

    function save_alert(){
        var item_id=$("#alert_item_id").val();
        var ptype = $("#alert_price_type").val();

        var v1 = tocopper($("#value1_g").val(),$("#value1_s").val(),$("#value1_c").val());
        var v2 = tocopper($("#value2_g").val(),$("#value2_s").val(),$("#value2_c").val());
        var op = $("#op_type").val();
        $.ajax({"url":"<?=Yii::$app->urlManager->createUrl("users/savealert") ?>","type":"POST","data":{id:item_id,'v1':v1,'v2':v2,'op':op,'ptype':ptype},success:function(res){
            if(res.status == "SUCCESS"){
                $("#set_alert_modal").modal("hide");
                refresh_alerts(res.data);
            }
            else {
                alert(res.message);
            }
        }});
    }
    function delete_alert(id,item_id){
        $.ajax({"url":"<?=Yii::$app->urlManager->createUrl("users/deletealert"); ?>","type":"POST","data":{'id':id,'itemid':item_id},success:function(res){
            if(res.status == "SUCCESS"){
                refresh_alerts(res.data);
            }
            else {
                alert(res.message);
            }
        }});
    }
    function searchitems(v){
        $.ajax({"url":"<?=\Yii::$app->urlManager->createUrl("item/search") ?>?term="+v,"success":function(res){
            if(res != null && res.items && res.items.length > 0){
                $("#item_list").find("a").remove();
                for(i=0; i < res.items.length; i++){
                    $("#item_list").append("<a href='#'>"+res.items[i].display+"</a>");
                }
                $("#item_list").addClass('show');
                if(needtocall){
                    needtocall = 0;
                    setTimeout(searchitems,0,called_value);
                }
                //$("#search_box").parent().addClass("open");
            }
            calling = 0;
        },"error":function(){calling = 0;}})
    }
    $(function(){
        $("#search_box").on("keyup",function(e){
            console.log(e);
            $("#search_box").parent().removeClass("open");
            $("#item_list").removeClass('show');
            v = $(e.currentTarget).val();
            if(v.length > 3){
                if(e.keyCode == 13){
                    document.location.href= "<?=Yii::$app->urlManager->createUrl("site/search") ?>?query="+v;
                }
                if(!calling){
                    called_value = v;
                    calling = 1;
                    searchitems(v);
                }
                else {
                    needtocall = 1;
                    called_value = v;
                }
            }
        });
    });
</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-133950066-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-133950066-1');
</script>
</body>
<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Web Auctioneer <?= date('Y') ?></p>
    </div>
</footer>
</html>

<?php $this->endPage() ?>
