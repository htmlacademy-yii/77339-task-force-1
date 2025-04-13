<?php

use yii\db\Migration;

class m250326_202756_init_database_schema extends Migration
{
    public function safeUp(): void
    {
        $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';

        $this->createTable('{{%cities}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->unique(),
            'latitude' => $this->decimal(10, 8),
            'longitude' => $this->decimal(11, 8),
        ], $tableOptions);

        $this->createTable('{{%categories}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->unique(),
            'icon' => $this->string(50),
        ], $tableOptions);

        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'email' => $this->string(255)->notNull()->unique(),
            'password_hash' => $this->string(255)->notNull(),
            'role' => "ENUM('customer', 'executor') NOT NULL",
            'city_id' => $this->integer(),
            'avatar' => $this->string(255),
            'telegram' => $this->string(255),
            'phone' => $this->string(20),
            'show_contacts' => $this->boolean()->defaultValue(true),
            'birthday' => $this->date(),
            'info' => $this->text(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->addForeignKey(
            'fk-users-city_id',
            '{{%users}}',
            'city_id',
            '{{%cities}}',
            'id',
            'SET NULL'
        );

        $this->createTable('{{%user_specializations}}', [
            'user_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'PRIMARY KEY(user_id, category_id)',
        ], $tableOptions);

        $this->addForeignKey(
            'fk-user_specializations-user_id',
            '{{%user_specializations}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-user_specializations-category_id',
            '{{%user_specializations}}',
            'category_id',
            '{{%categories}}',
            'id',
            'CASCADE'
        );

        $this->createTable('{{%tasks}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'description' => $this->text()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'budget' => $this->float(),
            'status' => "ENUM('new', 'in_progress', 'completed', 'failed', 'canceled') NOT NULL DEFAULT 'new'",
            'city_id' => $this->integer(),
            'latitude' => $this->decimal(10, 8),
            'longitude' => $this->decimal(11, 8),
            'ended_at' => $this->date(),
            'customer_id' => $this->integer()->notNull(),
            'executor_id' => $this->integer(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->addForeignKey(
            'fk-tasks-category_id',
            '{{%tasks}}',
            'category_id',
            '{{%categories}}',
            'id',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-tasks-city_id',
            '{{%tasks}}',
            'city_id',
            '{{%cities}}',
            'id',
            'SET NULL'
        );

        $this->addForeignKey(
            'fk-tasks-customer_id',
            '{{%tasks}}',
            'customer_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-tasks-executor_id',
            '{{%tasks}}',
            'executor_id',
            '{{%users}}',
            'id',
            'SET NULL'
        );

        $this->createTable('{{%responses}}', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull(),
            'executor_id' => $this->integer()->notNull(),
            'price' => $this->integer(),
            'comment' => $this->text(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->addForeignKey(
            'fk-responses-task_id',
            '{{%responses}}',
            'task_id',
            '{{%tasks}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-responses-executor_id',
            '{{%responses}}',
            'executor_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        $this->createTable('{{%reviews}}', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull(),
            'customer_id' => $this->integer()->notNull(),
            'executor_id' => $this->integer()->notNull(),
            'rating' => $this->tinyInteger()->notNull(),
            'comment' => $this->text(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->execute('ALTER TABLE {{%reviews}} ADD CONSTRAINT chk_rating_range CHECK (rating >= 1 AND rating <= 5)');

        $this->addForeignKey(
            'fk-reviews-task_id',
            '{{%reviews}}',
            'task_id',
            '{{%tasks}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-reviews-customer_id',
            '{{%reviews}}',
            'customer_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-reviews-executor_id',
            '{{%reviews}}',
            'executor_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        $this->createTable('{{%files}}', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull(),
            'path' => $this->string(255)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->addForeignKey(
            'fk-files-task_id',
            '{{%files}}',
            'task_id',
            '{{%tasks}}',
            'id',
            'CASCADE'
        );
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%files}}');
        $this->dropTable('{{%reviews}}');
        $this->dropTable('{{%responses}}');
        $this->dropTable('{{%tasks}}');
        $this->dropTable('{{%user_specializations}}');
        $this->dropTable('{{%users}}');
        $this->dropTable('{{%categories}}');
        $this->dropTable('{{%cities}}');
    }
}
