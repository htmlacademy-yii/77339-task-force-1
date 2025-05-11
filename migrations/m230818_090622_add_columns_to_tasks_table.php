<?php

use yii\db\Migration;

class m230818_090622_add_columns_to_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tasks}}', 'location', $this->string(255)->null());
        $this->addColumn('{{%tasks}}', 'latitude', $this->string(64)->null());
        $this->addColumn('{{%tasks}}', 'longitude', $this->string(64)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%tasks}}', 'location');
        $this->dropColumn('{{%tasks}}', 'latitude');
        $this->dropColumn('{{%tasks}}', 'longitude');
    }
}
