<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\City;
use Yii;
use yii\web\Controller;
use yii\web\Response;

final class GeocoderController extends Controller
{
    /**
     * @param string $query
     *
     * @return array
     */
    public function actionAutocomplete($query): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return City::find()
            ->select(['id', 'name', 'latitude', 'longitude'])
            ->where(['like', 'name', $query])
            ->limit(10)
            ->asArray()
            ->all();
    }
}
