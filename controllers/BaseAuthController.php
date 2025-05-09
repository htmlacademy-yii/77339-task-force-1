<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use Yii;
use yii\helpers\Url;

class BaseAuthController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }
}