<?php
namespace App\Actions;

abstract class Actions
{
    abstract public function getName(): string;
    abstract public function getInternalName(): string;
    abstract public function isAvailable(int $userId, int $customerId, ?int $executorId): bool;
}
