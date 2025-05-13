<?php

namespace app\components\StarRatingWidget;

use yii\base\Widget;

class StarRatingWidget extends Widget
{
    public float $rating;
    public const int MAX_RATING = 5;
    public const string DEFAULT_WRAPPER_CLASS = 'stars-rating small';
    public const string DEFAULT_FILLED_CLASS = 'fill-star'; 

    public string $wrapperClass = self::DEFAULT_WRAPPER_CLASS;
    public string $filledClass = self::DEFAULT_FILLED_CLASS;

    public function init(): void
    {
        parent::init();
        $this->rating = min(max($this->rating, 0), self::MAX_RATING);
    }

    public function run()
    {
        $fullStars = floor($this->rating);
        $partialStar = $this->rating - $fullStars;
        $emptyStars = self::MAX_RATING - $fullStars - ($partialStar > 0 ? 1 : 0);
        return $this->render('star-rating', [
            'fullStars' => $fullStars,
            'partialStar' => $partialStar,
            'emptyStars' => $emptyStars,
            'wrapperClass' => $this->wrapperClass,
            'filledClass' => $this->filledClass,
        ]);
    }
}
