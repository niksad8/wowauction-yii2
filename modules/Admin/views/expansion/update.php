<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Expansion */

$this->title = 'Update Expansion: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Expansions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="expansion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
