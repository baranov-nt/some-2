<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CarouselSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Карусель';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="carousel-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить элемент карусели', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'images_num',
            //'images_label',
            [
                'attribute' => 'Изображениe',
                'format' => 'html',
                'value' => function ($data) {
                    $images = '';
                    foreach($data->imagesOfObjects as $one):
                        $images .= Html::img('/images/'.$one->image->path_small_image,
                            [
                                'width' => '100px',
                                'style' => 'margin: 1px;'
                            ]);
                    endforeach;
                    return $images;
                },
            ],
            'header',
            'content',
            // 'product_id',
            'user.phone',
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Action',
                'headerOptions' => ['width' =>'65'],
                'template' => '{view} {update}{delete}',
            ],
        ],
    ]); ?>

</div>
