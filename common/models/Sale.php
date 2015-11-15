<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sale".
 *
 * @property integer $id
 * @property integer $percent_sale
 * @property integer $created_at
 * @property integer $product_id
 * @property integer $user_id
 *
 * @property Product $product
 * @property User $user
 */
class Sale extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['percent_sale', 'created_at', 'product_id', 'user_id'], 'integer'],
            [['product_id', 'user_id'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'percent_sale' => 'Percent Sale',
            'created_at' => 'Created At',
            'product_id' => 'Product ID',
            'user_id' => 'User ID',
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
}
