<?php

use yii\db\Migration;

class m250329_094130_add_executor_stats_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users', 'executor_rating', $this->decimal(3 ,2)->defaultValue(0));
        $this->addColumn('users', 'executor_reviews_count', $this->integer()->defaultValue(0));
        $this->createIndex('idx_user_executor_rating', 'users', 'executor_rating');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_user_executor_rating', 'users');
        $this->dropColumn('users', 'executor_rating');
        $this->dropColumn('users', 'executor_reviews_count');
    }
}
