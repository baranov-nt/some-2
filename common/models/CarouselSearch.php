<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Carousel;

/**
 * CarouselSearch represents the model behind the search form about `common\models\Carousel`.
 */
class CarouselSearch extends Carousel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'images_num', 'product_id', 'user_id'], 'integer'],
            [['images_label', 'header', 'content'], 'safe'],
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
    public function search($params)
    {
        $query = Carousel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'product_id' => $this->product_id,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'images_label', $this->images_label])
            ->andFilterWhere(['like', 'header', $this->header])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
