<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var  $categories */

$this->title = 'Мой профиль';
?>

<main class="main-content main-content--left container">
    <style>
        .help-block:empty {
            display: none;
        }
    </style>
    <div class="left-menu left-menu--edit">
        <h3 class="head-main head-task">Настройки</h3>
        <ul class="side-menu-list">
            <li class="side-menu-item
            <?= Yii::$app->controller->action->id === 'settings' ? 'side-menu-item--active' : '' ?>">
                <a class="link link--nav" href="
                <?= Url::to(['account-settings/settings']) ?>">Мой профиль
                </a>
            </li>
            <li class="side-menu-item
            <?= Yii::$app->controller->action->id === 'security' ? 'side-menu-item--active' : '' ?>">
                <a class="link link--nav" href="<?= Url::to(['account-settings/security']) ?>">Безопасность</a>
            </li>
        </ul>
    </div>
    <div class="my-profile-form">
        <?php
        $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data'],
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'errorOptions' => ['tag' => 'span', 'class' => 'help-block'],
            ],
        ]); ?>
        <h3 class="head-main head-regular">Мой профиль</h3>
        <div class="photo-editing">
            <div>
                <p class="form-label">Аватар</p>
                <img class="avatar-preview"
                     src="<?= $model->avatar ? Yii::$app->request->baseUrl . '/' . $model->avatar : '/img/man-glasses.png' ?>"
                     width="83" height="83">
            </div>

            <div class="form-group">
                <label class="button button--black" for="avatar-upload">Сменить аватар</label>
                <?= $form->field($model, 'avatar', [
                    'template' => '{input}{error}',
                    'errorOptions' => ['tag' => 'span', 'class' => 'help-block'],
                ])->fileInput([
                    'id' => 'avatar-upload',
                    'accept' => 'image/*',
                    'style' => 'display: none',
                ])->label(false) ?>
            </div>
        </div>
        <div class="form-group">
            <?= $form->field($model, 'name')->textInput(['id' => 'profile-name']) ?>
        </div>
        <div class="half-wrapper">
            <div class="form-group">
                <?= $form->field($model, 'email')->input('email', ['id' => 'profile-email']) ?>
            </div>
            <div class="form-group">
                <?= $form->field($model, 'birthday')->input(
                    'date',
                    ['id' => 'profile-date', 'value' => $model->birthday ? date('Y-m-d', strtotime($model->birthday)) : '']
                ) ?>
            </div>
        </div>
        <div class="half-wrapper">
            <div class="form-group">
                <?= $form->field($model, 'phone')->textInput(['id' => 'profile-phone']) ?>
            </div>
            <div class="form-group">
                <?= $form->field($model, 'telegram')->textInput(['id' => 'profile-tg']) ?>
            </div>
        </div>
        <div class="form-group">
            <?= $form->field($model, 'info', [
                'inputOptions' => [
                    'id' => 'profile-info',
                    'class' => 'form-control',
                ],
                'labelOptions' => ['class' => 'control-label', 'for' => 'profile-info'],
            ])->textarea() ?>
        </div>
        <div class="form-group">
            <p class="form-label">Выбор специализаций</p>
            <div class="checkbox-profile">
                <?php
                foreach ($categories as $category) : ?>
                    <label class="control-label" for="category-<?= $category->id ?>">
                        <?= Html::checkbox(
                            'AccountSettingsForm[categories][]',
                            in_array($category->id, $model->categories),
                            [
                                'value' => $category->id,
                                'id' => 'category-' . $category->id
                            ]
                        ) ?>
                        <?= Html::encode($category->name) ?>
                    </label>
                <?php
                endforeach; ?>
            </div>
        </div>
        <input type="submit" class="button button--blue" value="Сохранить">
        <?php
        ActiveForm::end(); ?>
    </div>
</main>
