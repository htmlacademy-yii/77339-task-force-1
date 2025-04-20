<?php

namespace app\models;

use yii\base\Model;

class SignupForm extends Model
{
    public $name;
    public $email;
    public $password;
    public $password_repeat;
    public $city;
    public bool $is_executor = false;

    public function rules(): array
    {
        return [
            [['name', 'email', 'password', 'password_repeat', 'city'], 'required',
                'message' => 'Поле должно быть заполнено'],
            ['email', 'email', 'message' => 'Введите корректный Email'],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'Пользователь с таким email уже зарегистрирован'],
            [['password'], 'string', 'min' => 8, 'message' => 'Пароль должен быть не менее 8 символов'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],
            ['is_executor', 'boolean'],
        ];
    }

    public function attributeLabels(): array {
        return [
            'name' => 'Ваше имя',
            'email' => 'Email',
            'city' => 'Город',
            'password' => 'Пароль',
            'password_repeat' => 'Повтор пароля',
            'is_executor' => 'Я собираюсь откликаться на заказы',
        ];
    }
}