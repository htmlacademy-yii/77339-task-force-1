<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "feedbacks".
 *
 * @property int $id
 * @property int $client_id
 * @property int $worker_id
 * @property int $task_id
 * @property string|null $comment
 * @property int $rating от 1 до 5
 * @property string $date_created
 *
 * @property Users $client
 * @property Users $worker
 * @property Tasks $task
 */
class Feedbacks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'feedbacks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'task_id', 'rating'], 'required'],
            [['client_id', 'task_id', 'rating'], 'integer'],
            [['date_created'], 'safe'],
            [['comment'], 'string', 'max' => 255],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::class, 'targetAttribute' => ['task_id' => 'id']],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['client_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'Client ID',
            'task_id' => 'Task ID',
            'comment' => 'Comment',
            'rating' => 'Rating',
            'date_created' => 'Date Created',
        ];
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
     * Gets query for [[Worker]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorker()
    {
        return $this->hasOne(Users::class, ['id' => 'worker_id']);
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Tasks::class, ['id' => 'task_id']);
    }

    public function getPublishedTimePassed()
    {
        return Yii::$app->formatter->format($this->date_created, 'relativeTime');
    }
}
