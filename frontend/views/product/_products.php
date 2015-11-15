<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 09.11.2015
 * Time: 13:17
 */

/* Вывод всех продуктов */

/*
 * Принимеет следующие свойства:
 *      $model - объект элемента
 *      $key - id элемента
 *      $index - порядковый номер элемента от 0. На каждой странице считается снова
 *      $widget - объект виджета
*/

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $one common\models\ImagesOfObject */


use yii\bootstrap\Carousel;
use common\widgets\ImageLoad\assets\backendImagesAsset;
use yii\helpers\Html;

if (Yii::$app->user->can('Редактор')):
    Yii::$app->assetManager->forceCopy = true;
endif;

$asset = backendImagesAsset::register($this);
?>
<h1><?= $model->name ?></h1>
<?php
if(count($model->imagesOfObjects) > 1):
    foreach($model->imagesOfObjects as $one):
        $items[] = [
            'content' => Html::img($asset->baseUrl.'/'.$one->image->path_small_image, [
                'style' => 'width: 100%'
            ]),
            //'caption' => '<h5>'.$model->name.'</h5><p>'.$model->desc.'</p>',
            //'caption' => '<h5>'.$model->name.'</h5><p>'.$model->desc.'</p>',
            'options' => [
                'style' => 'width:100%;' // set the width of the container if you like
            ],
            'active' => false
        ];
    endforeach;
    echo Carousel::widget([
        'items' => $items,
        'options' => [
            'data-interval' => 0,
            'class' => 'slide',
            'style' => 'width:100%;' // set the width of the container if you like
        ],
        //'controls' => ['&lsaquo;', '&rsaquo;'],     // Стрелочки вперед - назад
        'controls' => ['', ''],                     // Стрелочки вперед - назад
        'showIndicators' => true,                   // отображать индикаторы (кругляшки)

    ]);
else:
    foreach($model->imagesOfObjects as $one):
        echo Html::img($asset->baseUrl.'/'.$one->image->path_small_image, [
            'style' => 'width: 100%'
        ]);
    endforeach;
endif;
?>
<p><?= $model->desc.' ('.$model->category->name.')' ?></p>
