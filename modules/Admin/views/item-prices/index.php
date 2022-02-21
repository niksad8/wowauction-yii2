<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ItemPricesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Item Prices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-prices-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Item Prices', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'itemid',
            'datetime',
            'bid_mean',
            'bid_median',
            'cost_price',
            //'quantity',
            //'bid_median_last_compare',
            //'bid_mean_last_compare',
            //'buyout_mean',
            //'buyout_median',
            //'realm_id',
            //'faction_id',
            //'buyout_median_last_compare',
            //'buyout_mean_last_compare',

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
