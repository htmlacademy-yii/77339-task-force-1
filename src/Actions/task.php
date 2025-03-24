<?php
namespace App\Actions;
use App\Actions\Action;
use App\Actions\ActionAssign;
use App\Actions\ActionCancel;
use App\Actions\ActionExecute;
use App\Actions\ActionFail;
use App\Actions\ActionRespond;
use App\Exceptions\ActionException;
use App\Exceptions\RolesException;
use App\Exceptions\StatusException;

class Task
{
    // роли
    const string ROLE_CUSTOMER = 'customer';
    const string ROLE_EXECUTOR = 'executor';

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

    public function __construct(int $customerId, string $currentStatus = self::STATUS_NEW, ?int $executorId = null)
    {
        $this->customerId = $customerId;
        $this->executorId = $executorId;
        $this->currentStatus = $currentStatus;
    }

    public function checkRole(string $role): void
    {
        $availableRoles = [self::ROLE_CUSTOMER, self::ROLE_EXECUTOR];

        if (!in_array($role, $availableRoles)) {
            throw new RolesException("Неизвестная роль: $role");
        }
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

    public function setStatus(string $status): void {
        $availableStatuses = [self::STATUS_NEW, self::STATUS_IN_PROGRESS, self::STATUS_COMPLETED, self::STATUS_FAILED, self::STATUS_CANCELLED];

        if (!in_array($status, $availableStatuses)) {
            throw new StatusException("Неизвестный статус: $status");
        }
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
    public function getNextStatus(Action $action): ?string
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

        if (!isset($transitions[$this->currentStatus][$actionName])) {
            throw new ActionException("Действие '$actionName' невозможно в статусе '{$this->currentStatus}'");
        }

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
            return $action->isAvailable($userId, $this->customerId, $this->executorId);
        });
    }
}
