<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\widgets\ImageLoad\ImageLoadWidget;

/* @var $this yii\web\View */
/* @var $modelProduct common\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-md-12">
        <?php
        echo ImageLoadWidget::widget([
            'modelName' => 'Product',
            'id' => 'load-image',                                       // суффикс ID для основных форм виджета
            'object_id' => $modelProduct->id,                          // ID объекта, к которому привязаны изображения
            'imagesObject' => $modelProduct->imagesOfObjects,          // объект с загруженными для модели изображениями
            'images_num' => $modelProduct->images_num,                 // максимальное количество изображений
            'images_label' => $modelProduct->images_label,             // максимальное количество изображений
            'images_temp' => 0,       // указываем временной изображение или нет
            'imageSmallWidth' => 360,                       // ширина миниатюры
            'imageSmallHeight' => 200,                      // высота миниатюры
            'headerModal' => 'Загрузить изображение товара',                        // заголовок в модальном окне
            'sizeModal' => 'modal-md',                                  // размер модального окна
            'baseUrl' => '/images/',                        // основной путь к изображениям
            'imagePath' => 'product/images/',   // путь, куда будут записыватся изображения
            'noImage' => 'product/noImage.png',                 // картинка, если изображение отсутствует
            'classesWidget' => [
                'imageClass' => 'imageProduct',
                'buttonDeleteClass' => 'btn btn-xs btn-danger btn-imageDeleteProduct glyphicon glyphicon-trash glyphicon',
                'imageContainerClass' => 'imageContainerProduct',
                'formImagesContainerClass' => 'formImageContainerProduct',
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

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php /*echo $form->field($modelProduct, 'images_num')->textInput()*/ ?>

    <?php /*echo $form->field($modelProduct, 'images_label')->textInput(['maxlength' => true])*/ ?>

    <?= $form->field($modelProduct, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($modelProduct, 'desc')->textarea(['rows' => 6]) ?>

    <?= $form->field($modelProduct, 'price')->textInput(['maxlength' => true]) ?>

    <?php /*echo $form->field($modelProduct, 'rate')->textInput()*/ ?>

    <?php /*echo $form->field($modelProduct, 'created_at')->textInput(['value' => Yii::$app->formatter->asDatetime($modelProduct->created_at)])*/ ?>

    <?php /*echo  $form->field($modelProduct, 'updated_at')->textInput(['value' => Yii::$app->formatter->asDatetime($modelProduct->updated_at)])*/ ?>

    <?php
    $categoryList = ArrayHelper::map(\common\models\Category::find()->all(), 'id', 'name');
    echo $form->field($modelProduct, 'category_id')->dropDownList(
        $categoryList,           // Flat array ('id'=>'label')
        ['prompt'=>'Категория товара']    // options
    );
    ?>

    <?php /*echo $form->field($modelProduct, 'unit_id')->textInput()*/ ?>

    <?php /*echo $form->field($modelProduct, 'user_id')->textInput()*/ ?>

    <?php echo $form->field($modelProduct, 'created_at', ['template'=>'{label}<br>'.Yii::$app->formatter->asDatetime($modelProduct->created_at)]) ?>
    <?php echo $form->field($modelProduct, 'updated_at', ['template'=>'{label}<br>'.Yii::$app->formatter->asDatetime($modelProduct->updated_at)]) ?>
    <?php echo $form->field($modelProduct->user, 'email', ['template'=>'{label}<br>'.$modelProduct->user->email]) ?>

    <div class="form-group">
        <?= Html::submitButton((Yii::$app->controller->action->id == 'create') ? 'Добавить продукт' : 'Изменить продукт', ['class' => (Yii::$app->controller->action->id == 'create') ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
