<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use \yii\helpers\ArrayHelper;
use app\models\AhMainCat;

/* @var $this yii\web\View */
/* @var $model app\models\AhSubCat */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ah-sub-cat-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parent_id')->dropDownList(ArrayHelper::map(AhMainCat::find()->all(),'id','name')) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
