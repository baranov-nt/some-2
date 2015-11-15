<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property integer $images_num
 * @property string $images_label
 * @property string $name
 * @property string $desc
 * @property string $price
 * @property integer $rate
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $category_id
 * @property integer $unit_id
 * @property integer $user_id
 * @property integer $temp
 *
 * @property Carousel[] $carousels
 * @property ImagesOfObject[] $imagesOfObjects
 * @property Order[] $orders
 * @property Category $category
 * @property Unit $unit
 * @property User $user
 * @property Rating[] $ratings
 * @property Sale[] $sales
 */
class Product extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['images_num', 'rate', 'created_at', 'updated_at', 'category_id', 'unit_id', 'user_id', 'temp'], 'integer'],
            [['desc'], 'string'],
            [['price'], 'number'],
            [['images_label'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 64],
            [['name', 'desc', 'price', 'category_id'], 'required', 'on' => 'update']
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
            'name' => 'Name',
            'desc' => 'Desc',
            'price' => 'Price',
            'rate' => 'Rate',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'category_id' => 'Категория товара',
            'unit_id' => 'Unit ID',
            'user_id' => 'User ID',
            'temp' => 'Temp',
        ];
    }

    /* Поведение */
    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarousels()
    {
        return $this->hasMany(Carousel::className(), ['product_id' => 'id']);
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
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Unit::className(), ['id' => 'unit_id']);
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
    public function getRatings()
    {
        return $this->hasMany(Rating::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSales()
    {
        return $this->hasMany(Sale::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function createObject()
    {
        $modelProduct = new Product();
        $modelProduct->images_num = 3;
        $modelProduct->images_label = 'product';
        $modelProduct->desc = '';
        $modelProduct->price = 0;
        $modelProduct->user_id = Yii::$app->user->id;
        $modelProduct->temp = 1;

        $modelProduct->save();

        Yii::$app->session->set('tempModel', 'Product');
        Yii::$app->session->set('tempId', $modelProduct->id);

        return  $modelProduct ? $modelProduct : null;
    }

    /**
     * @return \yii\db\ActiveQuery
     * @var $modelProduct \common\models\Carousel
     */
    public function updateObject($modelProduct)
    {
        $modelProduct->temp = 0;

        $modelProduct->setScenario('update');

        if($modelProduct->save()):
            Yii::$app->session->remove('tempModel');
            Yii::$app->session->remove('tempId');
            return true;
        endif;

        return false;
    }

    /**
     * @param $modelProduct
     * @return \yii\db\ActiveQuery
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    public function deleteObject($modelProduct)
    {
        /* @var $modelProduct \common\models\Carousel */
        /* @var $one \common\models\ImagesOfObject */

        $modelImages = $modelProduct->imagesOfObjects;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach($modelImages as $one):
                $this->deleteImageFile($one->image->path);
                $this->deleteImageFile($one->image->path_small_image);
                $one->delete();
                $one->image->delete();
            endforeach;
            if($modelProduct->delete()):
                $transaction->commit();
            endif;
        } catch (Exception $e) {
            $transaction->rollBack();
        }
    }

    public function deleteImageFile($image_file) {
        if (empty('images/'.$image_file) || !file_exists('images/'.$image_file))
            return false;

        if (!unlink('images/'.$image_file))
            return false;
        return true;
    }
}
