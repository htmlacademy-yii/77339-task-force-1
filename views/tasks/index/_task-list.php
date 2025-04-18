<?php
use yii\widgets\ListView;
/** @var yii\data\ActiveDataProvider $dataProvider */
?>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_preview-task',
    'layout' => "{items}",
    'options' => ['class' => 'task-list'],
    'itemOptions' => ['class' => 'task-card'],
    'emptyText' => 'Нет заданий по выбранным критериям',
    'emptyTextOptions' => ['class' => 'empty-tasks']
]) ?>
