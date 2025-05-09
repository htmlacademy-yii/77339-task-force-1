<?php

use app\models\Users;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var Users $model */
/** @var array $cities */

$this->title = 'Taskforce';
?>

<main class="container container--registration">
    <div class="center-block">
        <div class="registration-form regular-form">
            <?php $form = ActiveForm::begin([
                'fieldConfig' => [
                    'inputOptions' => [
                        'class' => ''
                    ],
                    'errorOptions' => [
                            'tag' => 'span',
                            'class' => 'help-block'
                    ]
                ]
            ]); ?>
                <h3 class="head-main head-task">Регистрация нового пользователя</h3>
                <?= $form->field($model, 'full_name'); ?>
                <div class="half-wrapper">
                    <?= $form->field($model, 'email')->input('email'); ?>
                    <?= $form->field($model, 'city_id')->dropDownList($cities); ?>
                </div>
                <div class="half-wrapper">
                    <?= $form->field($model, 'password')->passwordInput(); ?>
                </div>
                <div class="half-wrapper">
                    <?= $form->field($model, 'password_repeat')->passwordInput(); ?>
                </div>
                <?= $form->field($model, 'is_worker', ['template' => "{label}\n{input}"])->checkbox(
                        [
                            'uncheck' => null,
                            'labelOptions' => [
                                'class' => 'control-label checkbox-label'
                            ],
                            'checked' => true
                        ]
                ); ?>
                <?= Html::submitInput('Создать аккаунт', ['class' => 'button button--blue']); ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</main>
