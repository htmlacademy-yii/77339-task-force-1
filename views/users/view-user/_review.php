<?php

use app\models\Task;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var $model app\models\Response */
?>
<div class="response-card">
    <img class="customer-photo" src="<?= Url::to('@web/img/' . $model->task->customer->avatar) ?>" width="120" height="127" alt="Фото заказчика">
    <div class="feedback-wrapper">
        <p class="feedback">«<?= Html::encode($model->comment) ?>»</p>
        <p class="task">Задание «<a href="<?= Url::to(['tasks/view', 'id' => $model->task->id]) ?>" class="link link--small"><?= Html::encode($model->task->title) ?></a>»
            <?= Html::encode(Task::getStatusLabels()[$model->task->status] ?? $model->task->status) ?></p>
    </div>
    <div class="feedback-wrapper">
        <div class="stars-rating small">
            <?= str_repeat('<span class="fill-star">&nbsp;</span>', $model->rating) ?>
            <?= str_repeat('<span>&nbsp;</span>', 5 - $model->rating) ?>
        </div>
        <p class="info-text"><span class="current-time"><?= Yii::$app->formatter->asRelativeTime($model->created_at) ?></span></p>
    </div>
</div>