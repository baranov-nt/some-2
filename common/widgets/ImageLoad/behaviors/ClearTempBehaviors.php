<?php
/**
 * Created by PhpStorm.
 * User: phpNT
 * Date: 25.10.2015
 * Time: 9:22
 * Поведение удаляет не созданный объект и его изображения из БД
 */

namespace common\widgets\ImageLoad\behaviors;

use Yii;
use yii\base\Behavior;
use yii\web\Controller;
use yii\db\Exception;
use common\models\Carousel;
use common\models\Product;

class ClearTempBehaviors   extends Behavior {

    /* @var $tempModel \common\models\Carousel */

    public $tempModel;                  // Получаем название модель
    public $tempId;                     // Получаем id объекта модели

    private $tempDelete = false;

    public function init()
    {
        parent::init();
        /* Инициализация объекта, если он есть в сессии */
        if($this->tempModel == 'Carousel'):
            if($this->tempModel = Carousel::findOne($this->tempId)):                // Если объект загружен
                $this->tempDelete = true;                                           // Устанавливаем флаг tempDelete для удаления
            endif;
        endif;
        if($this->tempModel == 'Product'):
            if($this->tempModel = Product::findOne($this->tempId)):                // Если объект загружен
                $this->tempDelete = true;                                           // Устанавливаем флаг tempDelete для удаления
            endif;
        endif;
    }

    public function events()
    {
        /* Поведение срабатывает перед действием контроллера */
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeAction'                       // Перед каждым действием запускаем метод beforeAction
        ];
    }

    /**
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    public function beforeAction()
    {
        /* @var $one \common\models\ImagesOfObject */
        if(
            $this->tempDelete == true                                               // если флаг tempDelete установлен, т.е. объект модели загружен
            && Yii::$app->controller->action->id != 'create'                        // и был сделан переход на иное, кроме create действие
            && Yii::$app->controller->id != 'images'
        ):
            $modelImages = $this->tempModel->imagesOfObjects;

            $transaction = Yii::$app->db->beginTransaction();
            try {
            foreach($modelImages as $one):                                          // перебираем объекты изображений и удаляем сами изображения
                $this->deleteImageFile($one->image->path);
                $this->deleteImageFile($one->image->path_small_image);
                $one->delete();
                $one->image->delete();
            endforeach;
            if($this->tempModel->delete()):                                         // удаляем объекты изображений
                Yii::$app->session->remove('tempModel');                            // ощищаем сессии
                Yii::$app->session->remove('tempId');
                $transaction->commit();
            endif;
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        endif;
    }


    public function deleteImageFile($image_file) {                                  // метод для удаления файлов
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