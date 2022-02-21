<?php
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AuctionItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auction-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'item_list')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ilvl')->textInput() ?>

    <?= $form->field($model, 'cat1')->textInput() ?>

    <?= $form->field($model, 'cat2')->textInput() ?>

    <?= $form->field($model, 'slot_id')->textInput() ?>

    <?= $form->field($model, 'current_bid')->textInput() ?>

    <?= $form->field($model, 'timeleft_id')->textInput() ?>

    <?= $form->field($model, 'timescanned')->textInput() ?>

    <?= $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'stack')->textInput() ?>

    <?= $form->field($model, 'quality')->textInput() ?>

    <?= $form->field($model, 'level_required')->textInput() ?>

    <?= $form->field($model, 'min_bid')->textInput() ?>

    <?= $form->field($model, 'bid_up_amount')->textInput() ?>

    <?= $form->field($model, 'previous_bid_amount')->textInput() ?>

    <?= $form->field($model, 'user')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'auction_id')->textInput() ?>

    <?= $form->field($model, 'itemid')->textInput() ?>

    <?= $form->field($model, 'realm_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
