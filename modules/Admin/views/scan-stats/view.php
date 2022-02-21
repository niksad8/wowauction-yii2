<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ScanStats */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Scan Stats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scan-stats-view">

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
            'realm_id',
            'faction_id',
            'datetime',
            'total_items',
            'total_listed',
            'cnt_prices_increased',
            'cnt_prices_decreased',
            'total_amt_bid',
            'avg_price_change',
            'total_bid_gold',
            'total_buyout_gold',
        ],
    ]) ?>

</div>
