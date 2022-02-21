<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SlotTranslation */

$this->title = 'Create Slot Translation';
$this->params['breadcrumbs'][] = ['label' => 'Slot Translations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="slot-translation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
