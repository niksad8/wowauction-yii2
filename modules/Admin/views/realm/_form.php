<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Realm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="realm-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'server_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\Servers::find()->all(),'id','name')) ?>

    <?= $form->field($model, 'expansion_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\Expansion::find()->all(),'id','name')) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'desc')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'data')->textarea() ?>
    <?= $form->field($model, 'update_schedule')->dropDownList([
        '3600' =>'Hourly',
        '10800' => 'Every 3 Hours',
        '21600' => 'Every 6 Hours',
        '43200' => 'Every 12 Hours',
        '86400' => 'Every 24 Hours',
    ])?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
