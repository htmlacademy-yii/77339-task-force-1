<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<section class="pop-up pop-up--completion pop-up--close" id="popup-completion">
    <style>
        .help-block:empty {
            display: none;
        }
    </style>
    <div class="pop-up--wrapper">
        <h4>Завершение задания</h4>
        <p class="pop-up-text">
            Вы собираетесь отметить это задание как выполненное.
            Пожалуйста, оставьте отзыв об исполнителе и отметьте отдельно, если возникли проблемы.
        </p>
        <div class="completion-form pop-up--form regular-form">
            <?php
            $form = ActiveForm::begin([
                'action' => ['/task-completion/complete', 'id' => $task->id],
                'method' => 'post',
                'id' => 'completion-form'
            ]); ?>

            <?= $form->field($model, 'comment')->textarea([
                'id' => 'completion-comment',
            ])->label('Ваш комментарий') ?>

            <p class="completion-head control-label">Оценка работы</p>
            <div class="stars-rating big active-stars" id="rating-stars">
                <span data-rating="1">&nbsp;</span>
                <span data-rating="2">&nbsp;</span>
                <span data-rating="3">&nbsp;</span>
                <span data-rating="4">&nbsp;</span>
                <span data-rating="5">&nbsp;</span></div>
            <?= $form->field($model, 'rating')->hiddenInput(['id' => 'rating-value', 'value' => 5])->label(false) ?>

            <?= Html::submitInput('Завершить', [
                'class' => 'button button--pop-up button--blue',
                'id' => 'complete-task-btn'
            ]) ?>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const stars = document.querySelectorAll('#rating-stars span');
        const ratingInput = document.getElementById('rating-value');

        stars.forEach((star, idx) => {
            star.addEventListener('click', function () {
                const rating = parseInt(this.getAttribute('data-rating'));

                // Устанавливаем значение в скрытое поле
                ratingInput.value = rating;

                // Обновляем визуальное отображение звёзд
                stars.forEach((s, i) => {
                    if (i < rating) {
                        s.classList.add('fill-star');
                    } else {
                        s.classList.remove('fill-star');
                    }
                });
            });
        });
    });
</script>
