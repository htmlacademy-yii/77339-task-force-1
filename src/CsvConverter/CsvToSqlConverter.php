<?php

namespace App\CsvConverter;

use App\Exceptions\FileFormatException;
use App\Exceptions\SourceFileException;
use SplFileObject;

class CsvToSqlConverter
{
    private string $fileName;
    private string $tableName;
    private array $columns = [];

    /**
     * @param string $fileName
     * @param string $tableName
     */
    public function __construct(string $fileName, string $tableName)
    {
        $this->fileName = $fileName;
        $this->tableName = $tableName;
    }

    /**
     * @throws SourceFileException
     */
    private function readColumnHeadings(): void
    {
        $file = new SplFileObject($this->fileName);
        $file->setFlags(SplFileObject::READ_CSV);

        $this->columns = $file->fgetcsv(',', '"', '\\');

        if (!$this->columns || empty(array_filter($this->columns))) {
            throw new SourceFileException("Файл не содержит заголовков");
        }

        $this->columns = array_map(function ($column) {
            return preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $column);
        }, $this->columns);
    }

    /**
     * @throws FileFormatException
     */
    public function readTableAndConvert(): string
    {
        $file = new SplFileObject($this->fileName);
        $file->setFlags(SplFileObject::READ_CSV);

        $sql = "-- Дамп для таблицы {$this->tableName}\n\n";

        $file->seek(1);

        while (!$file->eof()) {
            $data = $file->fgetcsv(',', '"', '\\');

            if ($data === false || (count($data) === 1 && $data[0] === null)) {
                continue;
            }


            if (count($data) !== count($this->columns)) {
                throw new FileFormatException("Ошибка формата файла: количество значений в строке не совпадает с заголовками");
            }

            $values = array_map(function ($value) {
                return is_numeric($value) ? $value : "'" . addslashes($value) . "'";
            }, $data);

            $sql .= sprintf(
                "INSERT INTO %s (%s) VALUES (%s);\n",
                $this->tableName,
                implode(',', $this->columns),
                implode(',', $values)
            );
        }

        return $sql;
    }

    /**
     * @throws FileFormatException
     */
    private function saveToFile(string $outputFile): void
    {
        $sql = $this->readTableAndConvert();

        $directory = dirname($outputFile);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        file_put_contents($outputFile, $sql);
    }

    /**
     * @throws SourceFileException
     * @throws FileFormatException
     */
    public function run(string $outputFile): void
    {
        $this->readColumnHeadings();
        $this->saveToFile($outputFile);
    }
}
