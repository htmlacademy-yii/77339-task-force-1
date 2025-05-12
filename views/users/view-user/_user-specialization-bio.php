<?php
/** @var $user */

use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="specialization-bio">
    <div class="specialization">
        <p class="head-info">Специализации</p>
        <ul class="special-list">
            <?php foreach ($user->categories as $category) : ?>
                <li class="special-item">
                    <a href="<?= Url::to(['tasks/index', 'category_id' => $category->id]) ?>"
                       class="link link--regular">
                        <?= Html::encode($category->name) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="bio">
        <p class="head-info">Био</p>
        <p class="bio-info">
            <span class="country-info">Россия</span>,
            <span class="town-info"><?= Html::encode($user->city->name ?? 'Не указано') ?></span>,
            <span class="age-info"><?= $user->getAge() !== null ? $user->getAge() . ' лет' : 'Не указано' ?></span>
        </p>
    </div>
</div>
