<?php

namespace app\logic\Actions;

use app\models\Response;
use Yii;
use yii\db\Exception;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

final class AcceptResponseAction
{
    /**
     * @throws Exception
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function run(int $id) : int
    {
        $response = Response::findOne($id);
        if (!$response) {
            throw new NotFoundHttpException("Отклик не найден");
        }

        $task = $response->task;
        $action = new ActionAssign();

        if (!$action->isAvailable(Yii::$app->user->id, $task->customer_id, $task->executor_id)) {
            throw new ForbiddenHttpException("Действие недоступно");
        }

        if ($action->execute($task, $response)) {
            Yii::$app->session->setFlash('success', 'Отклик принят');
        } else {
            Yii::$app->session->setFlash('error', 'Не удалось принять отклик');
        }

        return $task->id;
    }
}
