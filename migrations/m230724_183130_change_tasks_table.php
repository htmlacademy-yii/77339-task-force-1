<?php

use yii\db\Migration;

/**
 * Class m230724_183130_change_tasks_table
 */
class m230724_183130_change_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('tasks', 'price', $this->integer()->null());
        $this->alterColumn('tasks', 'city_id', $this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('tasks', 'price', $this->integer()->notNull());
        $this->alterColumn('tasks', 'city_id', $this->integer()->notNull());

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230724_183130_change_tasks_table cannot be reverted.\n";

        return false;
    }
    */
}
