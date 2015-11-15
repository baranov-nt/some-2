<?php
/**
 * Created by PhpStorm.
 * User: phpNT
 * Date: 05.10.2015
 * Time: 17:55
 */

namespace common\widgets\PjaxField;

use yii\base\Widget;
use common\models\Profile;
use common\models\User;

class PjaxFieldWidget extends Widget
{
    public $route = null;           // маршрут, который обрабатывает виджет
    public $model = null;           // модель (Profile)
    public $id = null;              // ID модели
    public $attribute = null;       // атрибут поля

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render(
            'field',
            [
                'route' => $this->route,
                'model' => $this->model,
                'id' => $this->id,
                'attribute' => $this->attribute,
            ]
        );
    }
}