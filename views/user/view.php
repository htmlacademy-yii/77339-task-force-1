<?php

use yii\helpers\Url;
use \app\components\RatingStarsWidget;

/** @var yii\web\View $this */
/** @var object $user */
/** @var array $userData */

$this->title = 'Taskforce';
?>

<main class="main-content container">
    <div class="left-column">
        <h3 class="head-main"><?= $user->full_name; ?></h3>
        <div class="user-card">
            <div class="photo-rate">
                <img class="card-photo" src="<?= $user->avatar->file_path; ?>" width="191" height="190" alt="Фото пользователя">
                <div class="card-rate">
                    <?= RatingStarsWidget::widget(['ratingValue' => $userData['rating'], 'ratingClass' => 'big'])?>
                    <span class="current-rate"><?= $userData['rating']; ?></span>
                </div>
            </div>
            <p class="user-description">
                <?= $user->about; ?>
            </p>
        </div>
        <div class="specialization-bio">
            <div class="specialization">
                <p class="head-info">Специализации</p>
                <?php if (empty($userData['specialities'])): ?>
                    <p>Пока не выбраны</p>
                <?php else: ?>
                <ul class="special-list">
                    <?php foreach ($userData['specialities'] as $speciality): ?>
                    <li class="special-item">
                        <a href="<?= Url::to(['tasks/category/' . $speciality->id]) ?>" class="link link--regular">
                            <?= $speciality->name; ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
            <div class="bio">
                <p class="head-info">Био</p>
                <p class="bio-info"><span class="country-info">Россия</span>, <span class="town-info"><?= $user->city->name; ?></span>, <span class="age-info"><?= $userData['age']; ?></span> </p>
            </div>
        </div>
        <?php if(!empty($user->workerFeedbacks)): ?>
            <h4 class="head-regular">Отзывы заказчиков</h4>
            <?php foreach ($user->workerFeedbacks as $feedback): ?>
                <div class="response-card">
                    <img class="customer-photo" src="<?= $feedback->client->avatar->file_path?>" width="120" height="127" alt="<?= $feedback->client->full_name; ?>">
                    <div class="feedback-wrapper">
                        <p class="feedback">
                            <?= $feedback->comment; ?>
                        </p>
                        <p class="task">Задание «<a href="<?= Url::to(['tasks/view', 'id' => $feedback->task->id]); ?>" class="link link--small"><?= $feedback->task->title; ?></a>» выполнено</p>
                    </div>
                    <div class="feedback-wrapper">
                        <?= RatingStarsWidget::widget(['ratingValue' => $feedback->rating])?>
                        <p class="info-text">
                            <?= $feedback->getPublishedTimePassed(); ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="right-column">
        <div class="right-card black">
            <h4 class="head-card">Статистика исполнителя</h4>
            <dl class="black-list">
                <dt>Всего заказов</dt>
                <dd><?= $user->finishedTasks; ?> выполнено, <?= $user->failedTasks; ?> провалено</dd>
                <dt>Место в рейтинге</dt>
                <dd><?= $userData['ratingPlace']; ?></dd>
                <dt>Дата регистрации</dt>
                <dd><?= $user->getRegisterDateFormat(); ?></dd>
                <dt>Статус</dt>
                <dd><?= $userData['status']; ?></dd>
            </dl>
        </div>
        <div class="right-card white">
            <h4 class="head-card">Контакты</h4>
            <ul class="enumeration-list">
                <?php if(!empty($user->phone)): ?>
                    <li class="enumeration-item">
                        <a href="tel:<?= $userData['userPhone']; ?></a" class="link link--block link--phone"><?= $userData['userPhone']; ?></a>
                    </li>
                <?php endif; ?>
                <li class="enumeration-item">
                    <a href="mailto:<?= $user->email; ?>" class="link link--block link--email"><?= $user->email; ?></a>
                </li>
                <?php if(!empty($user->telegram)): ?>
                <li class="enumeration-item">
                    <a href="https://t.me/<?= $user->telegram; ?>" class="link link--block link--tg">@<?= $user->telegram; ?></a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</main>
