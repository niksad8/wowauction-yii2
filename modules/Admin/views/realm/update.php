<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Realm */

$this->title = 'Update Realm: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Realms', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="realm-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
