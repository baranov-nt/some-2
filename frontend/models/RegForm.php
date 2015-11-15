<?php
/**
 * Created by PhpStorm.
 * User: phpNT
 * Date: 02.05.2015
 * Time: 18:17
 */
namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\db\Exception;
use common\rbac\helpers\RbacHelper;
use common\models\User;
use common\models\Profile;

class RegForm extends Model
{
    public $phone;
    public $email;
    public $password;
    public $status;

    public function rules()
    {
        return [
            [['phone', 'email', 'password'],'filter', 'filter' => 'trim'],
            [['phone', 'email', 'password'],'required', 'on' => 'default'],
            [['phone', 'email'],'required', 'on' => 'phoneAndEmailFinish'],
            [['phone'],'required', 'on' => 'phoneFinish'],
            ['password', 'string', 'min' => 6, 'max' => 255],
            ['phone', 'unique',
                'targetClass' => User::className(),
                'message' => 'Этот номер уже занят.'],
            ['phone', function ($attribute, $params) {
                $this->phone = str_replace('_', '', $this->phone);
                if(iconv_strlen($this->phone) != 16):
                    $this->addError($attribute, 'Пример: 7 (XXX) XXX-XXXX');
                endif;
            }],
            ['email', 'email'],
            ['email', 'unique',
                'targetClass' => User::className(),
                'message' => 'Эта почта уже занята.'],
            ['status', 'default', 'value' => User::STATUS_ACTIVE, 'on' => 'default'],
            ['status', 'in', 'range' =>[
                User::STATUS_NOT_ACTIVE,
                User::STATUS_ACTIVE
            ]],
            ['status', 'default', 'value' => User::STATUS_NOT_ACTIVE, 'on' => 'emailActivation'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'phone' => 'Телефон',
            'email' => 'Эл. почта',
            'password' => 'Пароль'
        ];
    }

    public function finishReg($id)
    {
        /* @var $modelUser \common\models\User */
        
        $modelUser = User::findOne($id);

        if($this->scenario === 'phoneFinish'):
            $modelUser->phone = $this->phone;
            $modelUser->status = User::STATUS_ACTIVE;
            $modelUser->save();
            return RbacHelper::assignRole($modelUser->getId()) ? $modelUser : null;
        elseif($this->scenario === 'phoneAndEmailFinish'):
            $modelUser->phone = $this->phone;
            $modelUser->email = $this->email;
            $modelUser->setPassword($this->password);
            $modelUser->generateAuthKey();
            $modelUser->generateSecretKey();
            $modelUser->save();
            return RbacHelper::assignRole($modelUser->getId()) ? $modelUser : null;
        endif;
        return false;
    }

    public function reg()
    {
        $user = new User();
        $user->phone = $this->phone;
        $user->email = $this->email;
        $user->status = $this->status;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        if($this->scenario === 'emailActivation')
            $user->generateSecretKey();

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if($user->save()):
                $modelProfile = new Profile();
                $modelProfile->user_id = $user->id;
                if($modelProfile->save()):
                    $transaction->commit();
                    return RbacHelper::assignRole($user->getId()) ? $user : null;
                endif;
            else:
                return false;
            endif;
        } catch (Exception $e) {
            $transaction->rollBack();
        }
    }

    public function sendActivationEmail($user)
    {
        return Yii::$app->mailer->compose('activationEmail', ['user' => $user])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name.' (отправлено роботом).'])
            ->setTo($this->email)
            ->setSubject('Активация для '.Yii::$app->name)
            ->send();
    }
}