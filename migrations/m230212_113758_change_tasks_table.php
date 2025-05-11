<?php

use yii\db\Migration;

/**
 * Class m230212_113758_change_tasks_table
 */
class m230212_113758_change_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('tasks', 'worker_id', $this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('tasks', 'worker_id', $this->integer()->notNull());

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230212_113758_change_tasks_table cannot be reverted.\n";

        return false;
    }
    */
}
