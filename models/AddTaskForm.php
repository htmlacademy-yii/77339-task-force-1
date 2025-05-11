<?php

namespace app\models;

use Taskforce\Service\Task\TaskStatuses;
use yii\base\Model;
use app\models\Files;
use app\models\Tasks;
use app\models\TaskFiles;

class AddTaskForm extends Model
{
    public $title;
    public $description;
    public $category_id;
    public $price;
    public $location;
    public $latitude;
    public $longitude;
    public $expired_at;
    public $files;

    protected $filesId;
    protected $taskId;

    public function attributeLabels()
    {
        return [
            'title' => 'Опишите суть работы',
            'description' => 'Подробности задания',
            'category_id' => 'Категория',
            'location' => 'Локация',
            'price' => 'Бюджет',
            'expired_at' => 'Срок исполнения',
            'files' => 'Файлы',
        ];
    }

    public function rules()
    {
        return [
            [['title', 'description', 'category_id'], 'required'],
            [['title'], 'string', 'min' => 10],
            [['description'], 'string', 'min' => 30],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['category_id' => 'id']],
            [['price'], 'integer', 'min' => 1],
            ['expired_at', 'date', 'format' => 'php:Y-m-d'],
            [['files'], 'file', 'maxFiles' => 4],
            [['location', 'latitude', 'longitude'], 'safe'],
        ];
    }

    public function upload()
    {
        if (empty($this->files)) {
            return;
        }

        if ($this->validate()) {
            foreach ($this->files as $uploadedFile) {
                $fileName = uniqid('upload'). '.' . $uploadedFile->extension;
                $uploadedFile->saveAs('@webroot/uploads/' . $fileName);

                $file = new Files();
                $file->file_path = $fileName;
                $file->save();

                if ($file->save()) {
                    $this->filesId[] = $file->id;
                }
            }
        }
    }

    public function addTask()
    {
        if (!$this->validate()) {
            return $this->getErrors();
        }

        $task = new Tasks();
        $task->attributes = $this->attributes;

        if (!empty($this->location)) {
            $location = explode(',', $this->location);
            $cityName = $location[0];

            $cityId = Cities::findCityIdByName($cityName);

            if (empty($cityId)) {
                $task->location = $this->location;
            } else {
                $task->city_id = $cityId;
                array_shift($location);
                $task->location = implode(',', $location);
            }
        }

        $task->client_id = \Yii::$app->user->identity->id;

        $task->published_at = (new \DateTime('now', new \DateTimeZone('Europe/Moscow')))->format('Y-m-d H:i:s');
        $task->expired_at = (new \DateTime($task->expired_at, new \DateTimeZone('Europe/Moscow')))->format('Y-m-d H:i:s');
        $task->current_status = TaskStatuses::STATUS_NEW;

        $taskSaved = $task->save(false);

        if ($taskSaved) {
            $this->taskId = $task->id;
            $this->upload();

            if (!empty($this->filesId)) {
                foreach ($this->filesId as $fileId) {
                    $taskFile = new TaskFiles();
                    $taskFile->task_id = $this->taskId;
                    $taskFile->file_id = $fileId;

                    $taskFile->save();
                }
            }
        }

        return $this->taskId;
    }
}