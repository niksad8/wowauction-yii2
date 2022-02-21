
<center><h1>Frequently Asked Questions</h1></center>
<br><br>
<p>
<div class="accordion" id="accordionExample">
    <div class="card">
        <div class="card-header" id="headingOne">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    What does this site do?
                </button>
            </h2>
        </div>

        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
            <div class="card-body">
                This sites takes a snap shot of the auction house from various servers and realms. This data is then processed and presented in graphs and trends.
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingTwo">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    The prices dont seem to match what i see on the auction house, why?
                </button>
            </h2>
        </div>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
            <div class="card-body">
                The prices we have on the site eg: bid price, buyout price are all medians. This usually means it will mostly be a bit higher than lowest price or the average sane price.<br>
                On the individual item page you can see the minimum bid and buyout price. Remember this is a snap snot so some of those listing may have been brought out already.
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingFour">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    What is a cost price?
                </button>
            </h2>
        </div>
        <div id="collapseThree" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
            <div class="card-body">
                Cost price works on crafted Items. It is the total cost of materials if each item was brought from the auction house.<br>
                This is useful if you want to a quick turnover where you can immediately buy the materials from the auction house and craft the item.
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingFive">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                    Can I integrate this in to my Discord server?
                </button>
            </h2>
        </div>
        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#collapseFive">
            <div class="card-body">
                Yes you can click <a href="<?=Yii::$app->urlManager->createUrl("site/discord") ?>">HERE</a>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingSix">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                    What are alerts?
                </button>
            </h2>
        </div>
        <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordionExample">
            <div class="card-body">
                Alerts are triggers that be set on data for an item. You can set these alerts in the item page.<br>
                You can see which items have alerts set in your profile screen.  <br>
                Just remember an alert triggers only once. You will have to reset it to trigger it again.<br>
                Triggers are only processed when we refresh the data on the site.
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingSeven">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                    How often is the data refreshed?
                </button>
            </h2>
        </div>
        <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordionExample">
            <div class="card-body">
                For all servers its every 6 hours. It usually takes about 1hr to scan and process all the data.
                For warmane servers because of the Population cap there can be a large delay if the bots are put in to a queue, at that time the data is not scanned.
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingEight">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                    Can I download this snapshot and use it in game?
                </button>
            </h2>
        </div>
        <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordionExample">
            <div class="card-body">
                Yes you can download and use the snap shots we create in game. We use the Auctioneer Addon to take a snapshot of the auction house.<br>
                If you click On your server then select realm, Just below the faction you will see the option to download the data. <br>
                The downloaded file will be a AucScan-data.lua. This file needs to be placed <br>
                <b>*wow folder*/WTF/Account/*your_account_name*/SavedVariables/</b><br>
                That should do it.
            </div>
        </div>
    </div>
</div>
</p>