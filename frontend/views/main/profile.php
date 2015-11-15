<?php
use yii\widgets\ActiveForm;
use common\widgets\PjaxField\PjaxFieldWidget;
use common\widgets\ImageLoad\ImageLoadWidget;
use common\widgets\SocialLinks\ShareBar;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $modelProfile common\models\Profile */
/* @var $image common\models\ImagesOfObject */
/* @var $modelUser common\models\User */
/* @var $form ActiveForm */
?>
<div class="main-profile">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <?php
            echo ImageLoadWidget::widget([
                'modelName' => 'Profile',
                'id' => 'load-avatar',                          // суффикс ID для основных форм виджета
                'object_id' => $modelProfile->user_id,              // ID объекта, к которому привязаны изображения
                'imagesObject' => $modelProfile->imagesOfObjects,    // объект с загруженными для модели изображениями
                'images_num' => $modelProfile->images_num,       // максимальное количество изображений
                'images_label' => $modelProfile->images_label,       // метка для изображения
                'images_temp' => 0,       // указываем временной изображение или нет (0 = нет)
                'imageSmallWidth' => 150,                       // ширина миниатюры
                'imageSmallHeight' => 150,                      // высота миниатюры
                'headerModal' => 'Загрузить аватар',            // заголовок в модальном окне
                'sizeModal' => 'modal-md',                      // размер модального окна
                'baseUrl' => '/images/',                        // основной путь к изображениям
                'imagePath' => 'avatars/users/'.Yii::$app->user->id.'/', // путь, куда будут записыватся изображения
                'noImage' => 'avatars/noavatar.png',    // картинка, если изображение отсутствует
                'classesWidget' => [
                    'imageClass' => 'imageAvatar',
                    'buttonDeleteClass' => 'btn btn-xs btn-danger btn-imageDeleteAvatar glyphicon glyphicon-trash glyphicon',
                    'imageContainerClass' => 'imageContainerAvatar',
                    'formImagesContainerClass' => 'formImageContainerAvatar',
                ],
                'pluginOptions' => [                            // настройки плагина
                    'aspectRatio' => 1/1,                       // установите соотношение сторон рамки обрезки. По умолчанию свободное отношение.
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
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <?php
            echo PjaxFieldWidget::widget([
                'route' => '/main/user',
                'model' => $modelUser,
                'id' => Yii::$app->user->id,
                'attribute' => 'phone',
            ]);
            echo PjaxFieldWidget::widget([
                'route' => '/main/profile',
                'model' => $modelProfile,
                'attribute' => 'first_name',
            ]);
            echo PjaxFieldWidget::widget([
                'route' => '/main/profile',
                'model' => $modelProfile,
                'attribute' => 'second_name',
            ]);
            echo PjaxFieldWidget::widget([
                'route' => '/main/profile',
                'model' => $modelProfile,
                'attribute' => 'middle_name',
            ]);
            ?>
            <div class="col-md-3">
                <?php
                echo ShareBar::widget([
                    'title' => 'Title Content',                                     // Название
                    'media' => 'image.jpg',                                         // Медиа контент
                    'url' => ['main/view-profile', 'id' => $modelProfile->user_id],       // Ссылка страницы, с которой делимся
                    'networks' => [
                        //'Email',
                        //'Github',
                        'GooglePlus',
                        'Facebook',
                        'Vk',
                        //'Twitter',
                        //'LinkedIn',
                        //'Hackernews',
                        //'Pinterest',
                        //'Pocket',
                        //'Reddit',
                        //'Tumblr',
                        //'Youtube'
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
