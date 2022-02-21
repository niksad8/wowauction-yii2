<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DiscordBotSettings */

$this->title = 'Update Discord Bot Settings: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Discord Bot Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->setting_name, 'url' => ['view', 'id' => $model->setting_name]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="discord-bot-settings-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
