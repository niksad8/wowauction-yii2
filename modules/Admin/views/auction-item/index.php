<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Auction Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auction-item-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Auction Item', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'item_list',
            'ilvl',
            'cat1',
            'cat2',
            //'slot_id',
            //'current_bid',
            //'timeleft_id:datetime',
            //'timescanned:datetime',
            //'icon',
            //'stack',
            //'quality',
            //'level_required',
            //'min_bid',
            //'bid_up_amount',
            //'previous_bid_amount',
            //'user',
            //'auction_id',
            //'itemid',
            //'realm_id',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return  Html::a('', $url, ['class' => 'fa fa-edit']);
                    },
                    'delete' => function ($url, $model, $key) {
                        return  Html::a('', $url, ['class' => 'fa fa-trash']);
                    }
                ]
            ],
        ],
    ]); ?>
</div>
