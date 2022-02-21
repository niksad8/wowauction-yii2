<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AuctionTimer */

$this->title = 'Update Auction Timer: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Auction Timers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="auction-timer-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
