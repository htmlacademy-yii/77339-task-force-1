<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%feedbacks}}`.
 */
class m230112_164425_create_feedbacks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%feedbacks}}', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer()->notNull(),
            'task_id' => $this->integer()->notNull(),
            'comment' => $this->string(255)->notNull(),
            'rating' => $this->integer()->notNull(),
            'date_created' => $this->dateTime()->notNull()->defaultValue(new \yii\db\Expression('NOW()')),
        ]);

        $this->createIndex('idx_rating', 'feedbacks', 'rating');
        $this->createIndex('idx_date_created', 'feedbacks', 'date_created');

        $this->addForeignKey(
            'fk_feedbacks_tasks_id',
            'feedbacks',
            'task_id',
            'tasks',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_feedbacks_users_id',
            'feedbacks',
            'client_id',
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
        $this->dropForeignKey('fk_feedbacks_tasks_id', 'feedbacks');
        $this->dropForeignKey('fk_feedbacks_users_id', 'feedbacks');

        $this->dropIndex('idx_rating', 'feedbacks');
        $this->dropIndex('idx_date_created', 'feedbacks');

        $this->dropTable('{{%feedbacks}}');
    }
}
