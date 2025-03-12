<?php
namespace App\Logics;

abstract class Action
{
    abstract public function getName(): string;
    abstract public function getInternalName(): string;
    abstract public function isAvailable(int $customerId, int $userId, ?int $executorId): bool;
}
