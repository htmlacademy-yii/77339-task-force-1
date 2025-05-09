<?php

namespace app\models;

use Taskforce\Service\Enum\ReactionStatuses;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "reactions".
 *
 * @property int $id
 * @property int $worker_id
 * @property int $task_id
 * @property int $worker_price
 * @property string|null $comment
 * @property string $date_created
 *
 * @property Tasks $task
 * @property Users $worker
 */
class Reactions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reactions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['worker_id', 'task_id', 'worker_price'], 'required'],
            [['worker_id', 'task_id', 'worker_price'], 'integer'],
            [['date_created'], 'safe'],
            [['comment'], 'string', 'max' => 255],
            [['worker_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['worker_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::class, 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'worker_id' => 'Worker ID',
            'task_id' => 'Task ID',
            'worker_price' => 'Worker Price',
        ];
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
        return Yii::$app->formatter->format($this->date_created, 'relativeTime');
    }

    public function setAcceptReactionStatus(int $reactionId): bool
    {
        $reaction = Reactions::findOne($reactionId);

        if (!$reaction) {
            throw new NotFoundHttpException("Отклик с ID $reactionId не найден");
        }

        $reaction->status = ReactionStatuses::Accept->value;

        return $reaction->save(false);
    }

    public function setRejectReactionStatus(int $reactionId): bool
    {
        $reaction = Reactions::findOne($reactionId);

        if (!$reaction) {
            throw new NotFoundHttpException("Отклик с ID $reactionId не найден");
        }

        $reaction->status = ReactionStatuses::Reject->value;

        return $reaction->save(false);
    }

    public function addWorkerReaction(object $params): bool
    {
        $this->worker_id = \Yii::$app->user->identity->id;
        $this->task_id = $params->taskId;
        $this->worker_price = $params->worker_price;
        $this->comment = $params->comment;
        $this->date_created = (new \DateTime('now', new \DateTimeZone('Europe/Moscow')))->format('Y-m-d H:i:s');

        return $this->save();
    }
}
