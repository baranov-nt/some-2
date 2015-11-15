<?php
namespace frontend\controllers;

use Yii;
use frontend\models\RegForm;
use common\models\LoginForm;
use common\models\User;
use common\models\Profile;
use frontend\models\SendEmailForm;
use frontend\models\ResetPasswordForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\AccountActivation;
use common\models\Carousel;
use yii\web\ErrorAction;

class MainController extends BehaviorsController
{
    public $layout = 'basic';
    public $defaultAction = 'index';

    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::className(),
            ],
        ];
    }

    public function actionIndex()
    {
        $carousel = Carousel::find()->all();



        return $this->render(
            'index',
            [
                'carousel' => $carousel
            ]);
    }

    public function actionFinishReg($id)
    {
        /* @var $modelUser \common\models\User */
        /* @var $model \frontend\models\RegForm */

        $modelUser = User::findOne($id);

        if($modelUser->email == ''):
            $model = new RegForm(['scenario' => 'phoneAndEmailFinish']);
        elseif($modelUser->email != ''):
            $model = new RegForm(['scenario' => 'phoneFinish']);
        endif;

        if ($model->load(Yii::$app->request->post()) && $model->validate()):
            if ($modelUser = $model->finishReg($id)):
                if ($modelUser->status === User::STATUS_ACTIVE):
                    if (Yii::$app->getUser()->login($modelUser)):
                        return $this->goHome();
                    endif;
                else:
                    if($model->sendActivationEmail($modelUser)):
                        Yii::$app->session->setFlash('success', 'Письмо с активацией отправлено на емайл <strong>'.Html::encode($modelUser->email).'</strong> (проверьте папку спам).');
                        return $this->redirect(Url::to(['/main/login']));
                    else:
                        Yii::$app->session->setFlash('error', 'Ошибка. Письмо не отправлено.');
                        Yii::error('Ошибка отправки письма.');
                    endif;
                    return $this->refresh();
                endif;
            else:
                Yii::$app->session->setFlash('error', 'Возникла ошибка при регистрации.');
                Yii::error('Ошибка при регистрации');
                return $this->refresh();
            endif;
        endif;

        return $this->render(
            'reg',
            [
                'modelUser' => $modelUser,
                'model' => $model
            ]
        );
    }

    public function actionReg()
    {
        $emailActivation = Yii::$app->params['emailActivation'];
        $model = $emailActivation ? new RegForm(['scenario' => 'emailActivation']) : new RegForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()):
            if ($user = $model->reg()):
                if ($user->status === User::STATUS_ACTIVE):
                    if (Yii::$app->getUser()->login($user)):
                        return $this->goHome();
                    endif;
                else:
                    if($model->sendActivationEmail($user)):
                        Yii::$app->session->setFlash('success', 'Письмо с активацией отправлено на емайл <strong>'.Html::encode($user->email).'</strong> (проверьте папку спам).');
                    else:
                        Yii::$app->session->setFlash('error', 'Ошибка. Письмо не отправлено.');
                        Yii::error('Ошибка отправки письма.');
                    endif;
                    return $this->refresh();
                endif;
            else:
                Yii::$app->session->setFlash('error', 'Возникла ошибка при регистрации.');
                Yii::error('Ошибка при регистрации');
                return $this->refresh();
            endif;
        endif;

        return $this->render(
            'reg',
            [
                'model' => $model
            ]
        );
    }

    public function actionActivateAccount($key)
    {
        /* @var $modelUser \common\models\User */

        if (!Yii::$app->user->isGuest):
            return $this->goHome();
        endif;

        try {
            $user = new AccountActivation($key);
        }
        catch(InvalidParamException $e) {
            Yii::$app->session->setFlash('error', 'Не верный ключ. Повторите регистрацию.');
            throw new BadRequestHttpException($e->getMessage());
        }

        if($user = $user->activateAccount()):
            Yii::$app->session->setFlash('success', 'Активация прошла успешно. Теперь вы можете заказывать продукцию компании Бояр на дом!!!');
            Yii::$app->getUser()->login($user);
            return $this->redirect(['/main/profile']);
        else:
            Yii::$app->session->setFlash('error', 'Ошибка активации.');
            Yii::error('Ошибка при активации.');
        endif;

        return $this->redirect(Url::to(['/main/index']));
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['/main/index']);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest):
            return $this->goHome();
        endif;

        $loginWithEmail = Yii::$app->params['loginWithEmail'];

        $model = $loginWithEmail ? new LoginForm(['scenario' => 'loginWithEmail']) : new LoginForm(['scenario' => 'default']);

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

    public function actionSearch()
    {
        $search = Yii::$app->session->get('search');
        Yii::$app->session->remove('search');

        if ($search):
            Yii::$app->session->setFlash(
                'success',
                'Результат поиска'
            );
        else:
            Yii::$app->session->setFlash(
                'error',
                'Не заполнена форма поиска'
            );
        endif;

        return $this->render(
            'search',
            [
                'search' => $search
            ]
        );
    }

    public function actionSendEmail()
    {
        $model = new SendEmailForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if($model->sendEmail()):
                    Yii::$app->getSession()->setFlash('warning', 'Проверьте емайл.');
                    return $this->goHome();
                else:
                    Yii::$app->getSession()->setFlash('error', 'Нельзя сбросить пароль.');
                endif;
            }
        }

        return $this->render('sendEmail', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($key)
    {
        try {
            $model = new ResetPasswordForm($key);
        }
        catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->resetPassword()) {
                Yii::$app->getSession()->setFlash('warning', 'Пароль изменен.');
                return $this->redirect(['/main/login']);
            }
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionUser()
    {
        $modelProfile = ($modelProfile = Profile::findOne(Yii::$app->user->id)) ? $modelProfile : new Profile();

        $modelUser = ($modelUser = User::findOne(Yii::$app->user->id)) ? $modelUser : new User();

        if($modelUser->load(Yii::$app->request->post()) && $modelUser->validate()):
            if($modelUser->updateUser()):
                //Yii::$app->session->setFlash('success', 'Профиль изменен');
            else:
                //Yii::$app->session->setFlash('error', 'Профиль не изменен');
                Yii::error('Ошибка записи. Профиль не изменен');
                return $this->refresh();
            endif;
        endif;

        return $this->render(
            'profile',
            [
                'modelProfile' => $modelProfile,
                'modelUser' => $modelUser
            ]
        );
    }

    public function actionProfile()
    {
        /* @var $modelProfile \common\models\Profile */

        $modelProfile = $modelProfile = Profile::findOne(Yii::$app->user->id);

        $imagesObject = $modelProfile->imagesOfObjects;

        $modelUser = ($modelUser = User::findOne(Yii::$app->user->id)) ? $modelUser : new User();

        if($modelProfile->load(Yii::$app->request->post()) && $modelProfile->validate()):
            if($modelProfile->updateProfile()):
                if(!$modelProfile->user->errors):
                    Yii::$app->session->setFlash('success', 'Профиль изменен');
                endif;
            else:
                Yii::$app->session->setFlash('error', 'Профиль не изменен');
                Yii::error('Ошибка записи. Профиль не изменен');
                return $this->refresh();
            endif;
        endif;

        return $this->render(
            'profile',
            [
                'modelProfile' => $modelProfile,
                'modelUser' => $modelUser,
                'imagesObject' => $imagesObject
            ]
        );
    }

    public function actionViewProfile($id)
    {
        /* @var $modelProfile \common\models\Profile */

        $modelProfile = ($model = Profile::findOne($id)) ? $model : new Profile();
        $this->titleMeta = $modelProfile->first_name.' '.$modelProfile->second_name;
        $this->descriptionMeta = 'Карточка пользователя';
        foreach($modelProfile->imagesOfObjects as $one):
            $this->imageMeta = Yii::$app->urlManager->createAbsoluteUrl('').'images/'.$one->image->path_small_image;
        endforeach;

        return $this->render(
            'view-profile', [
                'modelProfile' => $modelProfile
            ]
        );
    }

    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('error', ['exception' => $exception]);
        }
        return false;
    }
}
