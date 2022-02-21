<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DiscordBotSettings */

$this->title = 'Create Discord Bot Settings';
$this->params['breadcrumbs'][] = ['label' => 'Discord Bot Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="discord-bot-settings-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
