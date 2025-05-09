<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tasks}}`.
 */
class m230112_140708_create_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tasks}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'description' => $this->text()->notNull(),
            'price' => $this->integer()->notNull(),
            'published_at' => $this->dateTime()->notNull()->defaultValue(new \yii\db\Expression('NOW()')),
            'expired_at' => $this->dateTime(),
            'current_status' => $this->string(255)->notNull(),
            'category_id' => $this->integer()->notNull(),
            'client_id' => $this->integer()->notNull(),
            'worker_id' => $this->integer()->notNull(),
            'city_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx_price', 'tasks', 'price');
        $this->createIndex('idx_published_at', 'tasks', 'published_at');
        $this->createIndex('idx_current_status', 'tasks', 'current_status');
        $this->createIndex('idx_category_id', 'tasks', 'category_id');
        $this->createIndex('idx_worker_id', 'tasks', 'worker_id');
        $this->createIndex('city_id', 'tasks', 'city_id');

        $this->addForeignKey(
            'fk_tasks_cities_id',
            'tasks',
            'city_id',
            'cities',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_tasks_category_id',
            'tasks',
            'category_id',
            'categories',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_tasks_client_id',
            'tasks',
            'client_id',
            'users',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_tasks_worker_id',
            'tasks',
            'worker_id',
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
        $this->dropForeignKey('fk_tasks_cities_id', 'tasks');
        $this->dropForeignKey('fk_tasks_category_id', 'tasks');
        $this->dropForeignKey('fk_tasks_client_id', 'tasks');
        $this->dropForeignKey('fk_tasks_worker_id', 'tasks');

        $this->dropIndex('idx_price', 'tasks');
        $this->dropIndex('idx_published_at', 'tasks');
        $this->dropIndex('idx_current_status', 'tasks');
        $this->dropIndex('idx_category_id', 'tasks');
        $this->dropIndex('idx_worker_id', 'tasks');
        $this->dropIndex('city_id', 'tasks');

        $this->dropTable('{{%tasks}}');
    }
}
