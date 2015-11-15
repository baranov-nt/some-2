<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 27.10.2015
 * Time: 11:09
 */

namespace frontend\controllers;

use Yii;
use common\models\Profile;
use common\widgets\ImageLoad\ImageLoadWidget;
use yii\helpers\Json;
use common\widgets\ImageLoad\models\ImageForm;

class ImagesController extends BehaviorsController
{
    public function actionImageAutoload()
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

        /* @var $model \common\models\Profile */

        if($imageData['modelName'] == 'Profile'):
            $model = Profile::findOne($imageData['object_id']);
        endif;

        $imagesObject = $model->imagesOfObjects;

        return $this->render(
            '@common/widgets/ImageLoad/views/_formAutoload',
            [
                'modelName' => $imageData['modelName'],
                'id' => $imageData['id'],
                'object_id' => $imageData['object_id'],
                'images_num' => $imageData['images_num'],
                'images_label' => $imageData['images_label'],
                'images_temp' => $imageData['images_temp'],
                'imageSmallWidth' => $imageData['imageSmallWidth'],
                'imageSmallHeight' => $imageData['imageSmallHeight'],
                'imagesObject' => $imagesObject,
                'modelImageForm' => $modelImageForm,
                'baseUrl' => $imageData['baseUrl'],
                'imagePath' => $imageData['imagePath'],
                'noImage' => $imageData['noImage'],
                'imageClass' => $imageData['imageClass'],
                'buttonDeleteClass' => $imageData['buttonDeleteClass'],
                'imageContainerClass' => $imageData['imageContainerClass'],
                'formImagesContainerClass' => $imageData['formImagesContainerClass'],
                'error' => $error,
            ]
        );
    }

    /**
     * @return string
     */
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

        /* @var $model \common\models\Profile */

        if($imageData['modelName'] == 'Profile'):
            $model = Profile::findOne($imageData['object_id']);
        endif;

        $imagesObject = $model->imagesOfObjects;

        return $this->render(
            '@common/widgets/ImageLoad/views/_formAutoload',
            [
                'modelName' => $imageData['modelName'],
                'id' => $imageData['id'],
                'object_id' => $imageData['object_id'],
                'images_num' => $imageData['images_num'],
                'images_label' => $imageData['images_label'],
                'images_temp' => $imageData['images_temp'],
                'imagesObject' => $imagesObject,
                'modelImageForm' => $modelImageForm,
                'baseUrl' => $imageData['baseUrl'],
                'imagePath' => $imageData['imagePath'],
                'noImage' => $imageData['noImage'],
                'imageClass' => $imageData['imageClass'],
                'buttonDeleteClass' => $imageData['buttonDeleteClass'],
                'imageContainerClass' => $imageData['imageContainerClass'],
                'formImagesContainerClass' => $imageData['formImagesContainerClass'],
                'error' => $error,
            ]
        );
    }
}