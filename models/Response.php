<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "responses".
 *
 * @property int $id
 * @property int $task_id
 * @property int $executor_id
 * @property int|null $price
 * @property string|null $comment
 * @property string|null $created_at
 *
 * @property User $executor
 * @property Task $task
 */
class Response extends ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'responses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['price', 'comment'], 'default', 'value' => null],
            [['task_id', 'executor_id'], 'required'],
            [['task_id', 'executor_id'], 'integer'],
            [
                'price',
                'number',
                'integerOnly' => true,
                'min' => 1,
                'message' => 'Цена должна быть целым положительным числом.',
            ],
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
            'price' => 'Стоимость',
            'comment' => 'Ваш Комментарий',
        ];
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
