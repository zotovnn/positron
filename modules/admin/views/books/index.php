<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="books-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Books', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'filterModel' => $dataProvider,
        'dataProvider' => $dataProvider->search( Yii::$app->request->queryParams),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
//            'isbn',
            'title',
//            'page_count',
            [
                'attribute' => 'published_date',
                'contentOptions' => ['class' => 'table_class', 'style' => 'display:block;'],
                'content' => static function ($data) {
                    return (new DateTime($data->published_date))->format('Y-m-d');
                }
            ],
            //'thumbnail_url:url',
            'short_description:ntext',
            //'long_description:ntext',
            'status',
            'authors:ntext',
            'categories:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
