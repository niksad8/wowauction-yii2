<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'server_id')->dropDownList(); ?>

    <?= $form->field($model, 'realm_id')->textInput() ?>

    <?= $form->field($model, 'last_login')->input('date') ?>

    <?= $form->field($model, 'created_at')->input('date') ?>

    <?= $form->field($model, 'enabled')->checkbox() ?>

    <?= $form->field($model, 'email_address')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
