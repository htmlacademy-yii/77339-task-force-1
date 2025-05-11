<?php

use yii\db\Migration;

class m230227_170512_create_user_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_categories}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx_category_id', 'user_categories', 'category_id');
        $this->createIndex('idx_user_id', 'user_categories', 'user_id');

        $this->addForeignKey(
            'fk_user_categories_users_id',
            'user_categories',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_user_categories_categories_id',
            'user_categories',
            'category_id',
            'categories',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_user_categories_users_id', 'user_categories');
        $this->dropForeignKey('fk_user_categories_categories_id', 'user_categories');

        $this->dropIndex('idx_category_id', 'user_categories');
        $this->dropIndex('idx_user_id', 'user_categories');

        $this->dropTable('{{%user_categories}}');
    }
}
