<?php
require_once 'task.php';

$task = new Task(1, 2);
assert($task->getNextStatus('cancel') === Task::STATUS_CANCELLED,
        'Отмена действия приводит к отменённому статусу');
assert($task->getAvailableActions('new') === ['respond', 'cancel'],
    'Необходимы действия');

echo "Все тесты пройдены!" . PHP_EOL;
