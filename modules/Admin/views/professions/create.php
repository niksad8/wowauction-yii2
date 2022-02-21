<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Professions */

$this->title = 'Create Professions';
$this->params['breadcrumbs'][] = ['label' => 'Professions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="professions-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
