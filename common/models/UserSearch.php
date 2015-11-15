<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\rbac\models\AuthItem;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['phone', 'email', 'password_hash', 'auth_key', 'secret_key', 'item_name', 'status'], 'safe'],
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
    public function search($params, $pageSize = 10, $theCreator = false)
    {
        $query = User::find()->joinWith('role');   // Подключаем таблицу ролей

        // не показываем админам создателей
        if ($theCreator === false)
        {
            $query->where(['!=', 'item_name', 'Создатель']);
        }

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
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        // свойство item_name (роли) возможно сортировать
        $dataProvider->sort->attributes['item_name'] = [
            'asc' => ['item_name' => SORT_ASC],
            'desc' => ['item_name' => SORT_DESC],
        ];

        $query->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'secret_key', $this->secret_key])
            ->andFilterWhere(['like', 'item_name', $this->item_name]);              // фильтр для ролей


        return $dataProvider;
    }

    /**
     * Возвращает массив возможных ролей.
     * Примечание: используется в backend/user/index представлении.
     *
     * @return mixed
     */
    public static function getRolesList()
    {
        $roles = [];

        foreach (AuthItem::getRoles() as $item_name)
        {
            $roles[$item_name->name] = $item_name->name;
        }

        return $roles;
    }

    /**
     * Returns the array of possible user status values.
     *
     * @return array
     */
    public function getStatusList()
    {
        $statusArray = [
            self::STATUS_ACTIVE     => 'Активирован',
            self::STATUS_NOT_ACTIVE => 'Не активирован',
            self::STATUS_DELETED    => 'Бан'
        ];

        return $statusArray;
    }
}
