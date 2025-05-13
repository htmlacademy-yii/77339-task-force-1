<?php

use yii\db\Expression;
use yii\db\Migration;

class m250329_131617_init_executor_stats extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $expression = new Expression(
            "
            (SELECT IFNULL(AVG(rating), 0)
            FROM reviews
            WHERE executor_id = users.id)
        "
        );

        $this->update('users', [
            'executor_rating' => $expression,
            'executor_reviews_count' => new Expression(
                "(SELECT COUNT(*)
                            FROM reviews
                            WHERE executor_id = users.id)
        ")
        ], ['role' => 'executor']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "Миграция не может быть отменена.\n";

        return false;
    }
}
