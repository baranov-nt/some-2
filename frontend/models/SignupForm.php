<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $phone;
    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone', 'email', 'password'],'filter', 'filter' => 'trim'],
            [['phone', 'email', 'password'],'required'],
            ['phone', 'unique',
                'targetClass' => User::className(),
                'message' => 'Этот номер уже занят.'],
            ['phone', 'compare', 'compareValue' => '7 (999) 999-9999', 'operator' => '<=', 'message' => 'Пример: 7 (XXX) XXX-XXXX'],
            ['phone', 'compare', 'compareValue' => '7 (000) 000-0000', 'operator' => '>=', 'message' => 'Пример: 7 (XXX) XXX-XXXX'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['password', 'string', 'min' => 6, 'max' => 255],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->phone = $this->phone;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
}
