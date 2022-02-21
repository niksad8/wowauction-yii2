<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Expansion */

$this->title = 'Create Expansion';
$this->params['breadcrumbs'][] = ['label' => 'Expansions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expansion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
