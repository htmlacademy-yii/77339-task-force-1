<?php

/** @var $reviewsDataProvider */

use yii\widgets\ListView;
?>

<?= ListView::widget([
    'dataProvider' => $reviewsDataProvider,
    'itemView' => '_review',
    'layout' => "{items}\n{pager}",
    'options' => ['tag' => false],
    'itemOptions' => ['tag' => false],
    'emptyText' => 'Пока нет отзывов',
    'pager' => [
        'options' => ['class' => 'pagination-list'],
        'linkOptions' => ['class' => 'pagination-link'],
    ]
]);

?>
