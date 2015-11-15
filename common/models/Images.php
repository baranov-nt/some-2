<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "images".
 *
 * @property integer $id
 * @property string $path
 * @property string $path_small_image
 * @property integer $size
 * @property integer $status
 * @property integer $temp
 *
 * @property ImagesOfObject[] $imagesOfObjects
 * @property Profile[] $profiles
 */
class Images extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['path_small_image', 'path'], 'required'],
            [['size', 'status', 'temp'], 'integer'],
            [['path'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'path_small_image' => 'Path Small Image',
            'path' => 'Path',
            'size' => 'Size',
            'status' => 'Status',
            'temp' => 'Temp',
        ];
    }

    /* Поведения */
    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * Одна картинка может принадлежать нескольким объектам. Промежуточная таблица images_of_object
     *
     * @return \yii\db\ActiveQuery
     */
    public function getImagesOfObjects()
    {
        return $this->hasMany(ImagesOfObject::className(), ['id_image' => 'id']);
    }
}
