<?php
namespace App\Logics;

class Fail extends Action
{
    public function getName(): string
    {
        return "Отказаться";
    }

    public function getInternalName(): string
    {
        return "fail";
    }

    public function isAvailable(int $customerId, int $userId, ?int $executorId): bool
    {
        return $userId === $executorId;
    }
}
