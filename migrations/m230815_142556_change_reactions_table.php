<?php

use yii\db\Migration;

class m230815_142556_change_reactions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('reactions', 'comment', $this->text()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('reactions', 'comment', $this->string(255));

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230815_142556_change_reactions_table cannot be reverted.\n";

        return false;
    }
    */
}
