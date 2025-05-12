<?php

namespace app\components;

class ConditionalRouting implements \yii\base\BootstrapInterface
{
    /**
     * @var array
     */
    public $guestRules = [
        '' => 'landing/index',
    ];

    /**
     * @var array
     */
    public $userRules = [
        '' => 'tasks/index'
    ];

    /**
     * @inheritDoc
     */
    public function bootstrap($app)
    {
        $manager = \Yii::$app->urlManager;
        $manager->addRules(\Yii::$app->user->isGuest ? $this->guestRules : $this->userRules, false);
    }
}