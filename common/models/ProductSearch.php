<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ProductSearch represents the model behind the search form about `common\models\Product`.
 */
class ProductSearch extends Product
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'images_num', 'rate', 'created_at', 'updated_at', 'category_id', 'unit_id', 'user_id'], 'integer'],
            [['images_label', 'name', 'desc'], 'safe'],
            [['price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $pageSize = 10, $whereParams = '')
    {
        $query = Product::find()->joinWith('category')->where($whereParams);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_ASC]],
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'images_num' => $this->images_num,
            'price' => $this->price,
            'rate' => $this->rate,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'category_id' => $this->category_id,
            'unit_id' => $this->unit_id,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'images_label', $this->images_label])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'category_id', $this->category_id]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchFish($params, $pageSize = 10)
    {
        $query = Product::find()->joinWith('category')->where(['category_id' => 1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_ASC]],
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'images_num' => $this->images_num,
            'price' => $this->price,
            'rate' => $this->rate,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'category_id' => $this->category_id,
            'unit_id' => $this->unit_id,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'images_label', $this->images_label])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'category_id', $this->category_id]);

        return $dataProvider;
    }

    /**
     * Возвращает список возможных категорий товара.
     * Примечание: используется в backend/user/index представлении.
     *
     * @return mixed
     */
    public static function getCategoryList()
    {
        $categories = [];

        foreach (Category::getCategory() as $item_name)
        {
            $categories[$item_name->id] = $item_name->name;
        }

        return $categories;
    }
}
