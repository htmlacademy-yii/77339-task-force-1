<?php

use yii\widgets\ListView;

/** @var $responsesDataProvider */
?>

<?= ListView::widget([
    'dataProvider' => $responsesDataProvider,
    'itemView' => '_response-item',
    'options' => ['class' => 'responses-list'],
    'itemOptions' => ['class' => 'response-card'],
    'emptyText' => 'Пока нет откликов на это задание',
    'emptyTextOptions' => ['class' => 'empty-responses'],
    'layout' => "{items}\n{pager}",
    'pager' => [
        'options' => ['class' => 'pagination-list'],
        'linkOptions' => ['class' => 'pagination-item'],
        'maxButtonCount' => 3,
    ]
]) ?>
