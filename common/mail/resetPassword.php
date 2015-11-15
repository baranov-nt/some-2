<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 05.08.2015
 * Time: 15:38
 *
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

echo 'Привет '.Html::encode($user->email).'. ';
echo Html::a('Для смены пароля перейдите по этой ссылке.',
    Yii::$app->urlManager->createAbsoluteUrl(
        [
            '/main/reset-password',
            'key' => $user->secret_key
        ]
    ));