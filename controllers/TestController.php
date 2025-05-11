<?php

namespace app\controllers;

use app\models\Cities;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Taskforce\Service\Api\Geocoder;

class TestController extends Controller
{
    public function actionIndex()
    {
        $rootPath = dirname(__DIR__, 1);
        require_once $rootPath . '/src/Tests/test.php';
    }
}