<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AhMainCat */

$this->title = 'Create Ah Main Cat';
$this->params['breadcrumbs'][] = ['label' => 'Ah Main Cats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ah-main-cat-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
