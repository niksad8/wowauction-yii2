<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScanStatsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Scan Stats';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scan-stats-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Scan Stats', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'realm_id',
            'faction_id',
            'datetime',
            'total_items',
            //'total_listed',
            //'cnt_prices_increased',
            //'cnt_prices_decreased',
            //'total_amt_bid',
            //'avg_price_change',
            //'total_bid_gold',
            //'total_buyout_gold',

            ['class' => 'app\Components\CustomGridButtons'],
        ],
    ]); ?>
</div>
