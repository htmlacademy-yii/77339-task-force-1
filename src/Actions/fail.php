<?php
namespace App\Actions;

class ActionFail extends Action
{
    public function getName(): string
    {
        return "Отказаться";
    }

    public function getInternalName(): string
    {
        return "fail";
    }

    public function isAvailable(int $userId, int $customerId, ?int $executorId): bool
    {
        return $userId === $executorId;
    }
}
