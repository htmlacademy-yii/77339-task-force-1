<?php

use app\models\Response;
use app\models\Task;
use yii\db\Expression;
use yii\db\Migration;

class m250329_095249_fix_response_dates extends Migration
{
    public function up()
    {
        $tasks = Task::find()->all();

        foreach ($tasks as $task) {
            Response::updateAll([
                'created_at' => new Expression(
                    'DATE_ADD(:taskDate, INTERVAL FLOOR(RAND()*48) HOUR)',
                    [':taskDate' => $task->created_at]
                )
            ],
                ['task_id' => $task->id]
            );
        }
    }

    public function down(): false
    {
        echo "m250329_095249_fix_response_dates cannot be reverted.\n";

        return false;
    }
}
