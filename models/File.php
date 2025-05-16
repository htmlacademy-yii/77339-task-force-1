<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int
 * @property int
 * @property string
 * @property string|null
 *
 * @property Task
 */
class File extends ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['task_id', 'path'], 'required'],
            [['task_id'], 'integer'],
            [['created_at'], 'safe'],
            [['path'], 'string', 'max' => 255],
            [
                ['task_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Task::class,
                'targetAttribute' => ['task_id' => 'id']
            ],
            ['size', 'integer'],
            ['size', 'default', 'value' => 0],
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
            'path' => 'Path',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getTask(): ActiveQuery
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }

}
