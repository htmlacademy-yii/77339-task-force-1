<?php
/** @var View $this */

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

$taskId = Yii::$app->request->get('id');
?>

<section class="pop-up pop-up--act_response pop-up--close" id="popup-act_response">
    <style>
        .help-block:empty {
            display: none;
        }
    </style>
    <div class="pop-up--wrapper">
        <h4>Добавление отклика к заданию</h4>
        <p class="pop-up-text">
            Вы собираетесь оставить свой отклик к этому заданию.
            Пожалуйста, укажите стоимость работы и добавьте комментарий, если необходимо.
        </p>

        <div class="addition-form pop-up--form regular-form">
            <?php
            $form = ActiveForm::begin([
                'action' => ['/responses/create'],
                'method' => 'post',
            ]); ?>

            <?= $form->field($model, 'comment')->textarea([
                'id' => 'addition-comment',
            ])->label('Ваш комментарий') ?>

            <?= $form->field($model, 'price')->textInput([
                'id' => 'addition-price',
            ])->label('Стоимость') ?>

            <?= Html::hiddenInput('task_id', $taskId) ?>

            <?= Html::submitInput('Завершить', ['class' => 'button button--pop-up button--blue']) ?>

            <?php ActiveForm::end(); ?>
        </div>

        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
