<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ScanStats */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scan-stats-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'realm_id')->textInput() ?>

    <?= $form->field($model, 'faction_id')->textInput() ?>

    <?= $form->field($model, 'datetime')->textInput() ?>

    <?= $form->field($model, 'total_items')->textInput() ?>

    <?= $form->field($model, 'total_listed')->textInput() ?>

    <?= $form->field($model, 'cnt_prices_increased')->textInput() ?>

    <?= $form->field($model, 'cnt_prices_decreased')->textInput() ?>

    <?= $form->field($model, 'total_amt_bid')->textInput() ?>

    <?= $form->field($model, 'avg_price_change')->textInput() ?>

    <?= $form->field($model, 'total_bid_gold')->textInput() ?>

    <?= $form->field($model, 'total_buyout_gold')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
