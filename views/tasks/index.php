<?php

use app\models\TasksSearch;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var array $tasks */
/** @var array $categories */
/** @var TasksSearch $searchModel */

$this->title = 'Taskforce';
?>
<main class="main-content container">
    <div class="left-column">
        <h3 class="head-main head-task">Новые задания</h3>
        <?php foreach ($tasks as $task): ?>
        <div class="task-card">
            <div class="header-task">
                <a href="<?= Url::to(['tasks/view', 'id' => $task->id]); ?>" class="link link--block link--big">
                    <?= $task->title; ?>
                </a>
                <p class="price price--task">
                    <?= $task->price; ?>&nbsp;₽
                </p>
            </div>
            <p class="info-text">
                <span class="current-time">
                    <?= $task->getPublishedTimePassed(); ?>
                </span></p>
            <p class="task-text"><?= $task->description; ?>
            </p>
            <div class="footer-task">
                <p class="info-text town-text"><?= $task->city->name; ?></p>
                <p class="info-text category-text"><?= $task->category->name; ?></p>
                <a href="#" class="button button--black">Смотреть Задание</a>
            </div>
        </div>
        <?php endforeach; ?>
        <div class="pagination-wrapper">
            <ul class="pagination-list">
                <li class="pagination-item mark">
                    <a href="#" class="link link--page"></a>
                </li>
                <li class="pagination-item">
                    <a href="#" class="link link--page">1</a>
                </li>
                <li class="pagination-item pagination-item--active">
                    <a href="#" class="link link--page">2</a>
                </li>
                <li class="pagination-item">
                    <a href="#" class="link link--page">3</a>
                </li>
                <li class="pagination-item mark">
                    <a href="#" class="link link--page"></a>
                </li>
            </ul>
        </div>
    </div>
    <div class="right-column">
        <div class="right-card black">
            <div class="search-form">
                <?php $form = ActiveForm::begin([
                    'method' => 'post'
                ]); ?>
                <h4 class="head-card">Категории</h4>
                <div class="form-group">
                    <div class="checkbox-wrapper">
                        <?= $form->field($searchModel, 'categories', [
                                'options' => ['tag' => false, 'unselect' => null]
                        ])
                            ->checkboxList($categories, [
                                    'tag' => false,
                                    'itemOptions' => [
                                        'labelOptions' => [
                                            'class' => 'control-label'
                                        ],
                                    ]
                            ])->label(false);
                        ?>
                    </div>
                </div>

                <h4 class="head-card">Дополнительно</h4>
                <?=$form->field($searchModel, 'withoutWorker', ['options' => ['class' => 'form-group']])->checkbox([
                        'label' => 'Без исполнителя',
                        'labelOptions' => [
                            'class' => 'control-label'
                        ]
                ])->label(false); ?>

                <h4 class="head-card">Период</h4>
                <?=$form->field($searchModel, 'hoursPeriod', [
                    'template' => "{label}\n{input}",
                    'options' => ['class' => 'filters-block__field field field--select field--small']
                ])->dropDownList([
                        '' => 'Выберите интервал',
                        1 => '1 час',
                        12 => '12 часов',
                        24 => '24 часа'
                ])->label(false); ?>

                <?= Html::submitInput('Искать', ['class' => 'button button--blue']); ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</main>