<?php
/* @var $this yii\web\View
 * @var $hello string */
/* @var $carousel common\models\Carousel */
/* @var $one common\models\Carousel */
use yii\bootstrap\Carousel;
use common\widgets\ImageLoad\assets\backendImagesAsset;

if (Yii::$app->user->can('Редактор')):
    Yii::$app->assetManager->forceCopy = true;
endif;

$asset = backendImagesAsset::register($this);

if($carousel):
    foreach($carousel as $one):
        foreach($one->imagesOfObjects as $image):
            $items[] = [
                'content' => '<img src="'.$asset->baseUrl.'/'.$image->image->path.'" style="width:100%"/>',
                'caption' => '<h1>'.$one->header.'</h1><p>'.$one->content.'</p>',
                'options' => [
                    'style' => 'width:100%;' // set the width of the container if you like
                ],
                'active' => true
            ];
        endforeach;
    endforeach;

    echo Carousel::widget([
        'items' => $items,
        'options' => [
            'class' => 'slide',
            'style' => 'width:100%;' // set the width of the container if you like
        ]
    ]);
endif;