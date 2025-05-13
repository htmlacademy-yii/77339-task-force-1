<?php

/** @var Task $task */

use app\models\Task;
use yii\helpers\Html;

?>
<div class="right-card black info-card">
    <h4 class="head-card">Информация о задании</h4>
    <dl class="black-list">
        <dt>Категория</dt>
        <dd><?= Html::encode($task->category->name) ?></dd>
        <dt>Дата публикации</dt>
        <dd><?= Yii::$app->formatter->asRelativeTime(strtotime($task->created_at)) ?></dd>
        <dt>Срок выполнения</dt>
        <dd><?= Yii::$app->formatter->asDatetime($task->ended_at) ?></dd>
        <dt>Статус</dt>
        <dd><?= $task->getStatusLabel() ?></dd>
    </dl>
</div>
