<?php

use yii\db\Migration;

class m230225_141750_add_columns_to_feedbacks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%feedbacks}}', 'worker_id', $this->integer()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%feedbacks}}', 'worker_id');
    }
}
