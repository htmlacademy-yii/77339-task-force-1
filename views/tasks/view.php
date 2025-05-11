<?php

/** @var yii\web\View $this */
/** @var array $task */
/** @var array $reactions */
/** @var array $files */
/** @var bool $displayReactions */
/** @var array $actionsToDisplay */
/** @var array $taskMap */

use yii\helpers\Html;
use yii\helpers\Url;
use \app\components\RatingStarsWidget;

$this->registerJsFile('@web/js/main.js');

if(
    !empty($taskMap['latitude'])
    && !empty($taskMap['longitude'])
) {
    $apiKey = Yii::$app->params['apiKeyGeocoder'];
    $this->registerJsFile("https://api-maps.yandex.ru/2.1/?apikey={$apiKey}&lang=ru_RU");
    $this->registerJsFile('@web/js/map.js');
}

$this->title = 'Taskforce';
?>

<main class="main-content container" id="data-container">
    <div class="left-column">
        <div class="head-wrapper">
            <h3 class="head-main"><?= $task->title; ?></h3>
            <p class="price price--big"><?= $task->price; ?>&nbsp;₽</p>
        </div>
        <p class="task-description">
            <?= $task->description; ?></p>
        <?php if(!empty($actionsToDisplay)): ?>
            <?php foreach($actionsToDisplay as $action): ?>
                <a href="javascript:void(0)"
                   class="button action-btn <?= $action['color']; ?>"
                   data-action="<?= $action['code']; ?>"
                   data-task-id="<?= $task->id; ?>"
                >
                    <?= $action['name']; ?>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if(
            !empty($taskMap['latitude'])
            && !empty($taskMap['longitude'])
        ): ?>
            <div class="task-map">
                <?= Html::hiddenInput('latitude', $taskMap['latitude'], ['id' => 'latitude']) ?>
                <?= Html::hiddenInput('longitude', $taskMap['longitude'], ['id' => 'longitude']) ?>
                <div id="map" class="map"></div>
                <?php if(!empty($task->city->id)): ?>
                    <p class="map-address town"><?= $task->city->name; ?></p>
                <?php endif; ?>
                <p class="map-address"><?= $task->location; ?></p>
            </div>
        <?php endif; ?>

        <?php if($displayReactions && !empty($reactions)): ?>
            <h4 class="head-regular">Отклики на задание</h4>
            <?php foreach($reactions as $reaction): ?>
                <?php if($reaction['display']): ?>
                    <div class="response-card">
                        <img class="customer-photo" src="<?= $reaction['img']; ?>" width="146" height="156" alt="<?= $reaction['name']; ?>">
                        <div class="feedback-wrapper">
                            <a href="<?= Url::to(['user/view', 'id' => $reaction['user_id']]) ?>" class="link link--block link--big">
                                <?= $reaction['name']; ?>
                            </a>
                            <div class="response-wrapper">
                                <?= RatingStarsWidget::widget(['ratingValue' => $reaction['rating']])?>
                                <p class="reviews"><?= $reaction['feedbacks_count']; ?></p>
                            </div>
                            <p class="response-message">
                                <?= $reaction['comment']; ?>
                            </p>
                        </div>

                        <div class="feedback-wrapper">
                            <p class="info-text"><span class="current-time"><?= $reaction['published']; ?></span></p>
                            <p class="price price--small"><?= $reaction['price']; ?>&nbsp;₽</p>
                        </div>
                        <?php if($reaction['showButtons']): ?>
                            <div class="button-popup">
                                <a href="javascript:void(0)"
                                   class="button button--blue button--small reaction-accept"
                                   data-task-id="<?= $task->id; ?>"
                                   data-reaction-id="<?= $reaction['id'];?>"
                                   data-worker-id="<?= $reaction['user_id']; ?>"
                                   data-action="<?= \Taskforce\Service\Task\TaskActions::ACTION_START ?>"
                                >
                                    Принять
                                </a>
                                <a href="javascript:void(0)"
                                   class="button button--orange button--small reaction-reject"
                                   data-task-id="<?= $task->id; ?>"
                                   data-reaction-id="<?= $reaction['id'];?>"
                                   data-action="rejectReaction"
                                >
                                    Отказать
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="right-column">
        <div class="right-card black info-card">
            <h4 class="head-card">Информация о задании</h4>
            <dl class="black-list">
                <dt>Категория</dt>
                <dd><?= $task->category->name; ?></dd>
                <dt>Дата публикации</dt>
                <dd><?= $task->getPublishedTimePassed(); ?></dd>
                <dt>Срок выполнения</dt>
                <dd><?= $task->getExpiredAtFormat(); ?></dd>
                <dt>Статус</dt>
                <dd><?= $task->getStatusDescription(); ?></dd>
            </dl>
        </div>
        <?php if (!empty($files)): ?>
            <div class="right-card white file-card">
                <h4 class="head-card">Файлы задания</h4>
                <ul class="enumeration-list">
                    <?php foreach ($files as $file): ?>
                        <li class="enumeration-item">
                            <a href="<?= $file['link'] ?>" target="_blank" class="link link--block link--clip"><?= $file['name'] ?></a>
                            <p class="file-size"><?= Yii::$app->formatter->asShortSize($file['size']); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php if (!empty($actionsToDisplay)): ?>
    <?php foreach ($actionsToDisplay as $action): ?>
        <?php if ($action['code'] === \Taskforce\Service\Task\TaskActions::ACTION_CANCEL): ?>
            <section class="pop-up pop-up--<?= $action['code']; ?> pop-up--close">
                <div class="pop-up--wrapper">
                    <h4>Отмена задания</h4>
                    <p class="pop-up-text">
                        <b>Внимание!</b><br>
                        Вы собираетесь отменить задание.<br>
                        После выполнения этого действия вы не сможете выбирать исполнителей. Также задание не будет показываться на главной странице в списке заданий.
                    </p>
                    <a class="button button--pop-up button--orange button--submit">Отменить</a>
                    <div class="button-container">
                        <button class="button--close" type="button">Закрыть окно</button>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <?php if ($action['code'] === \Taskforce\Service\Task\TaskActions::ACTION_REJECT): ?>
            <section class="pop-up pop-up--<?= $action['code']; ?> pop-up--close">
                <div class="pop-up--wrapper">
                    <h4>Отказ от задания</h4>
                    <p class="pop-up-text">
                        <b>Внимание!</b><br>
                        Вы собираетесь отказаться от выполнения этого задания.<br>
                        Это действие плохо скажется на вашем рейтинге и увеличит счетчик проваленных заданий.
                    </p>
                    <a class="button button--pop-up button--orange button--submit">Отказаться</a>
                    <div class="button-container">
                        <button class="button--close" type="button">Закрыть окно</button>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <?php if ($action['code'] === \Taskforce\Service\Task\TaskActions::ACTION_FINISH): ?>
            <section class="pop-up pop-up--<?= $action['code']; ?> pop-up--close">
            <div class="pop-up--wrapper">
                <h4>Завершение задания</h4>
                <p class="pop-up-text">
                    Вы собираетесь отметить это задание как выполненное.
                    Пожалуйста, оставьте отзыв об исполнителе и отметьте отдельно, если возникли проблемы.
                </p>
                <div class="completion-form pop-up--form regular-form">
                    <form>
                        <div class="form-group">
                            <label class="control-label" for="completion-comment">Ваш комментарий</label>
                            <textarea id="completion-comment" name="comment" required></textarea>
                        </div>
                        <p class="completion-head control-label">Оценка работы</p>
                        <?= RatingStarsWidget::widget(['ratingClass' => 'big active-stars']); ?>
                        <input type="submit" class="button button--pop-up button--blue" value="Завершить">
                    </form>
                </div>
                <div class="button-container">
                    <button class="button--close" type="button">Закрыть окно</button>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <?php if ($action['code'] === \Taskforce\Service\Task\TaskActions::ACTION_REACT): ?>
            <section class="pop-up pop-up--<?= $action['code']; ?> pop-up--close">
            <div class="pop-up--wrapper">
                <h4>Добавление отклика к заданию</h4>
                <p class="pop-up-text">
                    Вы собираетесь оставить свой отклик к этому заданию.
                    Пожалуйста, укажите стоимость работы и добавьте комментарий, если необходимо.
                </p>
                <div class="addition-form pop-up--form regular-form">
                    <form>
                        <div class="form-group">
                            <label class="control-label" for="addition-comment">Ваш комментарий</label>
                            <textarea id="addition-comment" name="comment"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="addition-price">Стоимость</label>
                            <input id="addition-price" name="worker_price" type="number" min="0" required>
                        </div>
                        <input type="submit" class="button button--pop-up button--blue" value="Откликнуться">
                    </form>
                </div>
                <div class="button-container">
                    <button class="button--close" type="button">Закрыть окно</button>
                </div>
            </div>
        </section>
        <?php endif; ?>
    <?php endforeach; ?>
    <div class="overlay"></div>
<?php endif; ?>