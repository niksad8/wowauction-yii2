<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AuctionItem */

$this->title = 'Create Auction Item';
$this->params['breadcrumbs'][] = ['label' => 'Auction Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auction-item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
