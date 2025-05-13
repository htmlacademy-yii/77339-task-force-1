<?php

?>
<section class="pop-up pop-up--cancel pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Отмена задания</h4>
        <p class="pop-up-text">
            <b>Внимание!</b><br>
            Вы собираетесь отменить это задание.<br>
            Вы уверены, что хотите отменить это задание?
        </p>
        <?php

        use yii\helpers\Html;
        use yii\widgets\ActiveForm;

        $form = ActiveForm::begin([
            'action' => ['/task-cancel/cancel', 'id' => $task->id],
            'method' => 'post',
        ]); ?>

        <?= Html::submitInput('Отменить', [
            'class' => 'button button--pop-up button--orange',
        ]) ?>

        <?php
        ActiveForm::end(); ?>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
