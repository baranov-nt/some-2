<?php
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $widget \common\widgets\ImageLoad\ImageLoadWidget */
/* @var $modelImageForm \common\widgets\ImageLoad\ImageForm */
/* @var $attribute string */
/* @var $imagePath string */

echo $this->render(
    '_formAutoload',
    [
        'modelName' => $widget->modelName,
        'id' => $widget->id,
        'object_id' => $widget->object_id,
        'images_num' => $widget->images_num,
        'images_label' => $widget->images_label,
        'images_temp' => $widget->images_temp,
        'imageSmallWidth' => $widget->imageSmallWidth,
        'imageSmallHeight' => $widget->imageSmallHeight,
        'imagesObject' => $widget->imagesObject,
        'modelImageForm' => $modelImageForm,
        'baseUrl' => $widget->baseUrl,
        'imagePath' => $widget->imagePath,
        'noImage' => $widget->noImage,
        'imageClass' => $widget->classesWidget['imageClass'],
        'buttonDeleteClass' => $widget->classesWidget['buttonDeleteClass'],
        'imageContainerClass' => $widget->classesWidget['imageContainerClass'],
        'formImagesContainerClass' => $widget->classesWidget['formImagesContainerClass'],
    ]);

Modal::begin([
    'size' => $widget->sizeModal,
    'header' => '<h2>'.$widget->headerModal.'</h2>',
    'footer' => '
        <div class="btn-group">
            <button type="button" class="btn btn-primary" id="rotate-right-'.$widget->id.'">
                <span class="fa fa-rotate-right gly-spin-right"></span>
            </button>
            <button type="button" class="btn btn-primary" id="rotate-left-'.$widget->id.'">
                <span class="fa fa-rotate-left  gly-spin-left"></span>
            </button>
        </div>
        <button type="button" class="btn btn-primary crop-submit">Применить</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
    ',
    'toggleButton' => false,
    'options' => [
        'id' => 'modal-'.$widget->id,
    ]
]);
?>
    <div class="crop-image-container">

        <?= Html::img('', [
            'id' => 'previewImg-'.$widget->id,
            'class' => 'cropper-image img-responsive',
            'alt' => 'crop-image'
        ]) ?>
    </div>
<?php
Modal::end();

