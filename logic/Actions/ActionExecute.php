<?php

namespace app\logic\Actions;

use app\logic\AvailableActions;
use app\models\Review;
use app\models\Task;
use Yii;
use yii\db\Exception;

class ActionExecute extends AbstractAction
{
    public function getName(): string
    {
        return "Завершить задание";
    }

    public function getInternalName(): string
    {
        return "completion";
    }

    public function isAvailable(int $userId, int $customerId, ?int $executorId): bool
    {
        return $userId === $customerId;
    }

    /**
     * @throws Exception
     */
    public function execute(Task $task, Review $review): bool
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $task->status = AvailableActions::STATUS_COMPLETED;
            $review->task_id = $task->id;
            $review->customer_id = $task->customer_id;
            $review->executor_id = $task->executor_id;
            $review->created_at = date('Y-m-d H:i:s');

            if (!$task->save()) {
                throw new Exception(
                    'Не удалось обновить статус задания: ' .
                    implode('; ', array_map(fn($attr) => implode(', ', $attr), $task->getErrors()))
                );
            }

            if (!$review->save()) {
                throw new Exception(
                    'Не удалось сохранить отзыв: ' .
                    implode('; ', array_map(fn($attr) => implode(', ', $attr), $review->getErrors()))
                );
            }

            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
