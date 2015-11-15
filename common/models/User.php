<?php

namespace common\models;

use Yii;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\rbac\models\Role;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $phone
 * @property string $email
 * @property string $password_hash
 * @property integer $status
 * @property string $auth_key
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $secret_key
 *
 * @property Carousel[] $carousels
 * @property ImagesOfObject[] $imagesOfObjects
 * @property Order[] $orders
 * @property Product[] $products
 * @property Profile $profile
 * @property Rating[] $ratings
 * @property Sale[] $sales
 * @property mixed role
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_NOT_ACTIVE = 1;
    const STATUS_ACTIVE = 10;

    public $password;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @var \common\rbac\models\Role
     */
    public $item_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone', 'email', 'password'], 'filter', 'filter' => 'trim'],
            [['status'], 'required'],
            ['email', 'email'],
            ['password', 'required', 'on' => 'create'],
            ['phone', 'unique', 'message' => 'Этот номер занят.'],
            ['email', 'unique', 'message' => 'Эта почта уже зарегистрирована.'],
            ['secret_key', 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Телефон',
            'email' => 'Email',
            'password' => 'Password Hash',
            'status' => 'Статус',
            'auth_key' => 'Auth Key',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
            'item_name' => 'Роль',
        ];
    }

    /* Связи */
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuths()
    {
        return $this->hasOne(Auth::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    /* Поведения */
    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    /* Поиск */

    /** Находит пользователя по имени и возвращает объект найденного пользователя.
     *  Вызываеться из модели LoginForm.
     *
     * @param $phone
     * @return null|static
     */
    public static function findByphone($phone)
    {
        return static::findOne([
            'phone' => $phone
        ]);
    }

    /* Находит пользователя по емайл */
    public static function findByEmail($email)
    {
        return static::findOne([
            'email' => $email
        ]);
    }

    public static function findBySecretKey($key)
    {
        if (!static::isSecretKeyExpire($key))
        {
            return null;
        }
        return static::findOne([
            'secret_key' => $key,
        ]);
    }

    /* Хелперы */
    public function generateSecretKey()
    {
        $this->secret_key = Yii::$app->security->generateRandomString().'_'.time();
    }

    public function removeSecretKey()
    {
        $this->secret_key = null;
    }

    public static function isSecretKeyExpire($key)
    {
        if (empty($key))
        {
            return false;
        }
        $expire = Yii::$app->params['secretKeyExpire'];
        $parts = explode('_', $key);
        $timestamp = (int) end($parts);

        return $timestamp + $expire >= time();
    }

    /**
     * Генерирует хеш из введенного пароля и присваивает (при записи) полученное значение полю password_hash таблицы user для
     * нового пользователя.
     * Вызываеться из модели RegForm.
     * @param $password
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Генерирует случайную строку из 32 шестнадцатеричных символов и присваивает (при записи) полученное значение полю auth_key
     * таблицы user для нового пользователя.
     * Вызываеться из модели RegForm.
     */
    public function generateAuthKey(){
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Сравнивает полученный пароль с паролем в поле password_hash, для текущего пользователя, в таблице user.
     * Вызываеться из модели LoginForm.
     * @param $password
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /* Аутентификация пользователей */
    public static function findIdentity($id)
    {
        return static::findOne([
            'id' => $id,
            'status' => self::STATUS_ACTIVE
        ]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function updateUser()
    {
        $user = User::findOne(Yii::$app->user->id);
        $phone = $this->phone;
        $user->phone = $phone;
        if($user->save()):
            return $user;
        else:
            return false;
        endif;
    }

    /* Хелперы */

    /**
     * Returns the user status in nice format.
     *
     * @param  null|integer $status Status integer value if sent to method.
     * @return string               Nicely formatted status.
     */
    public function getStatusName($status = null)
    {
        $status = (empty($status)) ? $this->status : $status ;

        if ($status === self::STATUS_DELETED)
        {
            return "Бан";
        }
        elseif ($status === self::STATUS_NOT_ACTIVE)
        {
            return "Не активирован";
        }
        else
        {
            return "Активирован";
        }
    }

    /**
     * Returns the array of possible user status values.
     *
     * @return array
     */
    public function getStatusList()
    {
        $statusArray = [
            self::STATUS_ACTIVE     => 'Активирован',
            self::STATUS_NOT_ACTIVE => 'Не активирован',
            self::STATUS_DELETED    => 'Бан'
        ];
        return $statusArray;
    }


    /**
     * Связь с Role моделью.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        // User has_one Role via Role.user_id -> id
        return $this->hasMany(Role::className(), ['user_id' => 'id']);
    }

    /**
     * Возвращает название роли ( item_name )
     *
     * @return string
     */
    public function getRoleName()
    {
        return $this->role->item_name;
    }
}
