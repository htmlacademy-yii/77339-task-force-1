<?php

namespace app\controllers;

use app\logic\Actions\ActionCancel;
use app\models\Task;
use Throwable;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

final class TaskCancelController extends Controller
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function () {
                    throw new ForbiddenHttpException('У вас нет прав для выполнения этого действия');
                },
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['cancel'],
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return Yii::$app->user->identity->role === 'customer';
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionCancel($id): Response
    {
        $task = Task::findOne($id);

        if (!$task) {
            throw new NotFoundHttpException('Задание не найдено.');
        }

        if (Yii::$app->user->id !== $task->customer_id) {
            throw new ForbiddenHttpException('У вас нет прав для отмены этого задания.');
        }

        $action = new ActionCancel();

        try {
            if (!$action->isAvailable(Yii::$app->user->id, $task->customer_id, $task->executor_id)) {
                throw new ForbiddenHttpException('Действие недоступно');
            }

            if ($action->execute($task)) {
                Yii::$app->session->setFlash('success', 'Задание успешно отменено');
            }
        } catch (Throwable $e) {
            Yii::error($e->getMessage());
            Yii::$app->session->setFlash('error', 'Ошибка при отмене задания.');
        }

        return $this->redirect(['tasks/view', 'id' => $id]);
    }
}
