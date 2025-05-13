<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<section class="pop-up pop-up--refusal pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Отказ от задания</h4>
        <p class="pop-up-text">
            <b>Внимание!</b><br>
            Вы собираетесь отказаться от выполнения этого задания.<br>
            Это действие плохо скажется на вашем рейтинге и увеличит счетчик проваленных заданий.
        </p>
        <?php
        $form = ActiveForm::begin([
            'action' => ['/task-refusal/refusal', 'id' => $task->id],
            'method' => 'post',
        ]); ?>

        <?= Html::submitInput('Отказаться', [
            'class' => 'button button--pop-up button--orange',
        ]) ?>

        <?php ActiveForm::end(); ?>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
