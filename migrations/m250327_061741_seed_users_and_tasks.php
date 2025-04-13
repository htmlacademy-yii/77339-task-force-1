<?php

use yii\db\Migration;

class m250327_061741_seed_users_and_tasks extends Migration
{
    /**
     * {@inheritdoc}
     * @throws \yii\base\Exception
     */
    public function safeUp(): void
    {
        $faker = \Faker\Factory::create('ru_RU');

        $users = [];
        for ($i = 0; $i < 20; $i++) {
            $users[] = [
                'name' => $faker->userName,
                'email' => $faker->email,
                'password_hash' => \Yii::$app->security->generatePasswordHash('123456'),
                'role' => $faker->randomElement(['customer', 'executor']),
                'city_id' => rand(1, 1000),
                'telegram' => $faker->userName,
                'phone' => preg_replace('/[^0-9]/', '', $faker->phoneNumber),
                'show_contacts' => $faker->boolean,
                'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'),
                'info' => $faker->paragraph(3),
                'created_at' => $faker->dateTimeBetween('-6 month', 'now')->format('Y-m-d H:i:s'),
            ];
        }

        $this->batchInsert(
            'users',
            ['name', 'email', 'password_hash', 'role', 'city_id', 'telegram', 'phone', 'show_contacts', 'birthday', 'info', 'created_at'],
            $users
        );

        $tasks = [];
        $usersIds = (new \yii\db\Query())
            ->select('id')
            ->from('users')
            ->column();

        $cities = (new \yii\db\Query())
            ->select(['id', 'latitude', 'longitude'])
            ->from('cities')
            ->all();

        for ($i = 0; $i < 50; $i++) {
            $city = $faker->optional(0.7)->randomElement($cities);
            $tasks[] = [
                'title' => $faker->sentence(3),
                'description' => $faker->paragraph(3),
                'category_id' => rand(1, 8),
                'budget' => $faker->numberBetween($min = 1000, $max = 50000),
                'status' => $faker->randomElement(['new', 'in_progress', 'completed', 'failed', 'canceled']),
                'city_id' => $city ? $city['id'] : null,
                'latitude' => $city ? $city['latitude'] : null,
                'longitude' => $city ? $city['longitude'] : null,
                'ended_at' => $faker->dateTimeBetween('now', '+3 month')->format('Y-m-d H:i:s'),
                'customer_id' => $faker->randomElement($usersIds),
                'executor_id' => $faker->optional(0.7)->randomElement($usersIds),
                'created_at' => $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d H:i:s'),
            ];
        }
        $this->batchInsert('tasks',
            [
                'title',
                'description',
                'category_id',
                'budget',
                'status',
                'city_id',
                'latitude',
                'longitude',
                'ended_at',
                'customer_id',
                'executor_id',
                'created_at'
            ],
            $tasks
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->truncateTable('tasks');
        $this->truncateTable('users');
    }
}
