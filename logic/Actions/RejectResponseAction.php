<?php

namespace app\logic\Actions;

use app\models\Response;
use Yii;
use yii\db\StaleObjectException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

final class RejectResponseAction
{
    /**
     * @throws \Throwable
     * @throws StaleObjectException
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function run(int $id) : int
    {
        $response = Response::findOne($id);
        if (!$response) {
            throw new NotFoundHttpException("Отклик не найден");
        }

        $task = $response->task;
        $action = new ActionReject();

        if (!$action->isAvailable(Yii::$app->user->id, $task->customer_id, $task->executor_id)) {
            throw new ForbiddenHttpException("Действие недоступно");
        }

        if ($action->execute($response)) {
            Yii::$app->session->setFlash('success', "Отклик отклонен");
        } else {
            Yii::$app->session->setFlash('error', 'Не удалось отклонить отклик');
        }

        return $task->id;
    }
}
