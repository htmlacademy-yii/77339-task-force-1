<?php

namespace App\Actions;

class ActionCancel extends AbstractAction
{
    public function getName(): string
    {
        return "Отменить";
    }

    public function getInternalName(): string
    {
        return "cancel";
    }

    public function isAvailable(int $userId, int $customerId, ?int $executorId): bool
    {
        return $userId === $customerId;
    }
}
