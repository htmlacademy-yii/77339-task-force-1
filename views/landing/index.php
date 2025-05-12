<?php

use yii\data\ActiveDataProvider;
use yii\widgets\ListView;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var ActiveDataProvider $dataProvider */
?>

<section class="landing-tasks">
    <h3 class="head-main head-task">Последние задания</h3>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'options' => [
                'class' => 'mb-3'
        ],
        'itemOptions' => ['class' => 'task-card'],
        'itemView' => '_task',
        'layout' => '{items}',
    ]) ?>
    <a href="<?= Url::to(['registration/index']); ?>" class="button">Смотреть все задания</a>
</section>