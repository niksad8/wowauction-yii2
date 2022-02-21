<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Realm */

$this->title = 'Create Realm';
$this->params['breadcrumbs'][] = ['label' => 'Realms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="realm-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
