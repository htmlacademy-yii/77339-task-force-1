<?php

require_once 'vendor/autoload.php';

use App\CsvConverter\CsvToSqlConverter;
use App\Exceptions\FileFormatException;
use App\Exceptions\SourceFileException;

function processCsv(string $csvPath, string $tableName, string $outputPath): void
{
    try {
        $converter = new CsvToSqlConverter($csvPath, $tableName);
        $converter->run($outputPath);
        echo "SQL файл для таблицы '$tableName' успешно создан: $outputPath\n";
    } catch (FileFormatException | SourceFileException $e) {
        error_log("Ошибка обработки CSV '$csvPath': " . $e->getMessage());
        echo "⚠ Ошибка: не удалось обработать '$csvPath'. Подробности в логах.\n";
    }
}

processCsv('data/categories.csv', 'categories', 'requests/categories.sql');
processCsv('data/cities.csv', 'cities', 'requests/cities.sql');

echo "Обработка CSV завершена!\n";
