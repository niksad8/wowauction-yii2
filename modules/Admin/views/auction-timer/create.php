<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AuctionTimer */

$this->title = 'Create Auction Timer';
$this->params['breadcrumbs'][] = ['label' => 'Auction Timers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auction-timer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
