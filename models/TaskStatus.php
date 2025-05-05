<?php

namespace app\models;

class TaskStatus
{
    public const STATUS_NEW = 'new';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELED = 'canceled';

    private static array $labels = [
        self::STATUS_NEW => 'Новое',
        self::STATUS_IN_PROGRESS => 'В работе',
        self::STATUS_COMPLETED => 'Выполнено',
        self::STATUS_FAILED => 'Провалено',
        self::STATUS_CANCELED => 'Отменено'
    ];

    public static function getLabels(): array
    {
        return self::$labels;
    }

    public static function getLabel(string $status): string
    {
        return self::$labels[$status] ?? $status;
    }

    public static function getValidStatuses(): array
    {
        return array_keys(self::$labels);
    }
} 