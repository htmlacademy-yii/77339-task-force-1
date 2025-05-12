<?php

use app\models\Categories;
use app\models\Users;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/** @var yii\web\View $this */
/** @var Users $model */
/** @var Categories $categories */

$this->registerJsFile('@web/js/main.js');
$this->title = 'Taskforce | Редактирование профиля';
?>

<?php $this->beginContent('@app/views/profile/menu.php') ?>
<div class="my-profile-form">
    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'inputOptions' => [
                'class' => '',
            ],
            'errorOptions' => [
                'tag' => 'span',
                'class' => 'help-block',
            ],
        ],
    ]); ?>
    <h3 class="head-main head-regular">Мой профиль</h3>
    <div class="photo-editing">
        <div>
            <p class="form-label">Аватар</p>
            <img class="avatar-preview" src="<?= $model->avatarPath; ?>" width="83" height="83"
                 alt="<?= $model->full_name; ?>">
        </div>
        <?= $form->field($model, 'avatar', ['options' => ['tag' => false]])->fileInput([
            'hidden' => true,
            'id' => 'button-input',
        ])->label('Сменить аватар', ['class' => 'button button--black']); ?>
    </div>
    <?= $form->field($model, 'full_name'); ?>
    <div class="half-wrapper">
        <?= $form->field($model, 'email')->input('email'); ?>
        <?= $form->field($model, 'birthdate')->widget(DatePicker::class, [
            'language' => 'ru-RU',
            'dateFormat' => 'dd.MM.yyyy',
            'options' => [
                'placeholder' => 'дд.мм.гггг',
            ],
            'clientOptions' => [
                'maxDate' => 'now',
            ],
        ]); ?>
    </div>
    <div class="half-wrapper">
        <?= $form->field($model, 'phone')->textInput([
            'type' => 'tel',
            'maxlength' => 11,
            'pattern' => '\d{6,}',
            'title' => 'Поле телефон должно содержать от 6 до 11 цифр, без использования специальных знаков',
        ]); ?>
        <?= $form->field($model, 'telegram')->input('text', ['maxlength' => 64]); ?>
    </div>
    <?= $form->field($model, 'about')->textarea() ?>
    <div class="form-group">
        <p class="form-label">Выбор специализаций</p>
        <div class="checkbox-profile">
            <?= $form->field($model, 'specialities', [
                'options' => ['tag' => false, 'unselect' => null],
            ])
                ->checkboxList($categories, [
                    'tag' => false,
                    'itemOptions' => [
                        'labelOptions' => [
                            'class' => 'control-label',
                        ],
                    ],
                ])->label(false);
            ?>
        </div>
    </div>
    <?= Html::submitInput('Сохранить', ['class' => 'button button--blue']); ?>
    <?php ActiveForm::end(); ?>
</div>

<?php $this->endContent(); ?>
