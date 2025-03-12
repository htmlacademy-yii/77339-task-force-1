<?php
namespace App\Logics;

class Execute extends Action
{
    public function getName(): string
    {
        return "Выполнено";
    }

    public function getInternalName(): string
    {
        return "execute";
    }

    public function isAvailable(int $customerId, int $userId, ?int $executorId): bool
    {
        return $userId === $customerId;
    }
}
