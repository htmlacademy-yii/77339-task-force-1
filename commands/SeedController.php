<?php

namespace app\commands;

use app\models\Category;
use app\models\Task;
use Yii;
use yii\base\Exception;
use yii\console\Controller;
use Faker\Factory;
use app\models\User;
use app\models\City;

class SeedController extends Controller
{
    /**
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function actionUsers($count = 10): void
    {
        $faker = Factory::create();

        $cities = City::find()->all();

        for ($i = 0; $i < $count; $i++) {
            $user = new User();
            $user->name = $faker->name;
            $user->email = $faker->unique()->email;
            $user->password_hash = Yii::$app->security->generatePasswordHash('password');
            $user->role = $faker->randomElement([User::ROLE_CUSTOMER, User::ROLE_EXECUTOR]);
            $user->city_id = $faker->randomElement($cities)->id; // Случайный город
            $user->avatar = $faker->imageUrl(200, 200, 'people'); // Случайное изображение
            $user->telegram = $faker->userName;
            $user->phone = $faker->phoneNumber;
            $user->show_contacts = $faker->boolean ? 1 : 0;
            $user->birthday = $faker->date('Y-m-d', '2000-01-01');
            $user->info = $faker->paragraph;
            $user->created_at = $faker->dateTimeThisYear->format('Y-m-d H:i:s');

            if ($user->save()) {
                echo "Пользователь {$user->name} создан.\n";
            } else {
                echo "Ошибка при создании пользователя: " . implode(', ', $user->getErrorSummary(true)) . "\n";
            }
        }

        echo "Добавлено $count пользователей.\n";
    }

    /**
     * @throws \yii\db\Exception
     */
    public function actionTasks($count = 10): void
    {
        $faker = Factory::create();

        $categories = Category::find()->all();
        $customers = User::find()->where(['role' => User::ROLE_CUSTOMER])->all();
        $executors = User::find()->where(['role' => User::ROLE_EXECUTOR])->all();
        $cities = City::find()->all();

        for ($i = 0; $i < $count; $i++) {
            $task = new Task();
            $task->title = $faker->sentence(3); // Случайный заголовок
            $task->description = $faker->paragraph; // Случайное описание
            $task->category_id = $faker->randomElement($categories)->id; // Случайная категория
            $task->budget = $faker->randomFloat(2, 100, 10000); // Случайный бюджет
            $task->status = $faker->randomElement(array_keys(Task::optsStatus())); // Случайный статус
            $task->city_id = $faker->randomElement($cities)->id; // Случайный город
            $task->latitude = $faker->latitude; // Случайная широта
            $task->longitude = $faker->longitude; // Случайная долгота
            $task->ended_at = $faker->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s'); // Случайная дата завершения
            $task->customer_id = $faker->randomElement($customers)->id; // Случайный заказчик
            $task->executor_id = $faker->randomElement($executors)->id; // Случайный исполнитель
            $task->created_at = $faker->dateTimeThisYear->format('Y-m-d H:i:s'); // Случайная дата создания

            if ($task->save()) {
                echo "Задание '{$task->title}' создано.\n";
            } else {
                echo "Ошибка при создании задания: " . implode(', ', $task->getErrorSummary(true)) . "\n";
            }
        }

        echo "Добавлено $count заданий.\n";
    }
}
