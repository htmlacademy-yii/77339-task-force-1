<?php

use app\models\Users;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var Users $model */

$this->registerCssFile('https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.02.min.css');

$this->registerJsFile('https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js');
$this->registerJsFile('@web/js/autocomplit.js');
?>

<section class="modal enter-form form-modal" id="auth-form-section">
    <h2 class="mb-3">Уточняющая информация:</h2>
    <?php $form = ActiveForm::begin([
        'id' => 'auth-form',
        'enableAjaxValidation' => true,
        'fieldConfig' => [
            'options' => [
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
    <?= $form->field($model, 'location', [
        'options' => [
            'class' => 'mb-3',
        ],
        'inputOptions' => [
            'id' => 'location',
            'class' => 'location'
        ]]); ?>
    <?= $form->field($model, 'latitude', ['template' => "{input}"])->hiddenInput(['id' => 'latitude']) ?>
    <?= $form->field($model, 'longitude', ['template' => "{input}"])->hiddenInput(['id' => 'longitude']) ?>

    <?= $form->field($model, 'is_worker', [
            'template' => "{label}\n{input}",
            'options' => [
                'class' => 'mb-3',
            ],
    ])->checkbox(
        [
            'uncheck' => null,
            'labelOptions' => [
                'class' => 'control-label checkbox-label'
            ],
            'checked' => true,
        ]
    ); ?>
    <?= Html::submitButton('Войти', ['class' => 'button']); ?>
    <?php ActiveForm::end(); ?>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>