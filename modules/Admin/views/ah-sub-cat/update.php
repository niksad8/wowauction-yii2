<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AhSubCat */

$this->title = 'Update Ah Sub Cat: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Ah Sub Cats', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ah-sub-cat-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
