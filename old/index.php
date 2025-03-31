<?php

require_once 'vendor/autoload.php';

use App\Actions\ActionAssign;
use App\Actions\ActionCancel;
use App\Actions\ActionExecute;
use App\Actions\ActionFail;
use App\Actions\ActionRespond;
use App\Models\Task;
use App\Exceptions\RolesException;
use App\Exceptions\StatusException;

// Тест 1: Задание в статусе "Новое" может быть отменено только автором задания
$taskNew = new Task(1, Task::STATUS_NEW);
$availableActionsForCustomer = $taskNew->getAvailableActions(1);
assert(count($availableActionsForCustomer) === 2, 'Ошибка: Для заказчика должны быть доступны "Отменить" и "Выбрать исполнителя".');
assert($availableActionsForCustomer[0] instanceof ActionAssign, 'Ошибка: Должно быть доступно действие "Выбрать исполнителя".');
assert($availableActionsForCustomer[1] instanceof ActionCancel, 'Ошибка: Должно быть доступно действие "Отменить".');

$availableActionsForExecutor = $taskNew->getAvailableActions(2);
assert(count($availableActionsForExecutor) === 1, 'Ошибка: Для исполнителя должно быть доступно "Откликнуться".');
assert($availableActionsForExecutor[0] instanceof ActionRespond, 'Ошибка: Должно быть доступно действие "Откликнуться".');

// Тест 2: Задание в статусе "В работе" может быть отменено только исполнителем
$taskInProgress = new Task(1, Task::STATUS_IN_PROGRESS, 2);
$availableActionsForExecutor = $taskInProgress->getAvailableActions(2);
assert(count($availableActionsForExecutor) === 1, 'Ошибка: Для исполнителя должно быть доступно "Отказаться".');
assert($availableActionsForExecutor[0] instanceof ActionFail, 'Ошибка: Должно быть доступно действие "Отказаться".');

// Тест 3: Задание в статусе "В работе" может быть завершено только заказчиком
$availableActionsForCustomer = $taskInProgress->getAvailableActions(1);
assert(count($availableActionsForCustomer) === 1, 'Ошибка: Для заказчика должно быть доступно "Выполнить".');
assert($availableActionsForCustomer[0] instanceof ActionExecute, 'Ошибка: Должно быть доступно действие "Выполнить".');

// Тест 4: Задание в статусе "Новое" не может быть завершено или отменено исполнителем
$taskNew = new Task(1, Task::STATUS_NEW);
$availableActionsForExecutor = $taskNew->getAvailableActions(2);
assert(count($availableActionsForExecutor) === 1, 'Ошибка: Для исполнителя должно быть доступно только "Откликнуться".');
assert($availableActionsForExecutor[0] instanceof ActionRespond, 'Ошибка: Должно быть доступно действие "Откликнуться".');

// Тест 5: Задание в статусе "Завершено" не имеет доступных действий
$taskCompleted = new Task(1, Task::STATUS_COMPLETED);
assert(empty($taskCompleted->getAvailableActions(1)), 'Ошибка: Завершенная задача не должна иметь доступных действий.');

// Тест 6: Задание в статусе "Отменено" не имеет доступных действий
$taskCancelled = new Task(1, Task::STATUS_CANCELLED);
assert(empty($taskCancelled->getAvailableActions(1)), 'Ошибка: Отмененная задача не должна иметь доступных действий.');

// Тест 7: Задание в статусе "Провалено" не имеет доступных действий
$taskFailed = new Task(1, Task::STATUS_FAILED);
assert(empty($taskFailed->getAvailableActions(1)), 'Ошибка: Проваленная задача не должна иметь доступных действий.');

// Тест: проверка ролей
$task = new Task(1);
try {
    $task->checkRole('unknown_role');
    assert(false, 'Ошибка: Ожидалось исключение для неизвестной роли.');
} catch (RolesException $e) {
    assert($e->getMessage() === 'Неизвестная роль: unknown_role', 'Ошибка: Неверное сообщение исключения для неизвестной роли.');
}

// Тест: проверка корректной роли
try {
    $task->checkRole(Task::ROLE_CUSTOMER);
    assert(true, 'Роль CUSTOMER успешно прошла проверку.');
} catch (RolesException $e) {
    assert(false, 'Ошибка: Не должно было выбрасываться исключение для корректной роли.');
}

// Тест: проверка статусов
try {
    $task->setStatus('invalid_status');
    assert(false, 'Ошибка: Ожидалось исключение для неизвестного статуса.');
} catch (StatusException $e) {
    assert($e->getMessage() === 'Неизвестный статус: invalid_status', 'Ошибка: Неверное сообщение исключения для неизвестного статуса.');
}

// Тест: проверка корректного статуса
try {
    $task->setStatus(Task::STATUS_NEW);
    assert(true, 'Статус NEW успешно установлен.');
} catch (StatusException $e) {
    assert(false, 'Ошибка: Не должно было выбрасываться исключение для корректного статуса.');
}

echo "Все тесты пройдены!\n";
