<?php

use yii\widgets\LinkPager;

/** @var yii\data\Pagination $pagination */
?>

<?= LinkPager::widget([
    'pagination' => $pagination,
    'options' => ['class' => 'pagination-list'],
    'linkContainerOptions' => ['class' => 'pagination-item'],
    'linkOptions' => ['class' => 'link link--page'],
    'activePageCssClass' => 'pagination-item--active',
    'prevPageCssClass' => 'pagination-item mark',
    'nextPageCssClass' => 'pagination-item mark',
    'prevPageLabel' => '',
    'nextPageLabel' => '',
    'maxButtonCount' => 3,
]) ?>
