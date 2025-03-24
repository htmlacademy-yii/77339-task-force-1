<?php
namespace App\Actions;

class ActionRespond extends Action
{
    public function getName(): string
    {
        return "Откликнуться";
    }

    public function getInternalName(): string
    {
        return "respond";
    }

    public function isAvailable(int $userId, int $customerId, ?int $executorId): boolpublic function isAvailable(int $customerId, int $userId, ?int $executorId): bool
    {
        return $userId !== $customerId && $executorId === null;
    }
}
