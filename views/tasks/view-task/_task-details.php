<?php

/** @var Task $task */

/** @var ActiveDataProvider $responsesDataProvider */

/** @var $availableActions */

use app\customComponents\ActionButtonsWidget\ActionButtonsWidget;
use app\helpers\YandexMapHelper;
use app\models\Response;
use app\models\Task;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

$isCustomer = Yii::$app->user->id === $task->customer_id;
$userIsExecutorOfAnyResponse = Response::find()->where(['executor_id' => Yii::$app->user->id, 'task_id' => $task->id])->exists();

$latitude = $task->latitude;
$longitude = $task->longitude;

$this->registerJS(
    <<<JS
    ymaps.ready(init);
function init() {
    var myMap = new ymaps.Map('map', {
        center: [$latitude, $longitude],
        zoom: 16
    })

    myMap.controls.remove('trafficControl');
    myMap.controls.remove('searchControl');
    myMap.controls.remove('geolocationControl');
    myMap.controls.remove('typeSelector');
    myMap.controls.remove('fullscreenControl');
    myMap.controls.remove('rulerControl');

    var placemark = new ymaps.Placemark([$latitude, $longitude]);
        myMap.geoObjects.add(placemark);
}
JS,
    View::POS_READY
);

$mapHelper = new YandexMapHelper(Yii::$app->params['yandexApiKey']);

$mapHelper->setCache(Yii::$app->cache);

$address = $task->latitude && $task->longitude ? $mapHelper->getAddress($task->latitude, $task->longitude) : 'Адрес не указан';
?>

<div class="left-column">
    <div class="head-wrapper">
        <h3 class="head-main"><?= Html::encode($task->title) ?></h3>
        <p class="price price--big"><?= Html::encode($task->budget) ?> ₽</p>
    </div>
    <p class="task-description"><?= Html::encode($task->description) ?></p>
    <?= ActionButtonsWidget::widget([
        'availableActions' => $availableActions,
        'currentUserId' => Yii::$app->user->id,
        'task' => $task,
    ]); ?>
    <?php if ($task->latitude && $task->longitude) : ?>
        <div class="task-map">
            <div id="map" style="width: 725px; height: 346px;"></div>
            <?php
            $addressFromCoords = $mapHelper->getAddress($task->latitude, $task->longitude);
            $addressParts = explode(', ', $addressFromCoords);
            $cityFromAddress = $addressParts[1] ?? '';
            $streetAddress = implode(', ', array_slice($addressParts, 2));
            ?>
            <?php if ($cityFromAddress) : ?>
                <p class="map-address town"><?= Html::encode($cityFromAddress) ?></p>
            <?php endif; ?>
            <?php if ($streetAddress) : ?>
                <p class="map-address"><?= Html::encode($streetAddress) ?></p>
            <?php endif; ?>
        </div>
    <?php else : ?>
        <div class="task-remote">
            <h4>Удалённая работа</h4>
            <p>Задание можно выполнить из любой точки мира</p>
        </div>
    <?php endif; ?>
    <?php if ($isCustomer || $responsesDataProvider->getTotalCount() > 0) : ?>
        <h4 class="head-regular"><?= $isCustomer ? 'Отклики на задание' : 'Ваш отклик' ?></h4>
        <?php if ($responsesDataProvider->getTotalCount() > 0) : ?>
            <?= $this->render('_response-list', ['responsesDataProvider' => $responsesDataProvider]) ?>
        <?php else : ?>
            <p class="text-muted"><?= $isCustomer ? 'Пока нет откликов на это задание.' : 'Вы ещё не оставляли отклик.' ?></p>
        <?php endif; ?>
    <?php endif; ?>
</div>
<div class="overlay"></div>
