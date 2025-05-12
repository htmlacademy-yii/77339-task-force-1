<?php

namespace app\logic\Actions;

class ActionFail extends AbstractAction
{
    public function getName(): string
    {
        return "Отказаться от задания";
    }

    public function getInternalName(): string
    {
        return "refusal";
    }

    public function isAvailable(int $userId, int $customerId, ?int $executorId): bool
    {
        return $userId === $executorId;
    }
}
