<?php

namespace app\controllers;

use app\models\LoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

class SiteController extends SecuredController
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function() {
                    return Yii::$app->controller->redirect(['/tasks']);
                },
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?'],
                    ]
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @return Response|string
     */
    public function actionIndex(): Response|string
    {
        $this->layout = 'landing';

        return $this->render('index', ['model' => new LoginForm()]);
    }
}
