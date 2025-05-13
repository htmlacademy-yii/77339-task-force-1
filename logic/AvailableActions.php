<?php

namespace app\logic;

use app\logic\Actions\AbstractAction;
use app\logic\Actions\ActionAssign;
use app\logic\Actions\ActionCancel;
use app\logic\Actions\ActionExecute;
use app\logic\Actions\ActionFail;
use app\logic\Actions\ActionRespond;

use App\Exceptions\ActionException;
use App\Exceptions\RolesException;
use App\Exceptions\StatusException;
use Yii;

class AvailableActions
{
    public const string ROLE_CUSTOMER = 'customer';
    public const string ROLE_EXECUTOR = 'executor';

    public const string STATUS_NEW = 'new';
    public const string STATUS_CANCELLED = 'canceled';
    public const string STATUS_IN_PROGRESS = 'in_progress';
    public const string STATUS_COMPLETED = 'completed';
    public const string STATUS_FAILED = 'failed';

    private string $currentStatus;
    private int $customerId;
    private ?int $executorId;

    public function __construct(int $customerId, string $currentStatus = self::STATUS_NEW, ?int $executorId = null)
    {
        $this->customerId = $customerId;
        $this->currentStatus = $currentStatus;
        $this->executorId = $executorId;
    }

    /**
     * @throws RolesException
     */
    public function checkRole(string $role) : void
    {
        $availableRoles = [self::ROLE_CUSTOMER, self::ROLE_EXECUTOR];

        if (!in_array($role, $availableRoles)) {
            throw new RolesException("Неизвестная роль: $role");
        }
    }

    /**
     * @return string[]
     */
    public static function getStatusMap() : array
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
     * @throws StatusException
     */
    public function setStatus(string $status) : void
    {
        $availableStatuses = [
            self::STATUS_NEW,
            self::STATUS_IN_PROGRESS,
            self::STATUS_COMPLETED,
            self::STATUS_FAILED,
            self::STATUS_CANCELLED
        ];

        if (!in_array($status, $availableStatuses)) {
            throw new StatusException("Неизвестный статус: $status");
        }
    }

    /**
     * @return array
     */
    public static function getActionsMap() : array
    {
        return [
            'cancel' => 'Отменить',
            'assign' => 'Выбрать исполнителя',
            'respond' => 'Откликнуться',
            'execute' => 'Завершить',
            'fail' => 'Отказаться',
        ];
    }

    /**
     *
     * @param AbstractAction
     *
     * @return string|null
     * @throws \Exception
     */
    public function getNextStatus(AbstractAction $action) : ?string
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
     * @param int
     *
     * @return array|string[]
     */
    public function getAvailableActions(int $userId) : array
    {
        $actions = [];
        if ($this->currentStatus === self::STATUS_NEW) {
            if ($userId === $this->customerId) {
                $actions[] = new ActionAssign();
                $actions[] = new ActionCancel();
            }
            if ($this->executorId === null && $userId !== $this->customerId) {
                $actions[] = new ActionRespond();
            }
        }

        if ($this->currentStatus === self::STATUS_IN_PROGRESS) {
            if ($userId === $this->customerId) {
                $actions[] = Yii::$container->get(ActionExecute::class);
            } elseif ($userId === $this->executorId) {
                $actions[] = new ActionFail();
            }
        }
        error_log("User ID: $userId, Customer ID: {$this->customerId}, Executor ID: {$this->executorId}");

        return array_filter($actions, function ($action) use ($userId) {
            return $action->isAvailable($userId, $this->customerId, $this->executorId);
        });
    }
}
