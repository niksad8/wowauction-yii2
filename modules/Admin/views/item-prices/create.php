<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ItemPrices */

$this->title = 'Create Item Prices';
$this->params['breadcrumbs'][] = ['label' => 'Item Prices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-prices-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
