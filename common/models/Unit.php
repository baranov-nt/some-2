<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "unit".
 *
 * @property integer $id
 * @property integer $weight
 * @property integer $quantity
 * @property string $unit
 *
 * @property Product[] $products
 */
class Unit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'unit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['weight', 'quantity'], 'integer'],
            [['unit'], 'string', 'max' => 11]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'weight' => 'Weight',
            'quantity' => 'Quantity',
            'unit' => 'Unit',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['unit_id' => 'id']);
    }
}
