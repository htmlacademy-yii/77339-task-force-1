<?php

use yii\db\Migration;

class m230110_152008_create_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%categories}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'icon' => $this->string(255)->notNull(),
        ]);

        $this->createIndex('idx_name', 'categories', 'name');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_name', 'categories');

        $this->dropTable('{{%categories}}');
    }
}
