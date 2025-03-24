<?php
namespace App\Actions;

class ActionAssign extends Action
{
    public function getName(): string
    {
        return "Выбрать исполнителя";
    }

    public function getInternalName(): string
    {
        return "assign";
    }

    public function isAvailable(int $userId, int $customerId, ?int $executorId): bool
    {
        return $userId === $customerId;
    }
}
