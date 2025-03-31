<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class DbTestController extends Controller
{
    public function actionIndex(): string
    {
        try {
            Yii::$app->db->open();
            return "Подключение к БД успешно!";
        } catch (\Exception $e) {
            return "Ошибка подключения: " . $e->getMessage();
        }
    }

}
