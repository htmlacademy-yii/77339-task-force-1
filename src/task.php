<?php
namespace App\Logics;

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
            self::STATUS_NEW => [
                "assign" => self::STATUS_IN_PROGRESS,
                "cancel" => self::STATUS_CANCELLED,
            ],
            self::STATUS_IN_PROGRESS => [
                "execute" => self::STATUS_COMPLETED,
                "fail" => self::STATUS_FAILED,
            ],
        ];

        $actionName = $action->getInternalName();

        return $transitions[$this->currentStatus][$actionName] ?? null;
    }

    /**
     * получение доступных действий
     * @param string $status
     * @return array
     */

    public function getAvailableActions(string $status): array
    {
        $actions = [];
        if ($this->currentStatus === self::STATUS_NEW) {
            if ($userId === $this->customerId) {
                $actions[] = new ActionAssign();
                $actions[] = new ActionCancel();
            }
            if ($this->executorId === null) {
                $actions[] = new ActionRespond();
            }
        }

        if ($this->currentStatus === self::STATUS_IN_PROGRESS) {
            if ($userId === $this->customerId) {
                $actions[] = new ActionExecute();
            } else if ($userId === $this->executorId) {
                $actions[] = new ActionFail();
            }
        }

        return array_filter($actions, function ($action) use ($userId) {
            return $action->isAvailable($this->customerId, $userId, $this->executorId);
        });
    }
}
