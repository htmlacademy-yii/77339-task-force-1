<?php

namespace App\Actions;

class ActionExecute extends AbstractAction
{
    public function getName(): string
    {
        return "Выполнено";
    }

    public function getInternalName(): string
    {
        return "execute";
    }

    public function isAvailable(int $userId, int $customerId, ?int $executorId): bool
    {
        return $userId === $customerId;
    }
}
