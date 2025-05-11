<?php

use yii\db\Migration;

class m230112_163751_create_reactions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%reactions}}', [
            'id' => $this->primaryKey(),
            'worker_id' => $this->integer()->notNull(),
            'task_id' => $this->integer()->notNull(),
            'worker_price' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx_task_id', 'reactions', 'task_id');

        $this->addForeignKey(
            'fk_reactions_users_id',
            'reactions',
            'worker_id',
            'users',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_reactions_tasks_id',
            'reactions',
            'task_id',
            'tasks',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_reactions_users_id', 'reactions');
        $this->dropForeignKey('fk_reactions_tasks_id', 'reactions');

        $this->dropIndex('idx_task_id', 'reactions');

        $this->dropTable('{{%reactions}}');
    }
}
