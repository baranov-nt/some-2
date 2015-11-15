<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 17.10.2015
 * Time: 12:45
 */
/* Поведение для обработки и записи изображения */

namespace common\widgets\ImageLoad\behaviors;

use Yii;
use yii\base\Behavior;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\Image\Box;
use Imagine\Image\Point;
use yii\helpers\Json;
use common\models\Images;
use common\models\ImagesOfObject;
use common\widgets\ImageLoad\models\ImageForm;
use yii\db\Exception;

/* @var $modelImage \common\models\Images
 * @property Images $modelImages
 */

class ImageBehavior extends Behavior
{
    private $imageData;                             // Полученые параметры для редактирования
    private $imageDeleteData;                       // Полученые параметры для удаления

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->imageData = Yii::$app->request->post('phpntCrop');
        $this->imageDeleteData = Yii::$app->request->post('imageData');
    }

    /**
     * @return array
     */
    public function events()
    {
        return [
            ImageForm::EVENT_CREATE_IMAGE => 'createImage',
            ImageForm::EVENT_UPDATE_IMAGE => 'updateImage',
            ImageForm::EVENT_DELETE_IMAGE => 'deleteImage',
        ];
    }

    public function createImage()
    {
        /* @var $modelImages \common\models\Images */
        /* @var $modelImagesOfObject \common\models\ImagesOfObject */

        $paramsCrop = Json::decode($this->imageData['imageCrop']);
        $model = new ImageForm();
        $model->image = UploadedFile::getInstance($model, 'image');

        if($model->validate()):
            $smallFileName = time().'_'.Yii::$app->user->id.'_small.'.$model->image->extension;           // будущее имя миниатюры
            $fileName = time().'_'.Yii::$app->user->id.'.'.$model->image->extension;           // будущее имя

            $modelImages = new Images();
            $modelImages->path_small_image = $this->imageData['imagePath'].$smallFileName;       // изображение с путем
            $modelImages->path = $this->imageData['imagePath'].$fileName;                         // изображение с путем
            $modelImages->size = $model->image->size;                                             // размер изображения
            $modelImages->status = 0;                                                         // статус проверки
            $modelImages->temp = $this->imageData['images_temp'];                               // статус временного файла

            $transaction = Yii::$app->db->beginTransaction();
            try {
                if($modelImages->save()):
                    $modelImagesOfObject = new ImagesOfObject();
                    $modelImagesOfObject->image_id = $modelImages->id;
                    $modelImagesOfObject->object_id = $this->imageData['object_id'];
                    $modelImagesOfObject->label = $this->imageData['images_label'];
                    if($modelImagesOfObject->save()):
                        FileHelper::createDirectory('images/'.$this->imageData['imagePath'], $mode = 509);            // создаем папку
                        if($model->image->saveAs('images/'.$this->imageData['imagePath'].$fileName)):                // файл в папку
                            $image = Image::getImagine();
                            $newImage = $image->open('images/'.$this->imageData['imagePath'].$fileName);              // получаем записаный файл
                            $newImage->rotate($paramsCrop['rotate']);
                            if($newImage->crop(                                                                // обрезаем и пишем картинку
                                new Point($paramsCrop['x'], $paramsCrop['y']),
                                new Box($paramsCrop['width'], $paramsCrop['height']))
                                ->save('images/'.$this->imageData['imagePath'].$fileName)):

                                $newImage = $image->open('images/'.$this->imageData['imagePath'].$fileName);              // создание миниатюры
                                $newImage->thumbnail(new Box($this->imageData['imageSmallWidth'], $this->imageData['imageSmallHeight']))
                                    ->save('images/'.$this->imageData['imagePath'].$smallFileName);
                                $transaction->commit();
                            endif;
                        endif;
                        \Yii::$app->session->set('image', $modelImages->id);                          // если объект сохранился, записываем ID в сессию
                        \Yii::$app->session->remove('error');
                    endif;
                else:
                    \Yii::$app->session->set('error', 'Изображение не добавлено.');         // если все в порядке, пишем в сессию путь к изображениею
                    \Yii::$app->session->remove('image');
                endif;
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        else:
            \Yii::$app->session->set('error', $model->errors['image']['0']); // если все в порядке, пишем в сессию путь к изображениею
            \Yii::$app->session->remove('image');
        endif;
    }

    public function updateImage()
    {
        /* @var $modelImages \common\models\Images */
        /* @var $modelImagesOfObject \common\models\ImagesOfObject */

        $paramsCrop = Json::decode($this->imageData['imageCrop']);
        $model = new ImageForm();
        $model->image = UploadedFile::getInstance($model, 'image');

        if($model->validate()):
            $smallFileName = time().'_'.Yii::$app->user->id.'_small.'.$model->image->extension;           // будущее имя миниатюры
            $fileName = time().'_'.Yii::$app->user->id.'.'.$model->image->extension;           // будущее имя

            $modelImages = $modelImages = Images::findOne($this->imageData['image_id']);

            $deleteFile = $modelImages->path;
            $deleteSmallFile = $modelImages->path_small_image;

            $modelImages->path_small_image = $this->imageData['imagePath'].$smallFileName;       // изображение с путем
            $modelImages->path = $this->imageData['imagePath'].$fileName;                         // изображение с путем
            $modelImages->size = $model->image->size;                                             // размер изображения
            $modelImages->status = 0;                                                         // статус проверки
            $modelImages->temp = $this->imageData['images_temp'];                               // статус временного файла
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if($modelImages->save()):
                    FileHelper::createDirectory('images/'.$this->imageData['imagePath'], $mode = 509);            // создаем папку
                    if($model->image->saveAs('images/'.$this->imageData['imagePath'].$fileName)):                // файл в папку
                        $image = Image::getImagine();
                        $newImage = $image->open('images/'.$this->imageData['imagePath'].$fileName);              // получаем записаный файл
                        $newImage->rotate($paramsCrop['rotate']);
                        if($newImage->crop(                                                                // обрезаем и пишем картинку
                            new Point($paramsCrop['x'], $paramsCrop['y']),
                            new Box($paramsCrop['width'], $paramsCrop['height']))
                            ->save('images/'.$this->imageData['imagePath'].$fileName)):

                            $newImage = $image->open('images/'.$this->imageData['imagePath'].$fileName);              // создание миниатюры
                            $newImage->thumbnail(new Box($this->imageData['imageSmallWidth'], $this->imageData['imageSmallHeight']))
                                ->save('images/'.$this->imageData['imagePath'].$smallFileName);

                            if($this->deleteImageFile($deleteFile) && $this->deleteImageFile($deleteSmallFile))
                                $transaction->commit();
                        endif;
                    endif;
                    \Yii::$app->session->set('image', $modelImages->id);                          // если объект сохранился, записываем ID в сессию
                    \Yii::$app->session->remove('error');
                else:
                    \Yii::$app->session->set('error', 'Изображение не добавлено.');         // если все в порядке, пишем в сессию путь к изображениею
                    \Yii::$app->session->remove('image');
                endif;
            } catch (Exception $e) {
                $this->deleteImageFile('images/'.$this->imageData['imagePath'].$fileName);
                $this->deleteImageFile('images/'.$this->imageData['imagePath'].$smallFileName);
                $transaction->rollBack();
            }
        else:
            \Yii::$app->session->set('error', $model->errors['image']['0']); // если все в порядке, пишем в сессию путь к изображениею
            \Yii::$app->session->remove('image');
        endif;
    }

    /*
     * Поведение для удаления старых файлов и записей
     * */

    public function deleteImage()
    {
        /* @var $modelImages \common\models\Images */
        /* @var $modelImagesOfObject \common\models\ImagesOfObject */

        $paramsImageDeleteData = Json::decode($this->imageDeleteData);

        $modelImages = Images::findOne($paramsImageDeleteData['image_id']);


        $modelImagesOfObject = ImagesOfObject::findOne([
            'image_id' => $paramsImageDeleteData['image_id'],
            'object_id' => $paramsImageDeleteData['object_id']
        ]);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if($modelImagesOfObject->delete()):
                if($modelImages->delete()):
                    if($this->deleteImageFile($modelImages->path) && $this->deleteImageFile($modelImages->path_small_image))
                        $transaction->commit();
                endif;
            endif;
        } catch (Exception $e) {
            $transaction->rollBack();
        }
    }

    public function deleteImageFile($image_file) {
        // проверка файла на сервере
        if (empty('images/'.$image_file) || !file_exists('images/'.$image_file))
            return false;

        // проверка файла на удаление
        if (!unlink('images/'.$image_file))
            return false;
        // если удаление прошло успешно возвращаем true
        return true;
    }
}