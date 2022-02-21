<?php
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DiscordBotSettings */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="discord-bot-settings-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'serverid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'setting_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'value')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
