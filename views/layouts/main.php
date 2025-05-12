<?php

/** @var yii\web\View $this */

/** @var string $content */

use app\assets\AppAsset;
use app\customComponents\MainMenuWidget\MainMenuWidget;
use yii\bootstrap5\Html;
use yii\helpers\Url;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);

$this->registerCssFile('@web/css/normalize.css');
$this->registerCssFile('@web/css/style.css');
$this->registerCssFile('@web/css/site.css');

$this->registerJsFile('@web/js/main.js', ['position' => \yii\web\View::POS_END]);

$apiKey = Yii::$app->params['yandexApiKey'];
if (empty($apiKey)) {
    throw new RuntimeException('Yandex Maps API key is not configured');
}
$this->registerJsFile('https://api-maps.yandex.ru/2.1/?apikey=' . $apiKey . '&lang=ru_RU');

?>
<?php
$this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php
    $this->head() ?>
</head>
<body class="landing">
<?php
$this->beginBody() ?>

<div class="table-layout">
    <?php
    $user = Yii::$app->user->identity; ?>
    <header class="page-header">
        <nav class="main-nav">
            <a href='<?= Url::to('/tasks') ?>' class="header-logo">
                <img class="logo-image" src="<?= Url::to('@web/img/logotype.png') ?>"
                     width=227 height=60 alt="taskForce">
            </a>
            <?php
            if (Yii::$app->controller->id !== 'signup') : ?>
                <div class="nav-wrapper">
                    <?php
                    $menuItems = [
                        ['label' => 'Новое', 'url' => ['/tasks'], 'route' => 'tasks/index'],
                        ['label' => 'Мои задания', 'url' => ['/my-tasks/index', 'status' => 'new'], 'route' => 'my-tasks/index'],
                        Yii::$app->user->identity->role === 'customer' ? [
                            'label' => 'Создать задание',
                            'url' => ['/publish'],
                            'route' => 'task-creation/create',
                        ] : null,
                        ['label' => 'Настройки', 'url' => ['/account/settings'], 'route' => 'account-settings/settings'],
                    ];

                    $menuItems = array_filter($menuItems, function ($item) {
                        return $item !== null;
                    });

                    echo MainMenuWidget::widget([
                        'items' => $menuItems,
                    ]);
                    ?>
                </div>
            <?php
            endif; ?>
        </nav>
        <?php
        if (Yii::$app->controller->id !== 'signup') : ?>
            <div class="user-block">
                <?php
                if ($user->avatar !== null) : ?>
                    <a href="#">
                        <img class="user-photo" src="/<?= $user->avatar ?: 'img/avatars/1.png'; ?>" width="55" height="55" alt="Аватар">
                    </a>
                <?php
                endif; ?>
                <div class="user-menu">
                    <p class="user-name"><?= $user->name ?></p>
                    <div class="popup-head">
                        <ul class="popup-menu">
                            <li class="menu-item">
                                <a href="<?= Url::toRoute(['/account/settings']); ?>" class="link">Настройки</a>
                            </li>
                            <li class="menu-item">
                                <a href="#" class="link">Связаться с нами</a>
                            </li>
                            <li class="menu-item">
                                <a href="<?= Url::toRoute(['auth/logout']); ?>" class="link">Выход из системы</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php
        endif; ?>
    </header>
    <?= $content ?>
</div>

<?php
$this->endBody() ?>
</body>
</html>
<?php
$this->endPage() ?>
