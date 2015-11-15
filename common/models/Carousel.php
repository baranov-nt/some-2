<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * This is the model class for table "carousel".
 *
 * @property integer $id
 * @property integer $images_num
 * @property string $images_label
 * @property string $header
 * @property string $content
 * @property integer $product_id
 * @property integer $user_id
 * @property integer $temp
 *
 * @property Product $product
 * @property User $user
 * @property mixed imagesOfObjects
 */

class Carousel extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'carousel';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['images_num', 'product_id', 'user_id', 'temp'], 'integer'],
            [['user_id'], 'required'],
            [['images_label'], 'string', 'max' => 32],
            [['header', 'content'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'images_num' => 'Images Num',
            'images_label' => 'Images Label',
            'header' => 'Заголовок',
            'content' => 'Контент',
            'product_id' => 'Product ID',
            'user_id' => 'User ID',
            'temp' => 'Temp',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImagesOfObjects()
    {
        return $this->hasMany(ImagesOfObject::className(),
            [
                'object_id' => 'id',
                'label' => 'images_label'
            ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOneImagesOfObjects()
    {
        return $this->hasOne(ImagesOfObject::className(),
            [
                'object_id' => 'id',
                'label' => 'images_label'
            ]);
    }

    /**
     * Создание нового элемента
     * @return \yii\db\ActiveQuery
     */
    public function createObject()
    {
        $modelCarousel = new Carousel();
        $modelCarousel->images_num = 1;
        $modelCarousel->images_label = 'carousel';
        $modelCarousel->temp = 1;                               // при создании нового меню утанавливаем флаг "временный" $modelCarousel->temp
        $modelCarousel->user_id = Yii::$app->user->id;

        $modelCarousel->save();

        Yii::$app->session->set('tempModel', 'Carousel');
        Yii::$app->session->set('tempId', $modelCarousel->id);

        return  $modelCarousel ? $modelCarousel : null;
    }

    /**
     * Изменение суфествующего элемента
     * @return \yii\db\ActiveQuery
     * @var $modelCarousel \common\models\Carousel
     */
    public function updateObject($modelCarousel)                  // принемает загруженный объект из $_POST
    {
        $modelCarousel->temp = 0;                               // при обновлении меню сбрасываем флаг "временный" $modelCarousel->temp

        if($modelCarousel->save()):
            Yii::$app->session->remove('tempModel');            // ощищаем сессии
            Yii::$app->session->remove('tempId');
            return true;
        endif;

        return false;
    }

    /**
     * Удаление элемента
     * @param $modelCarousel
     * @return \yii\db\ActiveQuery
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    public function deleteObject($modelCarousel)
    {
        /* @var $modelCarousel \common\models\Carousel */
        /* @var $one \common\models\ImagesOfObject */

        $modelImages = $modelCarousel->imagesOfObjects;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach($modelImages as $one):                                          // перебираем объекты изображений и удаляем сами изображения
                $this->deleteImageFile($one->image->path);
                $this->deleteImageFile($one->image->path_small_image);
                $one->delete();
                $one->image->delete();
            endforeach;
            if($modelCarousel->delete()):                                         // удаляем объекты изображений
                $transaction->commit();
            endif;
        } catch (Exception $e) {
            $transaction->rollBack();
        }
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
