<?php
/** @var $dataProvider */

/** @var $statusFilter */

?>

<main class="main-content container">
    <div class="left-menu">
        <h3 class="head-main head-task">Мои задания</h3>
        <ul class="side-menu-list">
            <?php

            use yii\helpers\Url;

            if ($isCustomer) : ?>
                <li class="side-menu-item <?= $statusFilter === 'new' ? 'side-menu-item--active' : '' ?>">
                    <a class="link link--nav" href="<?= Url::to(['my-tasks/index', 'status' => 'new']) ?>">
                        Новые
                    </a>
                </li>
                <li class="side-menu-item <?= $statusFilter === 'in_progress' ? 'side-menu-item--active' : '' ?>">
                    <a href="<?= Url::to(['my-tasks/index', 'status' => 'in_progress']) ?>" class="link link--nav">В процессе</a>
                </li>
                <li class="side-menu-item <?= $statusFilter === 'closed' ? 'side-menu-item--active' : '' ?>">
                    <a href="<?= Url::to(['my-tasks/index', 'status' => 'closed']) ?>" class="link link--nav">Закрытые</a>
                </li>
            <?php
            else : ?>
                <li class="side-menu-item <?= $statusFilter === 'in_progress' ? 'side-menu-item--active' : '' ?>">
                    <a href="<?= Url::to(['my-tasks/index', 'status' => 'in_progress']) ?>" class="link link--nav">В процессе</a>
                </li>
                <li class="side-menu-item <?= $statusFilter === 'expired' ? 'side-menu-item--active' : '' ?>">
                    <a href="<?= Url::to(['my-tasks/index', 'status' => 'expired']) ?>" class="link link--nav">
                        Просрочено</a>
                </li>
                <li class="side-menu-item <?= $statusFilter === 'closed' ? 'side-menu-item--active' : '' ?>">
                    <a href="<?= Url::to(['my-tasks/index', 'status' => 'closed']) ?>" class="link link--nav">
                        Закрытые</a>
                </li>
            <?php
            endif; ?>
        </ul>
    </div>
    <div class="left-column left-column--task">
        <h3 class="head-main head-regular"><?= $title ?></h3>
        <?= $this->render('@app/views/tasks/index/_task-list', ['dataProvider' => $dataProvider], []) ?>
        <div class="pagination-wrapper">
            <?= $this->render('@app/views/tasks/index/_tasks-pagination', ['pagination' => $dataProvider->pagination]) ?>
        </div>
    </div>
</main>
