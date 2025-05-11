<?php

namespace app\models;

use yii\base\Model;
use app\models\Users;
use Yii;

class RegistrationForm extends Model
{
    public $full_name;
    public $email;
    public $password;

    public $password_repeat;
    public $is_worker;

    public $location;
    public $latitude;
    public $longitude;

    public function attributeLabels()
    {
        return [
            'full_name' => 'Ваше имя',
            'email' => 'Email',
            'location' => 'Город',
            'password' => 'Пароль',
            'password_repeat' => 'Повтор пароля',
            'is_worker' => 'я собираюсь откликаться на заказы',
        ];
    }

    public function rules()
    {
        return [
            [['full_name', 'email', 'password', 'password_repeat'], 'required'],
            [['email'], 'email'],
            ['email', 'unique', 'targetClass' => Users::class],
            [['full_name'], 'string', 'min' => 3],
            [['password'], 'string', 'min' => 8],
            ['password', 'compare'],
            ['is_worker', 'boolean'],
            ['is_worker', 'default', 'value' => null],
            [['location', 'latitude', 'longitude'], 'required', 'message' => 'Пожалуйста выберите город из списка автоподстановки'],
            [['location'], 'string', 'min' => 2],
            [['latitude', 'longitude'], 'safe'],
        ];
    }

    /**
     * Finds cityId by [[location]]
     *
     * @return int
     */
    public function getCityId()
    {
        $city = [
            'name' => $this->location,
            'latitude' => $this->latitude,
            'latitude' => $this->longitude
        ];

        return Cities::findCityIdByName($city);
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
        $user->city_id = $this->getCityId();

        $result = $user->save();

        if ($result) {
            $role = empty($this->is_worker) ? 'client' : 'worker';

            $auth = Yii::$app->authManager;
            $userRole = $auth->getRole($role);
            $auth->assign($userRole, $user->getId());
            Yii::$app->user->login($user);
        }

        return $result;
    }
}