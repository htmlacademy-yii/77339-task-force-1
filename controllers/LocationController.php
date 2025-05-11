<?php

namespace app\controllers;

use Taskforce\Service\Api\Geocoder;
use Yii;
use yii\web\Controller;

class LocationController extends Controller
{
    public function actionGet()
    {
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request;
            $query = $request->get('query');

            $api = new Geocoder();
            $response = $api->getCoordinates($query);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            return $response;
        }
    }
}