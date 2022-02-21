<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ServersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Servers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="servers-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Servers', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            array('label'=>'Logo','format'=>'raw','value'=>function($data){
                return '<img src="/images/servers/'.$data->id.'" style="width:100px; height:auto;">';
}           ),
            'name',
            'desc:ntext',
            'url:url',
            'connect_url:url',
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
    ]);
    ?>
</div>
