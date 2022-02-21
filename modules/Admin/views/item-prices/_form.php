<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ItemPrices */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="item-prices-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'itemid')->textInput() ?>

    <?= $form->field($model, 'datetime')->textInput() ?>

    <?= $form->field($model, 'bid_mean')->textInput() ?>

    <?= $form->field($model, 'bid_median')->textInput() ?>

    <?= $form->field($model, 'cost_price')->textInput() ?>

    <?= $form->field($model, 'quantity')->textInput() ?>

    <?= $form->field($model, 'bid_median_last_compare')->textInput() ?>

    <?= $form->field($model, 'bid_mean_last_compare')->textInput() ?>

    <?= $form->field($model, 'buyout_mean')->textInput() ?>

    <?= $form->field($model, 'buyout_median')->textInput() ?>

    <?= $form->field($model, 'realm_id')->textInput() ?>

    <?= $form->field($model, 'faction_id')->textInput() ?>

    <?= $form->field($model, 'buyout_median_last_compare')->textInput() ?>

    <?= $form->field($model, 'buyout_mean_last_compare')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
