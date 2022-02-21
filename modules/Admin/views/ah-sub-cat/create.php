<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AhSubCat */

$this->title = 'Create Ah Sub Cat';
$this->params['breadcrumbs'][] = ['label' => 'Ah Sub Cats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ah-sub-cat-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
