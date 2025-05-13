<?php

namespace app\interfaces;

use yii\base\Exception;

/**
 * @param array
 * @param int
 * @return array
 * @throws Exception
 */

interface FilesUploadInterface
{
    public function upload(array $files, int $taskId): array;
}
