<?php

namespace app\models;

use yii\base\Model;

/**
 * @property-read User|null
 */
class LoginForm extends Model
{
    public $email;
    public $password;

    private $_user;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['email', 'password'], 'required'],
            [['email', 'password'], 'safe'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'email' => 'Email',
            'password' => 'Пароль',
        ];
    }

    /**
     * @param string
     * @return void
     */
    public function validatePassword($attribute): void
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверный email или пароль.');
            }
        }
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        if ($this->_user === null) {
            $this->_user = User::findByEmail($this->email);
        }
        return $this->_user;
    }
}
