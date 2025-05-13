<?php

namespace app\handlers;

use app\models\User;
use yii\base\Behavior;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;

class UserAfterSaveHandler extends Behavior
{
    /**
     * @return array
     */
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_AFTER_INSERT => 'handleAfterSave',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'handleAfterSave',
        ];
    }

    /**
     * @param AfterSaveEvent $event
     *
     * @return void
     */
    public function handleAfterSave(AfterSaveEvent $event): void
    {
        $user = $event->sender;

        if ($user->role === User::ROLE_EXECUTOR) {
            $user->updateExecutorStars();
        }
    }
}
