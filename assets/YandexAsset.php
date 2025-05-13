<?php

namespace app\assets;

use yii\web\AssetBundle;

final class YandexAsset extends AssetBundle
{
    public $sourcePath = null;

    public function init()
    {
        parent::init();

        $apiKey = getenv('YANDEX_API_KEY');
        $this->js[] = "https://api.yandex.ru/2.1/?apikey=$apiKey&lang=ru_RU";
    }
}
