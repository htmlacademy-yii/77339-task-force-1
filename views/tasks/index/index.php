<?php
/** @var yii\web\View $this */

/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $categories */
/** @var $task */
/** @var $tasksQuery */

$this->title = "Задания";
?>

<main class="main-content container">
    <div class="left-column">
        <h3 class="head-main head-task">Новые задания</h3>
        <?= $this->render('_task-list', ['dataProvider' => $dataProvider]) ?>
        <div class="pagination-wrapper">
            <?= $this->render('_tasks-pagination', ['pagination' => $dataProvider->pagination]) ?>
        </div>
    </div>
    <div class="right-column">
        <div class="right-card black">
            <?= $this->render('_show-form', [
                'categories' => $categories,
                'task' => $task,
            ]) ?>
        </div>
    </div>
</main>