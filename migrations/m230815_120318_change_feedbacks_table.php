<?php

use yii\db\Migration;

/**
 * Class m230815_120318_change_feedbacks_table
 */
class m230815_120318_change_feedbacks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('feedbacks', 'comment', $this->text()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('feedbacks', 'comment', $this->string(255)->notNull());

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230815_120318_change_feedbacks_table cannot be reverted.\n";

        return false;
    }
    */
}
