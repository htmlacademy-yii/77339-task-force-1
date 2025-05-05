<?php

namespace app\interfaces;

interface FilesUploadInterface
{
    public function upload(array $files, int $taskId): array;
}
