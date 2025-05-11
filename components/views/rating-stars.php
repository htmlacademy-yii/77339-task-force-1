<?php
use Taskforce\Service\Enum\RatingValues;

/** @var integer $ratingValue */
/** @var string $ratingClass */
?>

<div class="stars-rating <?= $ratingClass; ?>">
    <?php for ($counter = RatingValues::Min->value; $counter <= RatingValues::Max->value; $counter++): ?>
        <span
                data-number="<?= $counter; ?>"
                class="stars-rating__star
                <?= $counter <= $ratingValue ? 'stars-rating__star--fill' : '' ?>"
        >&nbsp;</span>
    <?php endfor; ?>
    <input type="hidden" name="rating" class="stars-rating__value">
</div>