<?php

use yii\db\Migration;

class m250326_211901_seed_from_csv extends Migration
{
    /**
     * @throws Exception
     */
    public function safeUp(): void
    {
        $this->seedCategories();
        $this->seedCities();
    }

    public function safeDown(): void
    {
        $this->truncateTable('{{%categories}}');
        $this->truncateTable('{{%cities}}');
    }

    /**
     * @throws Exception
     */
    private function seedCategories(): void
    {
        $csvFile = Yii::getAlias('@app/data/categories.csv');

        if (($handle = fopen($csvFile, "r")) !== FALSE) {
            // Пропускаем заголовок
            fgetcsv($handle, 1000, ",", '"', "\\");

            $batch = [];
            while (($data = fgetcsv($handle, 1000, ",", '"', "\\")) !== FALSE) {
                $batch[] = [
                    'name' => $data[0], // Название категории
                    'icon' => $data[1] ?? null, // Иконка (если есть)
                ];

                // Вставляем пачками по 100 записей
                if (count($batch) >= 100) {
                    $this->batchInsert('{{%categories}}', ['name', 'icon'], $batch);
                    $batch = [];
                }
            }

            // Вставляем оставшиеся записи
            if (!empty($batch)) {
                $this->batchInsert('{{%categories}}', ['name', 'icon'], $batch);
            }

            fclose($handle);
            echo "Данные для categories загружены\n";
        } else {
            throw new Exception("Не удалось открыть файл categories.csv");
        }
    }

    /**
     * @throws Exception
     */
    private function seedCities(): void
    {
        $csvFile = Yii::getAlias('@app/data/cities.csv');

        if (($handle = fopen($csvFile, "r")) !== FALSE) {
            // Пропускаем заголовок
            fgetcsv($handle, 1000, ",", '"', "\\");

            $batch = [];
            while (($data = fgetcsv($handle, 1000, ",", '"', "\\")) !== FALSE) {
                $batch[] = [
                    'name' => $data[0],
                    'latitude' => $data[1] ?? null,
                    'longitude' => $data[2] ?? null,
                ];

                // Вставляем пачками по 100 записей
                if (count($batch) >= 100) {
                    $this->batchInsert('{{%cities}}', ['name', 'latitude', 'longitude'], $batch);
                    $batch = [];
                }
            }

            // Вставляем оставшиеся записи
            if (!empty($batch)) {
                $this->batchInsert('{{%cities}}', ['name', 'latitude', 'longitude'], $batch);
            }

            fclose($handle);
            echo "Данные для cities загружены\n";
        } else {
            throw new Exception("Не удалось открыть файл cities.csv");
        }
    }
}
