<?php

use app\models\AddTaskForm;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\DatePicker;

/** @var yii\web\View $this */
/** @var array $categories */
/** @var AddTaskForm $model */

$this->title = 'Публикация нового задания';
?>
<main class="main-content main-content--center container">
    <div class="add-task-form regular-form">
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
            <h3 class="head-main head-main">Публикация нового задания</h3>
            <?= $form->field($model, 'title') ?>
            <?= $form->field($model, 'description')->textarea() ?>
            <?= $form->field($model, 'category_id')->dropDownList($categories); ?>
            <?= $form->field($model, 'location')->textInput(['class' => 'location-icon']) ?>
            <div class="half-wrapper">
                <?= $form->field($model, 'price')->textInput(['class' => 'budget-icon']) ?>
                <?= $form->field($model, 'expired_at')->widget(DatePicker::classname(), [
                    'language' => 'ru-RU',
                    'dateFormat' => 'yyyy-MM-dd',
                    'clientOptions' => [
                        'minDate' => (new \DateTime('now', new \DateTimeZone('Europe/Moscow')))->format('Y-m-d')
                    ]
                ]); ?>
            </div>
            <?= Html::tag('label', 'Файлы', ['for' => 'addtaskform-files', 'class' => 'form-label']) ?>
            <div class="new-file">
                Добавить новый файл
                <?= $form->field($model, 'files[]', ['template' => "{input}"])->fileInput(['multiple' => 'true']) ?>
            </div>
            <?= Html::submitInput('Опубликовать', ['class' => 'button button--blue']); ?>
        <?php ActiveForm::end(); ?>
    </div>
</main>