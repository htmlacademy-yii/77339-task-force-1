<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task_files}}`.
 */
class m230114_150746_create_task_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task_files}}', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull(),
            'file_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx_task_id', 'task_files', 'task_id');

        $this->addForeignKey(
            'fk_task_files_tasks_id',
            'task_files',
            'task_id',
            'tasks',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_task_files_files_id',
            'task_files',
            'file_id',
            'files',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_task_files_tasks_id', 'task_files');
        $this->dropForeignKey('fk_task_files_files_id', 'task_files');

        $this->dropIndex('idx_task_id', 'task_files');

        $this->dropTable('{{%task_files}}');
    }
}
