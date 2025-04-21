<?php

/** @var Response $model */

use app\customComponents\StarRatingWidget\StarRatingWidget;
use app\models\Response;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="response-card">
    <img class="customer-photo" src="<?= Url::to('@web/img/' . $model->executor->avatar) ?>" width="146" height="156"
         alt="Фото заказчиков">
    <div class="feedback-wrapper">
        <a href="<?= Url::to(['users/view', 'id' => $model->executor->id]) ?>" class="link link--block link--big"><?= Html::encode(
                $model->executor->name
            ) ?></a>
        <div class="response-wrapper">
            <?= StarRatingWidget::widget(
                ['rating' => $model->executor->executor_rating, 'wrapperClass' => 'stars-rating small',]
            ) ?>
            <p class="reviews"><?= $model->executor->executor_reviews_count ?></p>
        </div>
        <p class="response-message">
            <?= Html::encode($model->comment) ?>
        </p>

    </div>
    <div class="feedback-wrapper">
        <p class="info-text"><span class="current-time"><?= Yii::$app->formatter->asRelativeTime($model->created_at) ?></p>
        <p class="price price--small">3700 ₽</p>
    </div>
    <div class="button-popup">
        <a href="#" class="button button--blue button--small">Принять</a>
        <a href="#" class="button button--orange button--small">Отказать</a>
    </div>
</div>
