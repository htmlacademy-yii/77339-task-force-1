<?php
/**
 * @var string $wrapperClass - класс контейнера (например "stars-rating small")
 * @var int $fullStars - количество полностью заполненных звезд
 * @var float $partialStar - значение частичного заполнения (от 0.0 до 1.0)
 * @var int $emptyStars - количество пустых звезд
 */

use yii\helpers\Html;

$isBig = str_contains($wrapperClass, 'big');

// Параметры для разных размеров
$params = $isBig ? [
    'size' => 25,
    'padding' => 0,
    'marginRight' => 5,
    'strokeWidth' => 0.8
] : [
    'size' => 18,
    'padding' => '5px 0',
    'marginRight' => 2,
    'marginTop' => 5,
    'strokeWidth' => 0.8
];

$starPath = 'M13.626,15.367L12.742,10.216L16.485,6.568L11.313,5.816L9,1.13L6.687,5.816L1.515,6.568L5.257,10.216L4.374,15.367L9,12.935L13.626,15.367Z';
?>

<div class="<?= Html::encode($wrapperClass) ?>">
    <?php
    // Полностью заполненные звезды
    for ($i = 0; $i < $fullStars; $i++) {
        echo '<span style="display:inline-block;padding:' . $params['padding'] . 'px;margin-right:' . $params['marginRight'] . 'px;' . (isset($params['marginTop']) ? 'margin-top:' . $params['marginTop'] . 'px' : '') . '">
            <svg width="' . $params['size'] . '" height="' . $params['size'] . '" viewBox="0 0 18 17">
                <path d="' . $starPath . '" fill="#F5C644" stroke="#F5C644" stroke-width="' . $params['strokeWidth'] . '"/>
            </svg>
        </span>';
    }

    // Частично заполненная звезда
    if ($partialStar > 0) {
        $fillWidth = round($partialStar * 100, 2);
        echo '<span style="display:inline-block;padding:' . $params['padding'] . 'px;margin-right:' . $params['marginRight'] . 'px;' . (isset($params['marginTop']) ? 'margin-top:' . $params['marginTop'] . 'px' : '') . '">
            <svg width="' . $params['size'] . '" height="' . $params['size'] . '" viewBox="0 0 18 17">
                <defs>
                    <linearGradient id="partialFill" x1="0" x2="100%" y1="0" y2="0">
                        <stop offset="' . $fillWidth . '%" stop-color="#F5C644"/>
                        <stop offset="' . $fillWidth . '%" stop-color="transparent"/>
                    </linearGradient>
                </defs>
                <path d="' . $starPath . '" fill="url(#partialFill)" stroke="#F5C644" stroke-width="' . $params['strokeWidth'] . '"/>
            </svg>
        </span>';
    }

    // Пустые звезды
    for ($i = 0; $i < $emptyStars; $i++) {
        echo '<span style="display:inline-block;padding:' . $params['padding'] . 'px;margin-right:' . $params['marginRight'] . 'px;' . (isset($params['marginTop']) ? 'margin-top:' . $params['marginTop'] . 'px' : '') . '">
            <svg width="' . $params['size'] . '" height="' . $params['size'] . '" viewBox="0 0 18 17">
                <path d="' . $starPath . '" fill="none" stroke="#F5C644" stroke-width="' . $params['strokeWidth'] . '"/>
            </svg>
        </span>';
    }
    ?>
</div>