<?php

/** @var $model User */

/** @var $cities */

use app\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Регистрация';

?>
<main class="container container--registration">
    <style>
        .help-block:empty {
            display: none;
        }
    </style>
    <div class="center-block">
        <div class="registration-form regular-form">
            <?php
            $form = ActiveForm::begin([
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'errorOptions' => ['tag' => 'span', 'class' => 'help-block'],
                ],
            ]) ?>
            <h3 class="head-main head-task">Регистрация нового пользователя</h3>
            <?= $form->field($model, 'name')
                ->textInput(['id' => 'username']) ?>

            <div class="half-wrapper">
                <?= $form->field($model, 'email')
                    ->input('email', ['id' => 'email-user']) ?>
                <?= $form->field($model, 'city')->dropDownList(
                    $cities,
                    [
                        'prompt' => 'Выберите город',
                        'id' => 'town-user',
                        'class' => 'form-control'
                    ]
                ) ?>
            </div>

            <div class="half-wrapper">
                <div class="form-group">
                    <?= $form->field($model, 'password')
                        ->passwordInput(['id' => 'password-user']) ?>
                </div>
            </div>

            <div class="half-wrapper">
                <?= $form->field($model, 'password_repeat')
                    ->passwordInput(['id' => 'password-repeat-user']) ?>
            </div>
            <div class="form-group">
                <?= $form->field($model, 'is_executor', [
                    'options' => ['tag' => false],
                    'template' => '<label class="control-label checkbox-label">{input} Я собираюсь откликаться на заказы</label>{error}',
                ])->checkbox(['id' => 'response-user'], false) ?>
            </div>

            <?= Html::submitInput(
                'Создать аккаунт',
                ['class' => 'button button--blue']
            ) ?>

            <?php
            ActiveForm::end() ?>
        </div>
    </div>
</main>
