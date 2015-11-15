<?php
/**
 * Created by PhpStorm.
 * User: phpNT
 * Date: 23.10.2015
 * Time: 22:01
 */

/* В представлении ---------------------------------------------------------------------------------------------------------------------------------------------------------- */
/*use common\widgets\ImageLoad\ImageLoadWidget;

echo ImageLoadWidget::widget([
    'id' => 'load-avatar',                          // суффикс ID для основных форм виджета
    'object_id' => Yii::$app->user->id,              // ID объекта, к которому привязаны изображения
    'imagesObject' => $imagesObject,                // объект с загруженными для модели изображениями
    'images_num' => $modelProfile->images_num,       // максимальное количество изображений
    'images_label' => $modelProfile->images_label,       // максимальное количество изображений
    'cropUrl' => Url::to(['main/image-autoload']),  // маршрут для обработки изображения
    'deleteUrl' => Url::to(['main/delete-avatar']),  // маршрут для удаления изображения
    'headerModal' => 'Загрузить аватар',            // заголовок в модальном окне
    'sizeModal' => 'modal-sm',                      // размер модального окна
    'imagePath' => 'images/avatars/'.Yii::$app->user->id.'/', // путь, куда будут записыватся изображения
    'noImage' => 'images/avatars/noavatar.png',    // картинка, если изображение отсутствует
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
    ]]);*/

/* В контроллере ---------------------------------------------------------------------------------------------------------------------------------------------------------- */
/*public function actionImageAutoload()
{
    $imageData = Yii::$app->request->post('phpntCrop');

    $modelImageForm = new ImageForm();

    if($imageData['image_id'] == '0'):
        $modelImageForm->createImage();
    else:
        $modelImageForm->updateImage();
    endif;

    if(Yii::$app->session->get('error')):
        $error = Yii::$app->session->get('error');
    else:
        $error = false;
    endif;

    // @var $modelProfile \common\models\Profile

    $modelProfile = $modelProfile = Profile::findOne(Yii::$app->user->id);

    $imagesObject = $modelProfile->imagesOfObjects;

    return $this->render(
        '@common/widgets/ImageLoad/views/_formAutoload',
        [
            'id' => $imageData['id'],
            'object_id' => $imageData['object_id'],
            'images_num' => $imageData['images_num'],
            'images_label' => $imageData['images_label'],
            'imagesObject' => $imagesObject,
            'modelImageForm' => $modelImageForm,
            'cropUrl' => $imageData['cropUrl'],
            'imagePath' => $imageData['imagePath'],
            'noImage' => $imageData['noImage'],
            'error' => $error,
        ]
    );
}

public function actionDeleteAvatar()
{
    $imageData = Json::decode(Yii::$app->request->post('imageData'));
    $modelImageForm = new ImageForm();
    $modelImageForm->deleteImage();

    if(Yii::$app->session->get('error')):
        echo $error = Yii::$app->session->get('error');
    else:
        $error = false;
    endif;

    // @var $modelProfile \common\models\Profile
    $modelProfile = $modelProfile = Profile::findOne(Yii::$app->user->id);

    $imagesObject = $modelProfile->imagesOfObjects;

    return $this->render(
        '@common/widgets/ImageLoad/views/_formAutoload',
        [
            'id' => $imageData['id'],
            'object_id' => $imageData['object_id'],
            'images_num' => $imageData['images_num'],
            'images_label' => $imageData['images_label'],
            'imagesObject' => $imagesObject,
            'modelImageForm' => $modelImageForm,
            'cropUrl' => $imageData['cropUrl'],
            'imagePath' => $imageData['imagePath'],
            'noImage' => $imageData['noImage'],
            'error' => $error,
        ]
    );
}*/

/* В модели ---------------------------------------------------------------------------------------------------------------------------------------------------------- */
/* 1) Profile */

/**
 * Один пользователь может иметь много аватарок. Промежуточная таблица images_of_object
 *
 * @return \yii\db\ActiveQuery
 */

/*public function getImagesOfObjects()
{
    return $this->hasMany(ImagesOfObject::className(),
        [
            'object_id' => 'user_id',
            'label' => 'images_label'
        ]);
}*/

/* Миграции в папке  ---------------------------------------------------------------------------------------------------------------------------------------------------------- */
