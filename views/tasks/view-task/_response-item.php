<?php

/** @var Response $model */

use app\customComponents\StarRatingWidget\StarRatingWidget;
use app\models\Response;
use yii\helpers\Html;
use yii\helpers\Url;

$user = Yii::$app->user->identity;
$isExecutorAssigned = $model->task->executor_id !== null;
?>
<?php
$avatarPath = $model->executor->avatar ? Url::to('@web/' . $model->executor->avatar) : Url::to(
    '@web/img/man-glasses.jpg'
);

?>
<img class="customer-photo" src="<?= $avatarPath; ?>"
     width="146" height="156" alt="Фото заказчиков">
<div class="feedback-wrapper">
    <a href="<?= Url::to(['users/view', 'id' => $model->executor->id]); ?>" class="link link--block link--big">
        <?= Html::encode(
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
    <p class="info-text"><span class="current-time">
            <?= Yii::$app->formatter->asRelativeTime(strtotime($model->created_at)) ?>
    </p>
    <?php
    if (!empty($model->price)) : ?>
        <p class="price price--small"><?= Html::encode($model->price) ?> ₽</p>
    <?php
    endif; ?>
</div>
<?php
if ($user->role === 'customer' && !$isExecutorAssigned) : ?>
    <div class="button-popup">
        <?= Html::a(
            'Принять',
            ['response/accept', 'id' => $model->id], ['class' => 'button button--blue button--small']
        ) ?>
        <?= Html::a(
            'Отказать',
            ['response/reject', 'id' => $model->id], ['class' => 'button button--orange button--small']
        ) ?>
    </div>
<?php
endif; ?>
