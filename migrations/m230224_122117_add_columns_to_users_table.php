<?php

use yii\db\Migration;

class m230224_122117_add_columns_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%users}}', 'auth_key', $this->string(255));
        $this->addColumn('{{%users}}', 'birthdate', $this->datetime());
        $this->addColumn('{{%users}}', 'phone', $this->string(11));
        $this->addColumn('{{%users}}', 'telegram', $this->string(64));
        $this->addColumn('{{%users}}', 'about', $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%users}}', 'auth_key');
        $this->dropColumn('{{%users}}', 'birthdate');
        $this->dropColumn('{{%users}}', 'phone');
        $this->dropColumn('{{%users}}', 'telegram');
        $this->dropColumn('{{%users}}', 'about');
    }
}
