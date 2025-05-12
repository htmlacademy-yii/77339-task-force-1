<?php

use yii\helpers\Html;
use yii\widgets\Menu;

/** @var yii\web\View $this */
/** @var string $content */

$this->registerJsFile('@web/js/main.js');
$this->title = 'Taskforce | Редактирование профиля';
$menuTitle = 'Настройки';
?>

<main class="main-content main-content--left container">
    <div class="left-menu left-menu--edit">
        <h3 class="head-main head-task">
            <?= Html::encode($menuTitle) ?>
        </h3>
        <?php
        echo Menu::widget([
            'items' => [
                ['label' => 'Мой профиль', 'url' => ['profile/settings']],
                ['label' => 'Безопасность', 'url' => ['profile/security']],
            ],
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

    <?= $content ?>
</main>

