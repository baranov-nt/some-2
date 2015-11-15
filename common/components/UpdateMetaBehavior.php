<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 04.11.2015
 * Time: 12:28
 */

namespace common\components;

use Yii;
use yii\base\Behavior;
use yii\web\Controller;

class UpdateMetaBehavior extends Behavior
{
    public $model;
    public $id;

    public function init()
    {
        parent::init();
    }

    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeAction'
        ];
    }

    public function beforeAction()
    {
        Yii::$app->controller->nameMeta = '555555555';
        Yii::$app->controller->descriptionMeta = '222';
        //Yii::$app->controller->imageMeta = '333';
    }
}