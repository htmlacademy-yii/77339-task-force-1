<?php

use yii\db\Migration;

class m250421_164507_add_auth_key_to_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->addColumn('users', 'auth_key', $this->string(32)->notNull()->defaultValue(''));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropColumn('users', 'auth_key');
    }
}
