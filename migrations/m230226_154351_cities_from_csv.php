<?php

use yii\db\Migration;

class m230226_154351_cities_from_csv extends Migration
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
            fgetcsv($handle, 1000, ",", '"', "\\");

            $batch = [];
            while (($data = fgetcsv($handle, 1000, ",", '"', "\\")) !== FALSE) {
                $batch[] = [
                    'name' => $data[0],
                    'icon' => $data[1] ?? null,
                ];

                if (count($batch) >= 100) {
                    $this->batchInsert('{{%categories}}', ['name', 'icon'], $batch);
                    $batch = [];
                }
            }

            if (!empty($batch)) {
                $this->batchInsert('{{%categories}}', ['name', 'icon'], $batch);
            }

            fclose($handle);
            echo "Данные для categories загружены\n";
        } else {
            throw new Exception("Не удалось открыть файл categories.csv");
        }
    }

    private function seedCities(): void
    {
        $csvFile = Yii::getAlias('@app/data/cities.csv');

        if (($handle = fopen($csvFile, "r")) !== FALSE) {
            fgetcsv($handle, 1000, ",", '"', "\\");

            $batch = [];
            while (($data = fgetcsv($handle, 1000, ",", '"', "\\")) !== FALSE) {
                $batch[] = [
                    'name' => $data[0],
                    'latitude' => $data[1] ?? null,
                    'longitude' => $data[2] ?? null,
                ];

                if (count($batch) >= 100) {
                    $this->batchInsert('{{%cities}}', ['name', 'latitude', 'longitude'], $batch);
                    $batch = [];
                }
            }

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