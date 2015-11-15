<?php
/**
 * Created by PhpStorm.
 * User: phpNT
 * Date: 30.06.2015
 * Time: 5:48
 */

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\widgets\ImageLoad\behaviors\ClearTempBehaviors;

class BehaviorsController extends Controller {

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                /*'denyCallback' => function ($rule, $action) {
                    throw new \Exception('Нет доступа.');
                },*/
                'rules' => [
                    [
                        'allow' => true,
                        'controllers' => ['main'],
                        'actions' => ['reg', 'login', 'activate-account'],
                        'verbs' => ['GET', 'POST'],
                        'roles' => ['?']
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['main', 'images'],
                        'actions' => ['profile', 'image-autoload', 'delete-avatar'],
                        'verbs' => ['GET', 'POST'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['main'],
                        'actions' => ['logout'],
                        'verbs' => ['POST'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['main'],
                        'actions' => ['index', 'search', 'send-email', 'reset-password']
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['widget-test'],
                        'actions' => ['index'],
                        /*'ips' => ['127.1.*'],
                        'matchCallback' => function ($rule, $action) {
                            return date('d-m') === '30-06';
                        }*/
                    ],
                    [
                        'controllers' => ['site'],
                        'actions' => ['index', 'login', 'logout', 'error'],
                        'allow' => true,
                    ],
                    [
                        'controllers' => ['carousel', 'product', 'user'],
                        'actions' => ['index', 'view', 'create', 'update' , 'delete', 'save-image', 'autoload-image', 'delete-image'],
                        'allow' => true,
                        'roles' => ['Редактор']
                    ],
                    [
                        'controllers' => ['user'],
                        'actions' => ['index', 'view', 'create', 'update' , 'delete', 'save-image', 'autoload-image', 'delete-image'],
                        'allow' => true,
                        'roles' => ['Администратор']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'clearTempBehaviors' => [
                'class' => ClearTempBehaviors::className(),
                'tempModel' => Yii::$app->session->get('tempModel'),
                'tempId' => Yii::$app->session->get('tempId'),
            ]
        ];
    }
}