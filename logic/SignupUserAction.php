<?php

namespace app\logic;

use app\models\SignupForm;
use app\models\User;
use yii\db\Exception;

class SignupUserAction
{
    /**
     * @param SignupForm
     * @return User|null
     * @throws Exception|\yii\base\Exception
     */
    
    public function execute(SignupForm $form): ?User
    {
        $user = new User();
        $user->name = $form->name;
        $user->email = $form->email;
        $user->city_id = $form->city;
        $user->setPassword($form->password);
        $user->role = $form->is_executor ? 'executor' : 'customer';

        return $user->save() ? $user : null;
    }
}
