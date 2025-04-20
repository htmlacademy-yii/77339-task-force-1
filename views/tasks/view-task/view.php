<?php

/** @var Task $task */
/** @var ActiveDataProvider $responsesDataProvider */

use app\models\Task;
use yii\data\ActiveDataProvider;
$this->title = $task->title;
?>

<main class="main-content container">
    <?= $this->render('_task-details', ['task' => $task, 'responsesDataProvider' => $responsesDataProvider]) ?>
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
