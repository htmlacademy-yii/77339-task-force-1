<?php

use Faker\Factory;
use yii\db\Migration;
use yii\db\Query;

class m250328_103444_seed_responses_and_reviews extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $faker = Factory::create('ru_RU');

        $tasks = (new Query())
            ->select(['id', 'customer_id', 'executor_id'])
            ->from('tasks')
            ->where(['not', ['executor_id' => null]])
            ->all();

        $reviews = [];
        $responses = [];

        foreach ($tasks as $task) {
            if ($faker->boolean(80)) { // 80% задач получают отзыв
                $reviews[] = [
                    'task_id' => $task['id'],
                    'customer_id' => $task['customer_id'],
                    'executor_id' => $task['executor_id'],
                    'rating' => $faker->numberBetween(1, 5),
                    'comment' => $faker->sentence(10),
                    'created_at' => $faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d H:i:s'),
                ];
            }
        }

        $executors = (new Query())
            ->select('id')
            ->from('users')
            ->where(['role' => 'executor'])
            ->column();

        $customers = (new Query())
            ->select('id')
            ->from('users')
            ->where(['role' => 'customer'])
            ->column();

        for ($i = 0; $i < 100; $i++) {
            $responses[] = [
                'task_id' => $faker->randomElement($tasks)['id'],
                'executor_id' => $faker->randomElement($executors),
                'price' => $faker->numberBetween(1000, 50000),
                'comment' => $faker->sentence(8),
                'created_at' => $faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d H:i:s'),
            ];
        }

        if (!empty($reviews)) {
            $this->batchInsert('reviews', ['task_id', 'customer_id', 'executor_id', 'rating', 'comment', 'created_at'], $reviews);
        }

        if (!empty($responses)) {
            $this->batchInsert('responses', ['task_id', 'executor_id', 'price', 'comment', 'created_at'], $responses);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->truncateTable('reviews');
        $this->truncateTable('responses');
    }
}
