<?php

/** @var $user User */

use app\customComponents\StarRatingWidget\StarRatingWidget;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="user-card">
    <div class="photo-rate">
        <?php
        $avatar = !empty($user->avatar) ? $user->avatar : 'man-glasses.jpg';
        ?>
        <img class="card-photo"
             src="<?= Url::to('@web/img/' . $avatar) ?>"
             width="191"
             height="190"
             alt="Фото пользователя">
        <div class="card-rate">
            <?= StarRatingWidget::widget(
                ['rating' => $user->executor_rating, 'wrapperClass' => 'stars-rating big',]
            ) ?>
            <span class="current-rate"><?= number_format($user->executor_rating, 2) ?></span>
        </div>
    </div>
    <p class="user-description">
        <?= Html::encode($user->info ?? 'Пользователь не указал информацию о себе') ?>
    </p>
</div>
