<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%auth}}`.
 */
class m230825_182225_create_auth_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%auth}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'source_id' => $this->integer()->notNull(),
            'source' => $this->string(255)->notNull(),
        ]);

        $this->createIndex('idx_user_id', 'auth', 'user_id');
        $this->createIndex('idx_source_id', 'auth', 'source_id');

        $this->addForeignKey(
            'fk_auth_users_id',
            'auth',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_auth_users_id', 'auth');

        $this->dropIndex('idx_user_id', 'auth');
        $this->dropIndex('idx_source_id', 'auth');

        $this->dropTable('{{%auth}}');
    }
}
