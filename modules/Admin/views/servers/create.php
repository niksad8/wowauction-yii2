<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Servers */

$this->title = 'Create Servers';
$this->params['breadcrumbs'][] = ['label' => 'Servers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="servers-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
