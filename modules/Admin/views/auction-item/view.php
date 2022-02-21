<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AuctionItem */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Auction Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auction-item-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'item_list',
            'ilvl',
            'cat1',
            'cat2',
            'slot_id',
            'current_bid',
            'timeleft_id:datetime',
            'timescanned:datetime',
            'icon',
            'stack',
            'quality',
            'level_required',
            'min_bid',
            'bid_up_amount',
            'previous_bid_amount',
            'user',
            'auction_id',
            'itemid',
            'realm_id',
        ],
    ]) ?>

</div>
