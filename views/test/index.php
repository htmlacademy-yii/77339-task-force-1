<?php

/** @var yii\web\View $this */

$this->title = 'Taskforce | Test Controller';
?>
<div class="main-container">
    <h1 class="display-6">Test Controller</h1>
    <h2>Категории</h2>
    <ul>
    <?php foreach ($categories as $category): ?>
        <li><b><?= $category->icon; ?></b> <?= $category->name; ?></li>
    <?php endforeach; ?>
    </ul>
</div>
