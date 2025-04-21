<?php
/** @var $completedTasks */

/** @var $failedTasks */
/** @var $user */
?>


<div class="right-card black">
    <h4 class="head-card">Статистика исполнителя</h4>
    <dl class="black-list">
        <dt>Всего заказов</dt>
        <dd><?= $completedTasks ?> выполнено, <?= $failedTasks ?> провалено</dd>
        <dt>Место в рейтинге</dt>
        <dd><?= $user->getExecutorRank() ?> место</dd>
        <dt>Дата регистрации</dt>
        <dd><?= Yii::$app->formatter->asDate($user->created_at, 'long') ?></dd>
        <dt>Статус</dt>
        <dd><?= $user->accepts_orders ? 'Открыт для новых заказов' : 'Не принимает заказы' ?></dd>
    </dl>
</div>
