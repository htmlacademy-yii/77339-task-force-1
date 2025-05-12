<?php

namespace app\controllers;

use app\models\MyTasksSearch;
use yii\helpers\VarDumper;
use Yii;
use yii\web\NotFoundHttpException;

class MyTaskController extends BaseAuthController
{
    const AVAILABLE_CLIENT_STATUSES = ['new', 'active', 'closed'];
    const AVAILABLE_WORKER_STATUSES = ['active', 'closed', 'expired'];

    public function actionIndex($status = null)
    {
        $userRole = Yii::$app->user->can('client') ? 'client' : 'worker';

        if (empty($status)) {
            return match ($userRole) {
                'client' => $this->redirect(['my-task/' . self::AVAILABLE_CLIENT_STATUSES[0]]),
                'worker' =>  $this->redirect(['my-task/' . self::AVAILABLE_WORKER_STATUSES[0]])
            };
        }

        if (
            (
                $userRole === 'client'
                && !in_array($status, self::AVAILABLE_CLIENT_STATUSES)
            )
            ||
            (
                $userRole === 'worker'
                && !in_array($status, self::AVAILABLE_WORKER_STATUSES)
            )
        ) {
            throw new NotFoundHttpException("Статус $status не предусмотрен в системе");
        }

        $model = new MyTasksSearch();
        $dataProvider = $model->search($userRole, $status);

        return $this->render('index', compact('dataProvider'));
    }
}