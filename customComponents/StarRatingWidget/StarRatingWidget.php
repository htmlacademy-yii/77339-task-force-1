<?php
/**
 * @author Романова Наталья <Natalochka_ne@mail.ru>
 * @copyright 2025 Романова Наталья | GitHub: Natalika-frontend
 * @licence html academy Use Only
 * @version 1.0
 * @warning Несанкционированное копирование запрещено!
 */

namespace app\customComponents\StarRatingWidget;

use yii\base\Widget;

class StarRatingWidget extends Widget
{
    public float $rating; // значение рейтинга
    public const int MAX_RATING = 5; // максимальный рейтинг
    public const string DEFAULT_WRAPPER_CLASS = 'stars-rating small'; // css-класс обертки
    public const string DEFAULT_FILLED_CLASS = 'fill-star'; // css-класс закрашенной звезды

    public string $wrapperClass = self::DEFAULT_WRAPPER_CLASS;
    public string $filledClass = self::DEFAULT_FILLED_CLASS;

    public function init(): void
    {
        parent::init();
        // ограничение рейтинга в пределах от 0 до MAX_RATING:
        $this->rating = min(max($this->rating, 0), self::MAX_RATING);
    }

    public function run()
    {
        $fullStars = floor($this->rating); // кол-во закрашенных звезд
        $partialStar = $this->rating - $fullStars; // звезда которая должна быть закрашена частично
        $emptyStars = self::MAX_RATING - $fullStars - ($partialStar > 0 ? 1 : 0); // кол-во незакрашенных звезд
        return $this->render('star-rating', [
            'fullStars' => $fullStars,
            'partialStar' => $partialStar,
            'emptyStars' => $emptyStars,
            'wrapperClass' => $this->wrapperClass,
            'filledClass' => $this->filledClass,
        ]);
    }
}
