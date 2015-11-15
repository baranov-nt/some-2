<?php
/**
 * Created by PhpStorm.
 * User: phpNT
 * Date: 24.10.2015
 * Time: 19:09
 */
namespace backend\controllers;

use Yii;
use common\models\LoginForm;

class SiteController extends BehaviorsController
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    public function actionIndex()
    {
        $hello = 'Привет МИР!!!';
        return $this->render(
            'index',
            [
                'hello' => $hello
            ]);
    }
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['/site/index']);
    }
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest):
            return $this->goHome();
        endif;
        $loginWithEmail = Yii::$app->params['loginWithEmail'];
        $model = $loginWithEmail ? new LoginForm(['scenario' => 'loginWithEmail']) : new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()):
            return $this->goBack();
        endif;
        return $this->render(
            'login',
            [
                'model' => $model
            ]
        );
    }
}