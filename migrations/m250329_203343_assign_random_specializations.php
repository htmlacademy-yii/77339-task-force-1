<?php

use yii\db\Migration;

class m250329_203343_assign_random_specializations extends Migration
{
    /**
     * {@inheritdoc}
     * @throws \yii\db\Exception
     */
    public function safeUp()
    {
        $executors = $this->getDb()
        ->createCommand("SELECT id FROM users WHERE role = 'executor'")
        ->queryColumn();

        $categories = $this->getDb()
        ->createCommand("SELECT id FROM categories")
        ->queryColumn();

        if (empty($executors)) {
            echo "Нет исполнителей для назначения специализаций\n";
            return true;
        }

        if (empty($categories)) {
            echo "Нет категорий для назначения\n";
            return true;
        }

        foreach ($executors as $executorId) {
            $this->delete('user_specializations', ['user_id' => $executorId]);

            $count = rand(1, min(4, count($categories)));

            shuffle($categories);

            $selectedCategories = array_slice($categories, 0, $count);

            foreach ($selectedCategories as $categoryId) {
                $this->insert('user_specializations', ['user_id' => $executorId, 'category_id' => $categoryId]);
            }
        }
        echo "Назначено специализаций для " . count($executors) . " исполнителей\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->truncateTable('user_specializations');
    }
}
