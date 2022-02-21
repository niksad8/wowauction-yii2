<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ScanStatsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scan-stats-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'realm_id') ?>

    <?= $form->field($model, 'faction_id') ?>

    <?= $form->field($model, 'datetime') ?>

    <?= $form->field($model, 'total_items') ?>

    <?php // echo $form->field($model, 'total_listed') ?>

    <?php // echo $form->field($model, 'cnt_prices_increased') ?>

    <?php // echo $form->field($model, 'cnt_prices_decreased') ?>

    <?php // echo $form->field($model, 'total_amt_bid') ?>

    <?php // echo $form->field($model, 'avg_price_change') ?>

    <?php // echo $form->field($model, 'total_bid_gold') ?>

    <?php // echo $form->field($model, 'total_buyout_gold') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
