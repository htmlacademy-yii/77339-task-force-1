<?php

use app\models\Users;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var Users $model */

$this->title = 'Taskforce | Настройки безопасности';
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
    <h3 class="head-main head-regular">Настройки безопасности</h3>
    <?= $form->field($model, 'oldPassword')->passwordInput(); ?>
    <?= $form->field($model, 'newPassword')->passwordInput(); ?>
    <?= $form->field($model, 'newPasswordRepeat')->passwordInput(); ?>
    <?= $form->field($model, 'is_private')->checkbox() ?>
    <?= Html::submitInput('Сохранить', ['class' => 'button button--blue']); ?>
    <?php ActiveForm::end(); ?>
</div>
<?php $this->endContent(); ?>
