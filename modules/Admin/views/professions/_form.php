<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use \yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Professions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="professions-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'icon_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'spell_id')->textInput() ?>
    <?= $form->field($model,'expansion_id')->dropDownList(ArrayHelper::map(\app\models\Expansion::find()->all(),'id','name')); ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
