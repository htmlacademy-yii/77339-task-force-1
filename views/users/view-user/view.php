<?php

use yii\helpers\Html;

/** @var $user app\models\User */
/** @var $completedTasks int */
/** @var $failedTasks int */
/** @var $reviewsDataProvider yii\data\ActiveDataProvider */

$this->title = "Профиль исполнителя " . Html::encode($user->name);
?>

<main class="main-content container">
    <div class="left-column">
        <h3 class="head-main"><?= Html::encode($user->name) ?></h3>
        <?= $this->render('_user-card', ['user' => $user]) ?>

        <?= $this->render('_user-specialization-bio', ['user' => $user]) ?>

        <h4 class="head-regular">Отзывы заказчиков</h4>
        <?= $this->render('_review-list', ['reviewsDataProvider' => $reviewsDataProvider]) ?>
    </div>
    <div class="right-column">
        <?= $this->render('_user-statistics', ['completedTasks' => $completedTasks, 'failedTasks' => $failedTasks, 'user' => $user]) ?>
        <?= $this->render('_user-contacts', ['user' => $user]) ?>
    </div>
</main>
