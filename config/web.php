<?php

use app\interfaces\FilesUploadInterface;
use app\logic\Actions\CreateTaskAction;
use app\services\FileUploader;
use yii\symfonymailer\Mailer;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'locale' => 'ru-RU',
            'dateFormat' => 'php: d.m.Y',
            'timeFormat' => 'php: H:i:s',
            'datetimeFormat' => 'php: d.m.Y H:i:s',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'v0lZHRxhb3helZ2OZp8at4wgBydAQntQ',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['site/index'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info', 'trace', 'profile'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'tasks' => 'tasks/index',
                'publish' => 'task-creation/create',
                'tasks/view/<id:\d+>' => 'tasks/view',
                'users/view/<id:\d+>' => 'users/view',
                'signup' => 'signup/index',
                'login' => 'auth/login',
                'logout' => 'auth/logout',
                'tasks/city-list' => 'tasks/city-list',
                'my-tasks' => 'my-tasks/index',
                'my-tasks/<status>' => 'my-tasks/index',
                'account/settings' => 'account-settings/settings',
            ],
        ],
    ],
    'container' => [
        'singletons' => [
            FilesUploadInterface::class => ['class' => FileUploader::class],
            CreateTaskAction::class => CreateTaskAction::class,
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
