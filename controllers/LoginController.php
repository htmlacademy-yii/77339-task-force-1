<?php

namespace app\controllers;

use app\models\Users;
use Yii;
use yii\web\Controller;
use app\models\LoginForm;
use yii\widgets\ActiveForm;
use yii\web\Response;
use yii\filters\AccessControl;

class LoginController extends Controller
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

    public $layout = 'landing';

    public function actionIndex()
    {
        $model = new LoginForm();

        if (\Yii::$app->request->getIsPost()) {
            $model->load(\Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($model);
            }

            if ($model->validate()) {
                \Yii::$app->user->login($model->getUser());

                return $this->goHome();
            }
        }

        return $this->render('index', compact('model'));
    }
}