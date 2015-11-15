<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Продукты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить продукт', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'showOnEmpty'=>false,
        'pager' => [                                                            // параметры для пагинации
            'firstPageLabel' => 'первая',
            'lastPageLabel' => 'последняя',
            'nextPageLabel' => 'следующая',
            'prevPageLabel' => 'предыдущая',
            'maxButtonCount' => 3,                                              // количество цифровых кнопок
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'images_num',
            //'images_label',
            [
                'attribute' => 'Изображения',
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
            'name',
            'desc:ntext',
            'price',
            [
                'attribute'=>'category_id',
                'filter' => $searchModel->categoryList,
                'value' => function ($data) {
                    return $data->category->name;
                },
            ],
            // 'rate',
            // 'created_at',
            // 'updated_at',
            // 'category_id',
            // 'unit_id',
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
