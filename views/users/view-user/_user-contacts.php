<?php
/** @var $user */

use yii\helpers\Html; ?>

<?php
if (!$user->show_contacts) : ?>
    <div class="right-card white">
        <h4 class="head-card">Контакты</h4>
        <ul class="enumeration-list">
            <li class="enumeration-item">
                <a href="tel:<?= Html::encode($user->phone) ?>" class="link link--block link--phone">
                    <?= Html::encode($user->phone) ?>
                </a>
            </li>
            <li class="enumeration-item">
                <a href="mailto:<?= Html::encode($user->email) ?>" class="link link--block link--email">
                    <?= Html::encode(
                        $user->email
                    ) ?>
                </a>
            </li>
            <?php
            if ($user->telegram) : ?>
                <li class="enumeration-item">
                    <a href="https://t.me/<?= Html::encode(ltrim($user->telegram, '@')) ?>"
                       class="link link--block link--tg"><?= Html::encode($user->telegram) ?></a>
                </li>
            <?php
            endif; ?>
        </ul>
    </div>
<?php
endif; ?>
