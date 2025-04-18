<?php

/** @var $user */

use app\custom_components\StarRatingWidget\StarRatingWidget;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="user-card">
    <div class="photo-rate">
        <img class="card-photo" src="<?= Url::to('@web/img/' . $user->avatar) ?>" width="191" height="190" alt="Фото пользователя">
        <div class="card-rate">
            <?= StarRatingWidget::widget(
                ['rating' => $user->calculateExecutorRating(), 'wrapperClass' => 'stars-rating big',]
            ) ?>
            <span class="current-rate"><?= number_format($user->executor_rating, 2) ?></span>
        </div>
    </div>
    <p class="user-description">
        <?= Html::encode($user->info ?? 'Пользователь не указал информацию о себе') ?>
    </p>
</div>
