<?php

use app\models\User;
use yii\db\Migration;

class m250329_094132_add_avatars_to_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $avatarBasePath = 'avatars/';

        $users = User::find()->all();

        $i = 1;
        foreach ($users as $user) {
            $avatarName = $avatarBasePath . $i . '.png';
            $user->avatar = $avatarName;
            $user->save(false);

            echo "Назначен аватар: " . $avatarName . " для пользователя ID: " . $user->id . "\n";

            $i++;

            if ($i > 5) {
                $i = 1;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "Миграция не может быть отменена.\n";
        return false;
    }
}
