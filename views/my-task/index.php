<?php

use yii\data\ActiveDataProvider;
use yii\widgets\Menu;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var ActiveDataProvider $dataProvider */
/** @var array $menuItems */

$this->title = 'Taskforce | Мои задания';

$status = \Yii::$app->request->get('status');
$taskListTitle = [
    'new' => 'Новые задания',
    'active' => 'Задания в процессе',
    'closed' => 'Закрытые задания',
    'expired' => 'Просроченные задания'
];

if (\Yii::$app->user->can('client')) {
    $menuItems = [
        ['label' => 'Новые', 'url' => ['my-task/index', 'status' => 'new']],
        ['label' => 'В процессе', 'url' => ['my-task/index', 'status' => 'active']],
        ['label' => 'Закрытые', 'url' => ['my-task/index', 'status' => 'closed']],
    ];
} else {
    $menuItems = [
        ['label' => 'В процессе', 'url' => ['my-task/index', 'status' => 'active']],
        ['label' => 'Просрочено', 'url' => ['my-task/index', 'status' => 'expired']],
        ['label' => 'Закрытые', 'url' => ['my-task/index', 'status' => 'closed']],
    ];
}
?>

<main class="main-content container">
    <div class="left-menu">
        <h3 class="head-main head-task">Мои задания</h3>
        <?php
        echo Menu::widget([
            'items' => $menuItems,
            'options' => [
                'class' => 'side-menu-list'
            ],
            'itemOptions' => [
                'class' => 'side-menu-item'
            ],
            'activeCssClass'=>'side-menu-item--active',
            'linkTemplate' => '<a href="{url}" class="link link--nav">{label}</a>'
        ]);
        ?>
    </div>
    <div class="left-column left-column--task">
        <h3 class="head-main head-regular"><?= $taskListTitle[$status]; ?></h3>
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemOptions' => ['class' => 'task-card'],
            'itemView' => '/tasks/_task',
            'layout' => '{items}{pager}',
            'emptyText' => 'Подходящие задания не найдены.',
            'pager' => [
                'activePageCssClass' => 'pagination-item--active',
                'prevPageCssClass' => 'pagination-item mark',
                'nextPageCssClass' => 'pagination-item mark',
                'pageCssClass' => 'pagination-item',
                'registerLinkTags' => true,
                'prevPageLabel' => '',
                'nextPageLabel' => '',
                'options' => [
                    'class' => 'pagination-list'
                ],
                'linkOptions' => [
                    'class' => 'link link--page'
                ]
            ],
        ]) ?>
    </div>
</main>