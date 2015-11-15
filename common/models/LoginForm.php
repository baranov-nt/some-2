<?php
/**
 * Created by PhpStorm.
 * User: phpNT
 * Date: 02.05.2015
 * Time: 18:16
 */
namespace common\models;

use yii\base\Model;
use Yii;

class LoginForm extends Model
{
    public $phone;
    public $password;
    public $email;
    public $rememberMe = true;
    public $status;

    private $_user = false;

    public function rules()
    {
        return [
            [['phone', 'password'], 'required', 'on' => 'default'],
            ['phone',
                /**
                 * @param $attribute
                 * @param $params
                 */
                function ($attribute, $params) {
                $this->phone = str_replace('_', '', $this->phone);
                if(iconv_strlen($this->phone) != 16):
                    $this->addError($attribute, 'Пример: 7 (XXX) XXX-XXXX');
                endif;
            }],
            [['email', 'password'], 'required', 'on' => 'loginWithEmail'],
            ['email', 'email'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword']
        ];
    }

    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()):
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)):
                $field = ($this->scenario === 'loginWithEmail') ? 'емайл' : 'телефон';
                $this->addError($attribute, 'Неправильный '.$field.' или пароль.');
            endif;
        endif;
    }

    public function getUser()
    {
        if ($this->_user === false):
            if($this->scenario === 'loginWithEmail'):
                $this->_user = User::findByEmail($this->email);
            else:
                $this->_user = User::findByphone($this->phone);
            endif;
        endif;
        return $this->_user;
    }

    public function attributeLabels()
    {
        return [
            'phone' => 'Телефон',
            'email' => 'Емайл',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить меня'
        ];
    }

    public function login()
    {
        /* @var $user User */
        if ($this->validate()):
            $this->status = ($user = $this->getUser()) ? $user->status : User::STATUS_NOT_ACTIVE;
            if ($this->status === User::STATUS_ACTIVE):
                return Yii::$app->user->login($user, $this->rememberMe ? 3600*24*30 : 0);
            else:
                return false;
            endif;
        else:
            return false;
        endif;
    }
}