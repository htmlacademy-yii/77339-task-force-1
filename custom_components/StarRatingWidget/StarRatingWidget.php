<?php
/**
 * @author Романова Наталья <Natalochka_ne@mail.ru>
 * @copyright 2025 Романова Наталья | GitHub: Natalika-frontend
 * @licence html academy Use Only
 * @version 1.0
 * @warning Несанкционированное копирование запрещено!
 */

namespace app\custom_components\StarRatingWidget;

use yii\base\Widget;

class StarRatingWidget extends Widget
{
    // [!] АВТОРСКИЙ КОД [!]
    // Student: Романова Наталья
    // Course: Профессия "PHP-разработчик#1"
    // Task: модуль 2, задание module6-task2

    public float $rating; // значение рейтинга
    public int $maxRating = 5; // максимальный рейтинг
    public string $wrapperClass = 'stars-rating small'; // css-класс обертки
    public string $filledClass = 'fill-star'; // css-класс закрашенной звезды


    public function init(): void
    {
        parent::init();
        $this->rating = min(max($this->rating, 0), $this->maxRating); // ограничение рейтинга в пределах от 0 до $maxRating
    }
    public function run()
    {
        $fullStars = floor($this->rating); // кол-во закрашенных звезд
        $partialStar = $this->rating - $fullStars; // звезда которая должна быть закрашена частично
        $emptyStars = $this->maxRating - $fullStars - ($partialStar > 0 ? 1 : 0); // кол-во незакрашенных звезд
        return $this->render('star-rating', [
            'fullStars' => $fullStars,
            'partialStar' => $partialStar,
            'emptyStars' => $emptyStars,
            'wrapperClass' => $this->wrapperClass,
            'filledClass' => $this->filledClass,
        ]);
    }
}