<?php

/* @var $this yii\web\View */
/* @var $servers */
$this->title = 'WoW Auction House Analysis';
?>
<style>
    .server-icon {
        height:200px;
        width:100%;
        background-position: center;
        background-repeat: no-repeat;
        background-size: contain;
    }
</style>
<div class="row">
    <div class="col text-center">
        <h1>Welcome to Web Auctioneer</h1>
        <h3>A auction house analysis website for World Of Warcraft private servers</h3>
    </div>
</div>
<div class="row">
    <div class="col">
        <h2>Features Of Website</h2>
        <ul>
            <li>price history of the item on your realm and faction</li>
            <li>add alerts to items based on your conditions and receive notifications (discord, email, desktop)</li>
            <li>view cost price(price to make item) of item</li>
            <li>view profit you can get if you make an item using buyout</li>
            <li>discord integration with website get auction house prices to your discord server</li>
            <li><b>*NEW*</b> Download Auctioneer scan data to WoW game folder</li>
            <li>Added a <a href="<?=Yii::$app->urlManager->createUrl("site/faq") ?>">FAQ</a></li>
        </ul>
        <h3>If you have any suggestion or feedback please send a email to admin@web-auctioneer.com</h3>
    </div>
</div>
<br>
<div class="container">
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h3>Select your Server</h3>
            </div>
            <div class="card-body">
                <div class="card-text">
                    <div class="container">
                        <div class="row">
                            <?php
                            foreach($servers as $server ){
                                ?>
                                <div class="col-sm-6 col-md-4">
                                    <div class="card">
                                        <div class='server-icon' style='background-image: url(/images/servers/<?=$server->id ?>)' alt="<?=$server->name ?>"></div>
                                        <div class="card-body">
                                            <h5 class="card-title"><?=$server->name ?></h5>
                                            <p class="card-text"><?=$server->desc ?></p>
                                            <a class='btn btn-primary btn-block' href="<?=Yii::$app->urlManager->createUrl(["servers/base",'id'=>$server->id]); ?>">Go</a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>