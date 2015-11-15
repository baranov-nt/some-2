<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 30.10.2015
 * Time: 9:01
 *
 * Используется только для авторизации через социальные сети !!!!
 */



namespace frontend\controllers;

use Yii;
use common\models\Auth;
use common\models\User;
use yii\helpers\Url;
use yii\web\Controller;
use common\models\Profile;
use yii\authclient\AuthAction;

class SiteController extends Controller
{
    public function actions()
    {
        return [
            'auth' => [
                'class' => AuthAction::className(),
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function onAuthSuccess($client)
    {
        /* @var $client \yii\authclient\OAuth2*/
        /* @var $user \common\models\User */

        $attributes = $client->getUserAttributes();

        /* @var $auth Auth */
        $auth = Auth::find()->where([
            'source' => $client->getId(),
            'source_id' => $attributes['id'],
        ])->one();

        if (Yii::$app->user->isGuest) {
            if ($auth) {                    // вход
                $user = $auth->user;
                if($user->status == User::STATUS_NOT_ACTIVE && $user->email == ''):
                    Yii::$app->getSession()->setFlash('success', [
                        Yii::t('app', "Для завершения регистрации введите телефон и подтвердите адрес электронной почты."),
                    ]);
                    return $this->redirectUser($url = Url::to(['/main/finish-reg', 'id' => $user->id]));
                elseif($user->status == User::STATUS_NOT_ACTIVE && $user->email != ''):
                    Yii::$app->getSession()->setFlash('success', [
                        Yii::t('app', "Для завершения регистрации введите номер телефона."),
                    ]);
                    return $this->redirectUser($url = Url::to(['/main/finish-reg', 'id' => $user->id]));
                elseif($user->status == User::STATUS_DELETED):
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', "Данный пользователь заблокирован."),
                    ]);
                    return $this->redirectUser($url = Url::to(['/main/index']));
                endif;
                Yii::$app->user->login($user);
            } else {                        // регистрация
                if (isset($attributes['email']) && $user = User::findOne(['email' => $attributes['email']])) {
                    // Если пользователь регитрировался ранее через форму регистации.
                    if($user->status == User::STATUS_ACTIVE):
                        Yii::$app->getSession()->setFlash('error',
                            Yii::t('app', "Авторизация с использованием электронного адреса <strong>".$user->email."</strong> уже успешно прошла через форму регистрации.
                            Воспользуйте ссылкой <strong>".'"Забыли пароль?"'."</strong> для востанновления пароля."));
                        return $this->redirectUser($url = Url::to(['/main/login']));
                    else:
                        Yii::$app->getSession()->setFlash('error', [
                            Yii::t('app', "Авторизация с использованием электронного адреса <strong>".$user->email."</strong> уже происходила через аккунт <strong>".$user->auths->source."</strong>.
                            Выполните вход используя аккаунт <strong>".$user->auths->source."</strong> или воспользуйте ссылкой <strong>".'"Забыли пароль?"'."</strong> для востанновления пароля для
                            пользователя с емайл <strong>".$user->email."</strong>.", ['client' => $title = $client->getTitle()]),
                        ]);
                        return $this->redirectUser($url = Url::to(['/main/login']));
                    endif;
                } else {
                    // Полученные данные заносим в переменные
                    /* @var $email string */
                    /* @var $first_name string */
                    /* @var $second_name string */

                    if(Yii::$app->request->get('authclient') == 'google'):
                        $first_name = $attributes['name']['givenName'];
                        $second_name = $attributes['name']['familyName'];
                        $email = $attributes['emails'][0]['value'];
                    elseif(Yii::$app->request->get('authclient') == 'yandex'):
                        $first_name = $attributes['first_name'];
                        $second_name = $attributes['last_name'];
                        $email = $attributes['default_email'];
                    elseif(Yii::$app->request->get('authclient') == 'facebook'):
                        $names = explode(' ', $attributes['name']);
                        $first_name = $names[0];
                        $second_name = $names[1];
                        $email = $attributes['email'];
                    elseif(Yii::$app->request->get('authclient') == 'vkontakte'):
                        $first_name = $attributes['first_name'];
                        $second_name = $attributes['last_name'];
                        $email = false;
                    elseif(Yii::$app->request->get('authclient') == 'twitter'):
                        $names = $attributes['name'];
                        $names = explode(' ', $names);
                        $first_name = $names[0];
                        $second_name = $names[1];
                        $email = false;
                    elseif(Yii::$app->request->get('authclient') == 'linkedin'):
                        $first_name = $attributes['first_name'];
                        $second_name = $attributes['last_name'];
                        $email = $attributes['email'];
                    endif;

                    $password = Yii::$app->security->generateRandomString(6);

                    $user = new User([
                        'email' => $email,
                        'password' => $password,
                        'status' => User::STATUS_NOT_ACTIVE,
                    ]);
                    $user->generateAuthKey();
                    $user->generateSecretKey();
                    $transaction = $user->getDb()->beginTransaction();

                    if ($user->save()) {
                        $auth = new Auth([
                            'user_id' => $user->id,
                            'source' => $client->getId(),
                            'source_id' => (string)$attributes['id'],
                        ]);
                        if ($auth->save()) {
                            /* @var $modelProfile /common/models/Profile */
                            $modelProfile = new Profile();
                            $modelProfile->user_id = $user->id;
                            $modelProfile->first_name = $first_name;
                            $modelProfile->second_name = $second_name;
                            if($modelProfile->save()):
                                $transaction->commit();
                                // если нет емайл, делаем перенаправление на main/finish-reg
                                if($email == false):
                                    Yii::$app->getSession()->setFlash('success', [
                                        Yii::t('app', "Для завершения регистрации введите телефон и подтвердите адрес электронной почты."),
                                    ]);
                                    return $this->redirectUser($url = Url::to(['/main/finish-reg', 'id' => $user->id]));
                                endif;
                                Yii::$app->getSession()->setFlash('success', [
                                    Yii::t('app', "Для завершения регистрации введите номер телефона."),
                                ]);
                                return $this->redirectUser($url = Url::to(['/main/finish-reg', 'id' => $user->id]));
                                //Yii::$app->user->login($user);
                            endif;
                        } else {
                            //dd($user->errors);
                            print_r($auth->getErrors());
                        }
                    } else {
                        $user = User::findOne(['email' => $user->email]);
                        // Если пользователь регитрировался ранее через форму регистации.
                        if($user->status == User::STATUS_ACTIVE):
                            Yii::$app->getSession()->setFlash('error',
                                Yii::t('app', "Авторизация с использованием электронного адреса <strong>".$user->email."</strong> уже успешно прошла через форму регистрации.
                                Воспользуйте ссылкой <strong>".'"Забыли пароль?"'."</strong> для востанновления пароля."));
                            return $this->redirectUser($url = Url::to(['/main/login']));
                        else:
                            Yii::$app->getSession()->setFlash('error', [
                                Yii::t('app', "Авторизация с использованием электронного адреса <strong>".$user->email."</strong> уже происходила через аккунт <strong>".$user->auths->source."</strong>.
                                Выполните вход используя аккаунт <strong>".$user->auths->source."</strong> или воспользуйте ссылкой <strong>".'"Забыли пароль?"'."</strong> для востанновления пароля для
                                пользователя с емайл <strong>".$user->email."</strong>.", ['client' => $title = $client->getTitle()]),
                            ]);
                            return $this->redirectUser($url = Url::to(['/main/login']));
                        endif;
                    }
                }
            }
        } else { // user already logged in
            if (!$auth) { // add auth provider
                $auth = new Auth([
                    'user_id' => Yii::$app->user->id,
                    'source' => $client->getId(),
                    'source_id' => $attributes['id'],
                ]);
                $auth->save();
            }
        }
        return true;
    }

    public function redirectUser($url) {


        /* Если пользователь с таким эл. адресом существуе, делаем переход обратно на страницу входа */
        $viewFile = '@frontend/views/main' . DIRECTORY_SEPARATOR . 'redirect.php';  // файл перехода
        $viewData = [
            'url' =>$url,
            'enforceRedirect' => true,
        ];
        $response = Yii::$app->getResponse();
        $response->content = Yii::$app->getView()->renderFile($viewFile, $viewData);
        return $response;
    }
}