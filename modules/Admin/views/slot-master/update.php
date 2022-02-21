<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SlotMaster */

$this->title = 'Update Slot Master: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Slot Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="slot-master-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
