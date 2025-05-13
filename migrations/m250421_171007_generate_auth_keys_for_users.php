<?php

use yii\db\Migration;

class m250421_171007_generate_auth_keys_for_users extends Migration
{
    /**
     * {@inheritdoc}
     * @throws \yii\base\Exception
     */
    public function safeUp(): void
    {
        foreach (\app\models\User::find()->all() as $user) {
            $user->auth_key = Yii::$app->security->generateRandomString();
            $user->save(false);
        }
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
