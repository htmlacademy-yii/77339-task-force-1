<?php

namespace app\services;

use app\interfaces\FilesUploadInterface;
use app\models\File;
use Yii;
use yii\base\Exception;

final class FileUploader implements FilesUploadInterface
{

    /**
     * @throws Exception
     */
    public function upload(array $files, int $taskId): array
    {
        $uploadPath = Yii::getAlias("@webroot/uploads");
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $savedFiles = [];
        foreach ($files as $file) {
            $fileName = Yii::$app->security->generateRandomString() . '.' . $file->extension;
            $filePath = $uploadPath . '/' . $fileName;

            if ($file->saveAs($filePath)) {
                $fileModel = new File();
                $fileModel->task_id = $taskId;
                $fileModel->path = '/uploads/' . $fileName;
                $fileModel->size = $file->size;
                $fileModel->created_at = date('Y-m-d H:i:s');

                if ($fileModel->save()) {
                    $savedFiles[] = $fileModel;
                } else {
                    unlink($filePath);
                    Yii::error("Не удалось сохранить запись файла в БД: " . print_r($fileModel->errors, true));
                }
            } else {
                throw new Exception("Не удалось загрузить файл {$file->name}: ");
            }
        }
        return $savedFiles;
    }
}
