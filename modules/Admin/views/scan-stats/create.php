<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ScanStats */

$this->title = 'Create Scan Stats';
$this->params['breadcrumbs'][] = ['label' => 'Scan Stats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scan-stats-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
