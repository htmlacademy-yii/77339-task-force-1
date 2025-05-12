<?php

/** @var Task $task */

/** @var ActiveDataProvider $responsesDataProvider */

/** @var $availableActions */

use app\assets\YandexAsset;
use app\models\Response;
use app\models\Review;
use app\models\Task;
use yii\data\ActiveDataProvider;
use yii\web\JqueryAsset;

$this->title = $task->title;
$this->registerJsFile('@web/js/main.js', ['depends' => [JqueryAsset::class]]);

YandexAsset::register($this);
?>

<main class="main-content container">
    <?= $this->render('_task-details', [
        'task' => $task,
        'responsesDataProvider' => $responsesDataProvider,
        'availableActions' => $availableActions,
    ]) ?>
    <div class="right-column">
        <?= $this->render('_task-info', ['task' => $task]) ?>
        <div class="right-card white file-card">
            <h4 class="head-card">Файлы задания</h4>
            <ul class="enumeration-list">
                <li class="enumeration-item">
                    <a href="#" class="link link--block link--clip">my_picture.jpg</a>
                    <p class="file-size">356 Кб</p>
                </li>
                <li class="enumeration-item">
                    <a href="#" class="link link--block link--clip">information.docx</a>
                    <p class="file-size">12 Кб</p>
                </li>
            </ul>
        </div>
    </div>
</main>
<?= $this->render('//modals/_act_response', [
    'model' => new Response(),
]) ?>

<?= $this->render('//modals/_completion', [
    'model' => new Review(),
    'task' => $task,
]) ?>

<?= $this->render('//modals/_refusal', [
    'model' => new Response(),
    'task' => $task,
]) ?>

<?= $this->render('//modals/_cancel', [
    'model' => new Review(),
    'task' => $task,
]) ?>
