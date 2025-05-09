<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Categories;

class TestController extends Controller
{
    public function actionIndex()
    {
        $rootPath = dirname(__DIR__, 1);
        require_once $rootPath . '/src/Tests/test.php';
    }
}