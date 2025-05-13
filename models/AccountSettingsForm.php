<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\web\UploadedFile;

final class AccountSettingsForm extends Model
{
    public $name;
    public $email;
    public $birthday;
    public $phone;
    public $telegram;
    public $avatar;
    public $categories = [];
    public string|null $info = null;
    public $show_contacts;
    public $old_password;
    public $new_password;
    public $repeat_password;

    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            [['show_contacts'], 'boolean'],
            [['name', 'email'], 'required'],
            ['email', 'email'],
            ['birthday', 'date', 'format' => 'php:Y-m-d'],
            ['phone', 'string', 'length' => 11],
            ['phone', 'match', 'pattern' => '/^[0-9]+$/'],
            ['telegram', 'string', 'max' => 64],
            ['info', 'string', 'max' => 255],
            [['categories'], 'each', 'rule' => ['integer']],
            ['avatar', 'file', 'extensions' => 'png, jpg', 'skipOnEmpty' => true],
            ['old_password', 'validatePassword'],
            ['new_password', 'string', 'min' => 6],
            ['repeat_password', 'compare', 'compareAttribute' => 'new_password'],
            [['birthday', 'info'], 'safe'],
        ];
    }

    /**
     * @return bool
     */
    public function beforeValidate() : bool
    {
        if ($this->birthday) {
            $date = \DateTime::createFromFormat('d.m.Y', $this->birthday);
            if ($date) {
                $this->birthday = $date->format('Y-m-d');
            }
        }

        return parent::beforeValidate();
    }

    /**
     * @throws InvalidConfigException
     */
    public function loadFromUser($user) : void
    {
        $this->name = $user->name;
        $this->email = $user->email;
        if ($user->birthday) {
            $this->birthday = Yii::$app->formatter->asDate($user->birthday, 'php:d.m.Y');
        }
        $this->phone = $user->phone;
        $this->telegram = $user->telegram;
        $this->info = $user->info;
        $this->categories = $user->categories;
        $this->show_contacts = (bool)$user->show_contacts;
        $this->avatar = $user->avatar;
        $this->categories = $user->getCategories()->select('id')->column();
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function applyToUser($user) : void
    {
        $user->name = $this->name;
        $user->email = $this->email;
        $user->birthday = $this->birthday ? $this->birthday : $user->birthday;
        $user->phone = $this->phone;
        $user->telegram = $this->telegram;
        $user->info = $this->info;
        $user->updateCategories($this->categories);
        $user->show_contacts = (int)$this->show_contacts;

        if (!empty($this->new_password)) {
            $user->setPassword($this->new_password);
        }

        if ($this->avatar instanceof UploadedFile) {
            $fileName = 'uploads/avatars/' . uniqid() . '.' . $this->avatar->extension;
            $this->avatar->saveAs($fileName);
            $user->avatar = $fileName;
        }
    }

    /**
     * @param string
     * @param arra
     *
     * @return void
     */
    public function validatePassword($attribute, $params) : void
    {
        if (!empty($this->old_password) && !Yii::$app->user->identity->validatePassword($this->old_password)) {
            $this->addError($attribute, 'Неверный текущий пароль');
        }
    }

    /**
     * @return array
     */
    public function attributeLabels() : array
    {
        return [
            'name' => 'Имя',
            'email' => 'Email',
            'birthday' => 'День рождения',
            'phone' => 'Номер телефона',
            'telegram' => 'Telegram',
            'info' => 'Информация о себе',
            'categories' => 'Специализации',
            'show_contacts' => 'Скрыть контакты',
            'avatar' => 'Аватар',
            'old_password' => 'Текущий пароль',
            'new_password' => 'Новый пароль',
            'repeat_password' => 'Повторите пароль',
        ];
    }
}
