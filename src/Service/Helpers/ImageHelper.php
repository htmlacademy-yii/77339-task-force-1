<?php

namespace Taskforce\Service\Helpers;

use app\models\Files;
use Taskforce\Exceptions\FileUploadException;
use yii\imagine\Image;
use Yii;
use yii\web\UploadedFile;

class ImageHelper
{
    const THUMBNAIL_WIDTH = 200;
    const THUMBNAIL_HEIGHT = 200;
    const THUMBNAIL_QUALITY = 90;

    const EMPTY_AVATAR_FILE_PATH = '@web/img/avatars/avatar.webp';

    protected UploadedFile $file;

    public string $filePath;
    public int $fileId;

    public function __construct(UploadedFile $file, $createThumbnail = false) {
//        Yii::setAlias('@uploads', '@webroot/uploads/');
        $this->file = $file;
        $this->saveFile();

        if ($createThumbnail) {
            $this->createThumbnail();
        }
    }

    protected function saveFile(): void
    {
        $this->filePath = uniqid('upload') . '.' . $this->file->extension;
        $this->file->saveAs(Yii::getAlias('@webroot/uploads/') . $this->filePath);

        $file = new Files();
        $file->file_path = $this->filePath;
        $result = $file->save();

        if ($result) {
            $this->fileId = $file->id;
        }
    }

    public function createThumbnail(): void
    {
        Image::thumbnail(
            Yii::getAlias('@webroot/uploads/' . $this->filePath),
            self::THUMBNAIL_WIDTH,
            self::THUMBNAIL_HEIGHT
        )->save(Yii::getAlias('@webroot/uploads/' . $this->filePath), [
            'quality' => self::THUMBNAIL_QUALITY
        ]);
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function getFileId(): int
    {
        return $this->fileId;
    }

    public static function getEmptyUserAvatar(): string
    {
        return Yii::getAlias(self::EMPTY_AVATAR_FILE_PATH);
    }
}