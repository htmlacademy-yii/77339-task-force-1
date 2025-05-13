<?php

namespace app\logic\Actions;

use app\logic\AvailableActions;
use app\models\Task;
use Exception;
use yii\web\ForbiddenHttpException;

class ActionCancel extends AbstractAction
{
    public function getName(): string
    {
        return "Отменить задание";
    }

    public function getInternalName(): string
    {
        return "cancel";
    }

    public function isAvailable(int $userId, int $customerId, ?int $executorId): bool
    {
        return $userId === $customerId;
    }

    /**
     * Отменяет задание
     *
     * @param Task $task
     * @return bool
     * @throws Exception
     */
    public function execute(Task $task): bool
    {
        if ($task->executor_id !== null) {
            throw new ForbiddenHttpException("Отменить можно только задание, на которое еще не назначен исполнитель");
        }

        if ($task->status !== AvailableActions::STATUS_NEW) {
            throw new ForbiddenHttpException("Отменить возможно только новое задание");
        }

        $task->status = AvailableActions::STATUS_CANCELLED;

        if (!$task->save()) {
            throw new Exception(
                'Не удалось отменить задание: ' .
                implode('; ', array_map(fn($attr) => implode(', ', $attr), $task->getErrors()))
            );
        }

        return true;
    }
}
