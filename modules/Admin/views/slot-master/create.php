<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SlotMaster */

$this->title = 'Create Slot Master';
$this->params['breadcrumbs'][] = ['label' => 'Slot Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="slot-master-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
