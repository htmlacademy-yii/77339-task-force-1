<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int
 * @property int
 * @property int
 * @property int
 * @property int
 * @property string|null
 * @property string|null
 *
 * @property User
 * @property User
 * @property Task
 */
class Review extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'reviews';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['comment', 'rating'], 'required'],
            ['rating', 'integer', 'min' => 1, 'max' => 5],
            [['comment'], 'default', 'value' => null],
            [['task_id', 'customer_id', 'executor_id'], 'safe'],
            [['task_id', 'customer_id', 'executor_id', 'rating'], 'integer'],
            [['comment'], 'string', 'max' => 255],
            [['created_at'], 'safe'],
            [
                ['task_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Task::class,
                'targetAttribute' => ['task_id' => 'id']
            ],
            [
                ['customer_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['customer_id' => 'id']
            ],
            [
                ['executor_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['executor_id' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'customer_id' => 'Customer ID',
            'executor_id' => 'Executor ID',
            'rating' => 'Rating',
            'comment' => 'Comment',
            'created_at' => 'Created At',
        ];
    }
    /**
     * @param bool
     * @param array
     * @return void
     */

    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->executor) {
            $this->executor->updateExecutorStars();
        }
    }

    /**
     * @return void
     */
    public function afterDelete(): void
    {
        parent::afterDelete();

        if ($this->executor) {
            $this->executor->updateExecutorStars();
        }
    }

    /**
     * @return ActiveQuery
     */
    public function getCustomer(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'customer_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getExecutor(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'executor_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTask(): ActiveQuery
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }

}
