<?php

namespace app\models;

use yii\base\Model;
use app\models\Users;
use Yii;

class RegistrationForm extends Model
{
    public $full_name;
    public $email;
    public $city_id;
    public $password;

    public $password_repeat;
    public $is_worker;

    public function attributeLabels()
    {
        return [
            'full_name' => 'Ваше имя',
            'email' => 'Email',
            'city_id' => 'Город',
            'password' => 'Пароль',
            'password_repeat' => 'Повтор пароля',
            'is_worker' => 'я собираюсь откликаться на заказы',
        ];
    }

    public function rules()
    {
        return [
            [['full_name', 'email', 'city_id', 'password', 'password_repeat'], 'required'],
            [['email'], 'email'],
            ['email', 'unique', 'targetClass' => Users::class],
            [['full_name'], 'string', 'min' => 3],
            [['password'], 'string', 'min' => 8],
            ['password', 'compare'],
            ['is_worker', 'boolean'],
            ['is_worker', 'default', 'value' => null],
        ];
    }

    public function register()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = new Users();
        $user->scenario = $user::SCENARIO_REGISTER;
        $user->attributes = $this->attributes;

        $user->generateSafePassword($this->password);
        $user->generateAuthKey();
        $user->avatar_id = random_int(1, 10);

        $user->save();

        if ($user->save()) {
            $role = empty($this->is_worker) ? 'client' : 'worker';

            $auth = Yii::$app->authManager;
            $userRole = $auth->getRole($role);
            $auth->assign($userRole, $user->getId());
        }

        return $user->save();
    }
}