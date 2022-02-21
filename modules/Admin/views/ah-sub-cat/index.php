<?php

use yii\bootstrap4\Html;

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ah Sub Cats';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ah-sub-cat-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Ah Sub Cat', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'name',
            'parent.name::AH Main Category',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return  Html::a('', $url, ['class' => 'fa fa-edit']);
                    },
                    'delete' => function ($url, $model, $key) {
                        return  Html::a('', $url, ['class' => 'fa fa-trash']);
                    }
                ]
            ],
        ],
    ]); ?>
</div>
