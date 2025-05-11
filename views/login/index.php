<?php

use app\models\Users;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var Users $model */
?>

<section class="modal enter-form form-modal" id="enter-form">
    <h2>Вход на сайт</h2>
    <div class="social">
        <h3>Авторизация через соцсети</h3>
        <?= yii\authclient\widgets\AuthChoice::widget([
            'baseAuthUrl' => ['login/auth'],
            'popupMode' => false,
        ]) ?>
    </div>
    <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'enableAjaxValidation' => true,
            'fieldConfig' => [
                    'options' => [
//                        'tag' => 'p',
                        'class' => '',
                    ],
                    'template' => "{label}\n{input}\n{error}",
                'inputOptions' => [
                    'class' => 'enter-form-email input input-middle'
                ],
                'labelOptions' => [
                    'class' => 'form-modal-description'
                ],
            ]
    ]); ?>
        <?= $form->field($model, 'email')->input('email'); ?>
        <?= $form->field($model, 'password')->passwordInput(); ?>
        <?= Html::submitButton('Войти', ['class' => 'button']); ?>
    <?php ActiveForm::end(); ?>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>