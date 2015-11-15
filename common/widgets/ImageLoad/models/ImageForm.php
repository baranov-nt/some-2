<?php
/**
 * Created by PhpStorm.
 * User: phpNT
 * Date: 18.10.2015
 * Time: 12:07
 */

namespace common\widgets\ImageLoad\models;

use Yii;
use yii\base\Model;
use common\widgets\ImageLoad\behaviors\ImageBehavior;

class ImageForm extends Model
{
    const EVENT_CREATE_IMAGE = 'createImage';
    const EVENT_UPDATE_IMAGE = 'updateImage';
    const EVENT_DELETE_IMAGE = 'deleteImage';

    public $image;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image'], 'file',
                'skipOnEmpty' => false,
                'extensions' => 'gif, jpeg, jpg, png',
                'mimeTypes'=>'image/gif, image/jpeg, image/jpg, image/png',
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'image' => 'Изображение',
        ];
    }

    public function behaviors(){
        return [
            // Поведение для обработки и записи изображения
            [
                'class' => ImageBehavior::className(),
            ],
        ];
    }

    public  function  createImage() {
        $this->trigger(self::EVENT_CREATE_IMAGE);                         // вызываем событие EVENT_CROP_AVATAR в поведении ImageBehavior
    }

    public  function  updateImage() {
        $this->trigger(self::EVENT_UPDATE_IMAGE);                         // вызываем событие EVENT_CROP_AVATAR в поведении ImageBehavior
    }

    public  function  deleteImage() {

        $this->trigger(self::EVENT_DELETE_IMAGE);                       // вызываем событие EVENT_CROP_AVATAR в поведении ImageBehavior
    }
}