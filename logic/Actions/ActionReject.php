<?php

declare(strict_types=1);

namespace app\logic\Actions;

use app\models\Response;
use Throwable;
use yii\db\StaleObjectException;

/**
 * Отказать исполнителю на отклик на задание
 */
final class ActionReject extends AbstractAction
{

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return "Отказать";
    }

    /**
     * @inheritDoc
     */
    public function getInternalName(): string
    {
        return "refuse_response";
    }

    /**
     * @inheritDoc
     */
    public function isAvailable(int $userId, int $customerId, ?int $executorId): bool
    {
        return $userId === $customerId;
    }

    /**
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function execute(Response $response): bool
    {
        if ($response->delete() > 0) {
            return true;
        }

        return false;
    }
}
