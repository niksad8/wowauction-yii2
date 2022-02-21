<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ItemPricesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="item-prices-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'itemid') ?>

    <?= $form->field($model, 'datetime') ?>

    <?= $form->field($model, 'bid_mean') ?>

    <?= $form->field($model, 'bid_median') ?>

    <?= $form->field($model, 'cost_price') ?>

    <?php // echo $form->field($model, 'quantity') ?>

    <?php // echo $form->field($model, 'bid_median_last_compare') ?>

    <?php // echo $form->field($model, 'bid_mean_last_compare') ?>

    <?php // echo $form->field($model, 'buyout_mean') ?>

    <?php // echo $form->field($model, 'buyout_median') ?>

    <?php // echo $form->field($model, 'realm_id') ?>

    <?php // echo $form->field($model, 'faction_id') ?>

    <?php // echo $form->field($model, 'buyout_median_last_compare') ?>

    <?php // echo $form->field($model, 'buyout_mean_last_compare') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
