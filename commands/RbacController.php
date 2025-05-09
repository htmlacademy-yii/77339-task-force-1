<?php

namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll(); //удаляем старые данные

        // добавляем разрешение "createTask"
        $createTask = $auth->createPermission('createTask');
        $createTask->description = 'Создание задачи';
        $auth->add($createTask);

        // добавляем роль "client" и даём роли разрешение "createTask"
        $client = $auth->createRole('client');
        $client->description = 'Заказчик';
        $auth->add($client);
        $auth->addChild($client, $createTask);


        // добавляем роль "worker"
        $worker = $auth->createRole('worker');
        $worker->description = 'Исполнитель';
        $auth->add($worker);


        // добавляем роль "admin" и даём роли все разрешения роли "client" и "worker"
        $admin = $auth->createRole('admin');
        $admin->description = 'Администратор';
        $auth->add($admin);
        $auth->addChild($admin, $client);
        $auth->addChild($admin, $worker);
    }
}