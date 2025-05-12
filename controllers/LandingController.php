<?php

namespace app\controllers;

use app\models\LandingTasksSearch;
use yii\filters\AccessControl;

class LandingController extends BaseAuthController
{
    public $layout = 'landing';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?']
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $model = new LandingTasksSearch();
        $dataProvider = $model->search();

        return $this->render('index', compact('dataProvider'));
    }
}