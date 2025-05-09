<?php

namespace app\controllers;

use app\models\RegistrationForm;
use Yii;
use app\models\Cities;
use yii\filters\AccessControl;
use yii\web\Controller;

class RegistrationController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['?']
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $model = new RegistrationForm();

        $cities = Cities::getAllCityNames();

//        Убедимся, что форма была отправлена
        if (Yii::$app->request->getIsPost()) {

//            Загрузим в модель все данные из POST
            $model->load(Yii::$app->request->post());

            if ($model->register()) {
                $this->goHome();
            }
        }

        return $this->render('index', compact('model', 'cities'));
    }
}