<?php

namespace App\Actions;

abstract class AbstractAction
{
    /**
     * Название действия
     *
     * @return string
     */
    abstract public function getName(): string;

    /**
     * Внутреннее имя действия
     *
     * @return string
     */
    abstract public function getInternalName(): string;

    /**
     * Проверка доступа к действию для пользователя
     *
     * @param int $customerId заказчик
     * @param ?int $executorId исполнитель
     * @param int $userId текущий пользователь
     * @return bool
     */
    abstract public function isAvailable(int $userId, int $customerId, ?int $executorId): bool;
}
