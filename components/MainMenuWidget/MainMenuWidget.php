<?php

namespace app\components\MainMenuWidget;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class MainMenuWidget extends Widget
{
    /**
     * @var array[]
     */
    public array $items = [];

    /**
     * @return string
     */
    public function run() : string
    {
        $output = '<ul class="nav-list">';

        $currentRoute = Yii::$app->controller->route;

        foreach ($this->items as $item) {
            $label = $item['label'];
            $url = Url::to($item['url']);
            $route = $item['route'];

            $activeClass = ($currentRoute === $route) ? 'list-item--active' : '';

            $output .= Html::tag(
                'li',
                Html::a(Html::encode($label), $url, ['class' => 'link link--nav']),
                ['class' => "list-item $activeClass"]
            );
        }

        $output .= '</ul>';

        return $output;
    }
}
