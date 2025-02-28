<?php


class Task
{
    // статусы
    const string STATUS_NEW = 'new';
    const string STATUS_CANCELLED = 'cancelled';
    const string STATUS_IN_PROGRESS = 'inProgress';
    const string STATUS_COMPLETED = 'completed';
    const string STATUS_FAILED = 'failed';

    // действия
    const string ACTION_REFUSAL = 'refused';
    const string ACTION_CANCEL = 'cancel';
    const string ACTION_RESPOND = 'respond';
    const string ACTION_EXECUTE = 'execute';

    private string $currentStatus;
    private int $customerId;
    private int $executorId;

    public function __construct(int $customerId, int $executorId, string $currentStatus = self::STATUS_NEW)
    {
        $this->customerId = $customerId;
        $this->executorId = $executorId;
        $this->currentStatus = $currentStatus;
    }

    /**
     * статусы
     * @return string[]
     */
    public static function getStatus(): array
    {
        return [
            self::STATUS_NEW => 'Новое',
            self::STATUS_CANCELLED => 'Отменено',
            self::STATUS_IN_PROGRESS => 'В работе',
            self::STATUS_COMPLETED => 'Выполнено',
            self::STATUS_FAILED => 'Провалено'
        ];
    }

    /**
     * действия
     * @return string[]
     */
    public static function getActions(): array
    {
        return [
            self::ACTION_CANCEL => 'Отменить',
            self::ACTION_EXECUTE => 'Выполнено',
            self::ACTION_RESPOND => 'Откликнуться',
            self::ACTION_REFUSAL => 'Отказаться'
        ];
    }

    /**
     * получение следующего статуса
     * @param string $action
     * @return string|null
     */
    public function getNextStatus(string $action): ?string
    {
        $transitions = [
            self::ACTION_RESPOND => self::STATUS_IN_PROGRESS,
            self::ACTION_CANCEL => self::STATUS_CANCELLED,
            self::ACTION_EXECUTE => self::STATUS_COMPLETED,
            self::ACTION_REFUSAL => self::STATUS_CANCELLED,
        ];

        return $transitions[$action] ?? null;
    }

    /**
     * получение доступных действий
     * @param string $status
     * @return array
     */

    public function getAvailableActions(string $status): array
    {
        $actions = [
            self::STATUS_NEW => [self::ACTION_RESPOND, self::ACTION_CANCEL],
            self::STATUS_IN_PROGRESS => [self::ACTION_EXECUTE, self::ACTION_REFUSAL],
        ];
        return $actions[$status] ?? [];
    }
}
