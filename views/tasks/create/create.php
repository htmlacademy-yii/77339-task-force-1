<?php

/* @var $model app\models\Task */

/* @var $categories app\models\Category[] */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\web\View;
use yii\widgets\ActiveForm;

$this->title = 'Публикация нового задания';
$this->registerJsFile(
    'https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js',
    ['depends' => JqueryAsset::class]
);
?>
    <main class="main-content main-content--center container">
        <style>
            .help-block:empty {
                display: none;
            }
        </style>
        <div class="add-task-form regular-form">
            <?php
            $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data'],
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'errorOptions' => ['tag' => 'span', 'class' => 'help-block'],
                ],
            ]) ?>
            <h3 class="head-main head-main"><?= Html::encode($this->title) ?></h3>

            <div class="form-group">
                <?= $form->field($model, 'title')->textInput(['id' => 'essence-work']) ?>
            </div>
            <div class="form-group">
                <?= $form->field($model, 'description', [
                    'inputOptions' => [
                        'class' => 'form-control',
                        'rows' => 5
                    ]
                ])->textarea(['id' => 'username']) ?>
            </div>
            <div class="form-group">
                <?= $form->field($model, 'category_id', [
                    'inputOptions' => [
                        'id' => 'town-user',
                        'class' => 'form-control'
                    ]
                ])->dropDownList(
                    ArrayHelper::map($categories, 'id', 'name'),
                    ['prompt' => 'Выберите категорию']
                ) ?>
            </div>
            <div class="form-group">
                <?= $form->field($model, 'location')->textInput([
                    'id' => 'location',
                    'class' => 'location-icon',
                    'placeholder' => 'Город, улица, дом',
                    'autocomplete' => 'off'
                ]) ?>

                <?= $form->field($model, 'latitude')->hiddenInput(['id' => 'task-latitude'])->label(false) ?>
                <?= $form->field($model, 'longitude')->hiddenInput(['id' => 'task-longitude'])->label(false) ?>

            </div>
            <div class="half-wrapper">
                <div class="form-group">
                    <?= $form->field($model, 'budget', [
                        'inputOptions' => [
                            'id' => 'budget',
                            'class' => 'budget-icon'
                        ]
                    ])->textInput() ?>
                </div>
                <div class="form-group">
                    <?= $form->field($model, 'ended_at', [
                        'inputOptions' => [
                            'id' => 'period-execution',
                            'class' => 'form-control',
                            'min' => date('Y-m-d')
                        ]
                    ])->input('date') ?>
                </div>
            </div>
            <p class="form-label">Файлы</p>
            <div class="new-file">
                <?= $form->field($model, 'files[]', ['template' => '{input}'])->fileInput([
                    'multiple' => true,
                    'hidden' => true,
                    'id' => 'file-upload',
                ]) ?>
                <label for="file-upload">Добавить новый файл</label>
            </div>
            <?= Html::submitInput('Опубликовать', ['class' => 'button button--blue']) ?>
            <?php
            ActiveForm::end() ?>
        </div>
    </main>


<?php
$this->registerCss(
    <<<CSS
.location-autocomplete-wrapper {
    position: relative;
    margin-bottom: 20px;
}

.autoComplete_wrapper {
    position: relative;
    display: block;
}

#autoComplete_list_1 {
    position: absolute;
    z-index: 1000;
    width: 100%;
    max-height: 300px;
    overflow-y: auto;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-top: 5px;
    padding: 0;
    list-style: none;
}

#autoComplete_list_1 li {
    padding: 8px 12px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
}

#autoComplete_list_1 li:hover,
#autoComplete_list_1 li[aria-selected="true"] {
    background-color: #f5f5f5;
}

#autoComplete_list_1 mark {
    background-color: #fff9c4;
    padding: 0;
}
CSS
);

$this->registerJs(
    <<<'JS'
(function($) {
    $(function() {
        new autoComplete({
            selector: '#location',
            placeHolder: "Город, улица, дом...",
            debounce: 300,
            threshold: 3,
            data: {
                src: function(query) {
                    return new Promise(function(resolve) {
                        if (!query || query.length < 3) return resolve([]);
                        
                        $.getJSON('/task-creation/city-list', {term: query})
                            .done(function(data) {
                                let results = data.map(function(item) {
                                    return {
                                        value: item.value,
                                        label: item.value,
                                        data: item
                                    };
                                });
                                resolve(results);
                            })
                            .fail(function() { resolve([]) });
                    });
                },
                keys: ['value'],
                cache: false
            },
            resultsList: {
                element: function(list, data) {
                    if (!data.results.length) {
                        list.innerHTML = '<div class="no-result">' + 
                            (data.query.length < 3 ? 'Введите 3+ символа' : 'Ничего не найдено') + 
                            '</div>';
                    }
                },
                noResults: true,
                maxResults: 10
            },
            resultItem: {
                content: function(data, element) {
                    element.textContent = data.match;
                },
                highlight: true
            },
            onSelection: function(feedback) {
                var selection = feedback.selection;
                $('#location').val(selection.value);
                if (selection.data.latitude && selection.data.longitude) {
                    $('#task-latitude').val(selection.data.latitude);
                    $('#task-longitude').val(selection.data.longitude);
                }
            }
        });
    });
})(jQuery);
JS,
    View::POS_READY
);
