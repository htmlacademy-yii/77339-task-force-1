<?php

use yii\db\Migration;

class m230728_171447_add_status_column_to_reactions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%reactions}}', 'status', $this->string(6));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%reactions}}', 'status');
    }
}
