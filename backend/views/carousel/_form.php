<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\widgets\ImageLoad\ImageLoadWidget;

/* @var $this yii\web\View */
/* @var $modelCarousel common\models\Carousel */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-md-12">
        <?php
        echo ImageLoadWidget::widget([
            'modelName' => 'Carousel',
            'id' => 'load-image',                                       // суффикс ID для основных форм виджета
            'object_id' => $modelCarousel->id,                          // ID объекта, к которому привязаны изображения
            'imagesObject' => $modelCarousel->imagesOfObjects,          // объект с загруженными для модели изображениями
            'images_num' => $modelCarousel->images_num,                 // максимальное количество изображений
            'images_label' => $modelCarousel->images_label,             // максимальное количество изображений
            'images_temp' => 0,       // указываем временной изображение или нет
            'imageSmallWidth' => 360,                       // ширина миниатюры
            'imageSmallHeight' => 200,                      // высота миниатюры
            'headerModal' => 'Загрузить изображение карусели',                        // заголовок в модальном окне
            'sizeModal' => 'modal-md',                                  // размер модального окна
            'baseUrl' => '/images/',                        // основной путь к изображениям
            'imagePath' => 'carousel/images/',   // путь, куда будут записыватся изображения
            'noImage' => 'carousel/noImage.png',                 // картинка, если изображение отсутствует
            'classesWidget' => [
                'imageClass' => 'imageCarousel',
                'buttonDeleteClass' => 'btn btn-xs btn-danger btn-imageDeleteCarousel glyphicon glyphicon-trash glyphicon',
                'imageContainerClass' => 'imageContainerCarousel',
                'formImagesContainerClass' => 'formImageContainerCarousel',
            ],
            'pluginOptions' => [                            // настройки плагина
                'aspectRatio' => 16/9,                      // установите соотношение сторон рамки обрезки. По умолчанию свободное отношение.
                'strict' => true,                           // true - рамка не может вызодить за холст, false - может
                'guides' => true,                           // показывать пунктирные линии в рамке
                'center' => true,                           // показывать центр в рамке изображения изображения
                'autoCrop' => true,                         // показывать рамку обрезки при загрузке
                'autoCropArea' => 0.5,                      // площидь рамки на холсте изображения при autoCrop (1 = 100% - 0 - 0%)
                'dragCrop' => true,                         // создание новой рамки при клики в свободное место хоста (false - нельзя)
                'movable' => true,                          // перемещать изображение холста (false - нельзя)
                'rotatable' => true,                        // позволяет вращать изображение
                'scalable' => true,                         // мастабирование изображения
                'zoomable' => true,
                'preview' => '.img-preview',                // класс превью
            ],
            'cropBoxData' => [                              // начальные настройки рамки // cropBoxData = { left: 10, top: 10, width: 160, height:200 }
                'left' => 10,                               // смещение слева
                'top' => 10,                                // смещение вниз
                //'width' => 160,                             // ширина
                //'height' => 200                             // высота
            ],
            'canvasData' => [                               // начальные настройки холста
                //'width' => 500,                             // ширина
                //'height' => 500                             // высота
            ]]);
        ?>
    </div>
</div>

<div class="carousel-form">
    <?php $form = ActiveForm::begin(); ?>

    <?php /*echo $form->field($modelCarousel, 'images_num')->textInput()*/ ?>

    <?php /*echo $form->field($modelCarousel, 'images_label')->textInput(['maxlength' => true])*/ ?>

    <?= $form->field($modelCarousel, 'header')->textInput(['maxlength' => true]) ?>

    <?= $form->field($modelCarousel, 'content')->textInput(['maxlength' => true]) ?>

    <?php /*echo $form->field($modelCarousel, 'product_id')->textInput()*/ ?>

    <?php echo $form->field($modelCarousel->user, 'email', ['template'=>'{label}<br>'.$modelCarousel->user->email]) ?>

    <div class="form-group">
        <?= Html::submitButton((Yii::$app->controller->action->id == 'create') ? 'Создать меню' : 'Изменить меню', ['class' => (Yii::$app->controller->action->id == 'create') ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
