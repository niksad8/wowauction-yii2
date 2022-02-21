<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DiscordBotSettings */

$this->title = $model->setting_name;
$this->params['breadcrumbs'][] = ['label' => 'Discord Bot Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="discord-bot-settings-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->setting_name], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->setting_name], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'serverid',
            'setting_name',
            'value:ntext',
        ],
    ]) ?>

</div>
