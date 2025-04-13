<?php

use app\models\Category;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var array $task */
/** @var array $categories */

?>

<?php
$form = ActiveForm::begin([
    'method' => 'post',
    'action' => ['tasks/index'],
    'enableClientScript' => false,
    'fieldConfig' => [
        'template' => "{input}\n{label}",
        'labelOptions' => ['class' => 'control-label'],
    ],
]);
?>

    <div class="search-form">
        <h4 class="head-card">Категории</h4>
        <div class="form-group">
            <div class="checkbox-wrapper">
                <?= $form->field($task, 'categoryIds', [
                    'options' => ['tag' => false],
                    'template' => "{input}",
                ])->checkboxList(
                    ArrayHelper::map($categories, 'id', 'name'),
                    [
                        'item' => function ($index, $label, $name, $checked, $value) {
                            return Html::checkbox($name, $checked, [
                                'value' => $value,
                                'label' => Html::encode($label),
                                'labelOptions' => [
                                    'class' => 'control-label',
                                    'for' => 'category-' . $value
                                ],
                            ]);
                        },
                        'tag' => false
                    ]
                ) ?>
            </div>
        </div>

        <h4 class="head-card">Дополнительно</h4>
        <div class="form-group">
            <?= $form->field($task, 'noResponses')->checkbox([
                'value' => 1,
                'uncheck' => 0,
                'label' => 'Без откликов',
                'labelOptions' => ['class' => 'control-label'],
            ]) ?>
            <?= $form->field($task, 'noLocation')->checkbox([
                'value' => 1,
                'uncheck' => 0,
                'label' => 'Удаленная работа',
                'labelOptions' => ['class' => 'control-label'],
            ]) ?>
        </div>

        <h4 class="head-card">Период</h4>
        <?= $form->field($task, 'filterPeriod', [
            'options' => ['class' => 'form-group'],
            'template' => "{label}\n{input}",
            'labelOptions' => ['class' => 'period-value', 'for' => 'period-value'],
        ])->dropDownList([
            '' => 'Любое время',
            '3600' => 'За последний час',
            '86400' => 'За сутки',
            '604800' => 'За неделю'
        ], [
            'id' => 'period-value',
            'class' => '',
        ])->label('') ?>

        <?= Html::submitInput('Искать', ['class' => 'button button--blue']) ?>
    </div>
<?php
ActiveForm::end() ?>