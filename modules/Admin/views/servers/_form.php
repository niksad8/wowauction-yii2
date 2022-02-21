<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Servers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="servers-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?php
    if(file_exists(Yii::$app->getBasePath().DIRECTORY_SEPARATOR."web".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."servers".DIRECTORY_SEPARATOR.$model->id)){
        echo "<img src='".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."servers".DIRECTORY_SEPARATOR.$model->id."'>";
    }
    ?>
    <?= Html::fileInput("server_logo"); ?>
    <?= $form->field($model, 'desc')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'connect_url')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
