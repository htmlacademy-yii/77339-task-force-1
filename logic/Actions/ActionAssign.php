<?php

namespace app\logic\Actions;

use app\logic\AvailableActions;
use app\models\Response;
use app\models\Task;
use Yii;
use yii\db\Exception;

class ActionAssign extends AbstractAction
{
    public function getName() : string
    {
        return "Выбрать исполнителя";
    }

    public function getInternalName() : string
    {
        return "assign";
    }

    public function isAvailable(int $userId, int $customerId, ?int $executorId) : bool
    {
        Yii::info('isAvailable: userId=' . $userId . ', customerId=' . $customerId);

        return $userId === $customerId;
    }

    /**
     * @throws Exception
     */
    public function execute(Task $task, Response $response) : bool
    {
        $task->executor_id = $response->executor_id;
        $task->status = AvailableActions::STATUS_IN_PROGRESS;

        return $task->save();
    }
}
