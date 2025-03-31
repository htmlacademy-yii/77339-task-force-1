<?php

namespace App\Actions;

class ActionRespond extends AbstractAction
{
    public function getName(): string
    {
        return "Откликнуться";
    }

    public function getInternalName(): string
    {
        return "respond";
    }

    public function isAvailable(int $userId, int $customerId, ?int $executorId): bool
    {
        return $userId !== $customerId && $executorId === null;
    }
}
