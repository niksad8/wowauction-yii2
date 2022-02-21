<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ScanStats */

$this->title = 'Update Scan Stats: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Scan Stats', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scan-stats-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
