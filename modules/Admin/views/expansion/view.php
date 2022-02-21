<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Expansion */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Expansions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expansion-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            array('label'=>'Logo','format'=>'raw','value'=>'<img src="/images/expansions/'.$model->id.'" style="width:100px; height:auto;">'),
            'version_no',
            'short_name',
        ],
    ]) ?>

</div>
