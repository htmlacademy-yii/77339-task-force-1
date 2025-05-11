<?php

namespace app\models;

use Yii;
use yii\web\NotFoundHttpException;
use Taskforce\Service\Task\TaskStatuses;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $price
 * @property string|null $published_at
 * @property string|null $expired_at
 * @property string|null $current_status
 * @property int $category_id
 * @property int $client_id
 * @property int $worker_id
 * @property int $city_id
 * @property string $location
 * @property string $latitude
 * @property string $longitude
 *
 * @property Categories $category
 * @property Cities $city
 * @property Users $client
 * @property Feedbacks[] $feedbacks
 * @property Reactions[] $reactions
 * @property TaskFiles[] $taskFiles
 * @property Users $worker
 */
class Tasks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'published_at', 'price', 'category_id', 'client_id', 'worker_id', 'city_id'], 'required'],
            [['price', 'category_id', 'client_id', 'worker_id', 'city_id'], 'integer'],
            [['expired_at', 'location', 'latitude', 'longitude'], 'safe'],
            [['title', 'current_status'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::class, 'targetAttribute' => ['city_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['category_id' => 'id']],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['client_id' => 'id']],
            [['worker_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['worker_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'price' => 'Price',
            'published_at' => 'Published At',
            'expired_at' => 'Expired At',
            'current_status' => 'Current Status',
            'category_id' => 'Category ID',
            'client_id' => 'Client ID',
            'worker_id' => 'Worker ID',
            'city_id' => 'City ID',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Client]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Users::class, ['id' => 'client_id']);
    }

    /**
     * Gets query for [[Feedbacks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFeedbacks()
    {
        return $this->hasMany(Feedbacks::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Reactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReactions()
    {
        return $this->hasMany(Reactions::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[TaskFiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTaskFiles()
    {
        return $this->hasMany(TaskFiles::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Worker]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorker()
    {
        return $this->hasOne(Users::class, ['id' => 'worker_id']);
    }

    public function getPublishedTimePassed()
    {
        return Yii::$app->formatter->format($this->published_at, 'relativeTime');
    }

    public function getExpiredAtFormat()
    {
        return Yii::$app->formatter->asDate($this->expired_at, 'php:d F, H:i');
    }

    public function getStatusDescription()
    {
        $statuses = TaskStatuses::getStatusesMap();
        return $statuses[$this->current_status];
    }

    public static function getTaskByPrimary(int $id)
    {
        $task = Tasks::findOne($id);

        if (!$task) {
            throw new NotFoundHttpException("Задание с ID $id не найдено");
        }

        return $task;
    }
}
