<?php

namespace app\logic\Actions;

use app\logic\AvailableActions;
use app\models\Response;
use app\models\Task;
use yii\db\Exception;

class ActionRespond extends AbstractAction
{
    public function getName(): string
    {
        return "Откликнуться на задание";
    }

    public function getInternalName(): string
    {
        return "act_response";
    }

    public function isAvailable(int $userId, int $customerId, ?int $executorId): bool
    {
        return $userId !== $customerId && $executorId === null;
    }

    /**
     * @throws Exception
     */
    public function execute(Task $task, Response $response): bool
    {
        $task->executor_id = $response->executor_id;
        $task->status = AvailableActions::STATUS_IN_PROGRESS;
        return $task->save();
    }
}
