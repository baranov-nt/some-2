<?php

namespace common\models;

use Yii;
use common\widgets\ImageLoad\ImageBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "profile".
 *
 * @property integer $user_id
 * @property integer $images_num
 * @property string $images_label
 * @property string $first_name
 * @property string $second_name
 * @property string $middle_name
 * @property integer $birthday
 * @property integer $gender
 *
 * @property ImagesOfObject[] $imagesOfObjects
 * @property User $user
 */

class Profile extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['images_num', 'birthday', 'gender'], 'integer'],
            [['images_label', 'first_name', 'second_name', 'middle_name'], 'string', 'max' => 32]
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'images_num' => 'Images Num',
            'images_label' => 'Images Label',
            'avatar' => 'Аватар',
            'first_name' => 'Имя',
            'second_name' => 'Фамилия',
            'middle_name' => 'Отчество',
            'birthday' => 'Дата рождения',
            'gender' => 'Пол',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Один пользователь может иметь много аватарок. Промежуточная таблица images_of_object
     *
     * @return \yii\db\ActiveQuery
     */
    public function getImagesOfObjects()
    {
        return $this->hasMany(ImagesOfObject::className(),
            [
                'object_id' => 'user_id',
                'label' => 'images_label'
            ]);
    }

    public function updateProfile()
    {
        $profile = ($profile = Profile::findOne(Yii::$app->user->id)) ? $profile : new Profile();
        $profile->user_id = Yii::$app->user->id;
        $profile->first_name = $this->first_name;
        $profile->second_name = $this->second_name;
        $profile->middle_name = $this->middle_name;
        if($profile->save()):
            return $profile;
        endif;
        return false;
    }
}
