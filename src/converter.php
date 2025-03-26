<?php
use App\Exceptions\FileFormatException;
use App\Exceptions\SourceFileException;
use SplFileObject;

class CsvToSqlConverter
{
    private string $fileName;
    private string $tableName;
    private array $columns = [];

    public function __construct(string $fileName, string $tableName)
    {
        $this->fileName = $fileName;
        $this->tableName = $tableName;
        $this->readColumns();
    }

    private function readColumns(): void
    {
        $file = new SplFileObject($this->fileName);
        $file->setFlags(SplFileObject::READ_CSV);

        $this->columns = $file->fgetcsv(',', '"', '\\');

        if (empty($this->columns)) {
            throw new SourceFileException("Файл не содержит заголовков");
        }

        $this->columns = array_map(function ($column) {
            return preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $column); // Удаляем ZWNBSP и другие невидимые символы
        }, $this->columns);
    }

    public function convert(): string
    {
        $file = new SplFileObject($this->fileName);
        $file->setFlags(SplFileObject::READ_CSV);

        $sql = '';

        $file->seek(1);

        while (!$file->eof()) {
            $data = $file->fgetcsv(',', '"', '\\');

            if ($data && count($data) === count($this->columns)) {
                $values = array_map(function ($value) {
                    return "'" . addslashes($value) . "'";
                }, $data);

                $sql .= sprintf(
                    "INSERT INTO %s (%s) VALUES (%s);\n",
                    $this->tableName,
                    implode(',', $this->columns),
                    implode(',', $values)
                );
            }
        }

        return $sql;
    }

    public function saveToFile(string $outputFile): void
    {
        $sql = $this->convert();

        $directory = dirname($outputFile);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        file_put_contents($outputFile, $sql);
    }
}
