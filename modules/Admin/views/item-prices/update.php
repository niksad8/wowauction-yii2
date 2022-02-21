<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ItemPrices */

$this->title = 'Update Item Prices: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Item Prices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->itemid, 'url' => ['view', 'itemid' => $model->itemid, 'datetime' => $model->datetime, 'realm_id' => $model->realm_id, 'faction_id' => $model->faction_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="item-prices-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
