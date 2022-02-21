<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ItemPrices */

$this->title = $model->itemid;
$this->params['breadcrumbs'][] = ['label' => 'Item Prices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-prices-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'itemid' => $model->itemid, 'datetime' => $model->datetime, 'realm_id' => $model->realm_id, 'faction_id' => $model->faction_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'itemid' => $model->itemid, 'datetime' => $model->datetime, 'realm_id' => $model->realm_id, 'faction_id' => $model->faction_id], [
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
            'itemid',
            'datetime',
            'bid_mean',
            'bid_median',
            'cost_price',
            'quantity',
            'bid_median_last_compare',
            'bid_mean_last_compare',
            'buyout_mean',
            'buyout_median',
            'realm_id',
            'faction_id',
            'buyout_median_last_compare',
            'buyout_mean_last_compare',
        ],
    ]) ?>

</div>
