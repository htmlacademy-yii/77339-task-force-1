<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cities}}`.
 */
class m230110_151631_create_cities_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cities}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'latitude' => $this->string(255)->notNull(),
            'longitude' => $this->string(255)->notNull(),
        ]);

        $this->createIndex('idx_name', 'cities', 'name');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_name', 'cities');

        $this->dropTable('{{%cities}}');
    }
}
