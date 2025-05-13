<?php

namespace app\controllers;

use app\logic\Actions\ActionExecute;
use app\models\Review;
use app\models\Task;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

final class TaskCompletionController extends Controller
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
                        'actions' => ['complete'],
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
    public function actionComplete(int $id): string|Response
    {
        $task = Task::findOne($id);
        if (!$task) {
            throw new NotFoundHttpException("Задание не найдено");
        }

        $model = new Review();
        $action = new ActionExecute();

        if (!$action->isAvailable(Yii::$app->user->id, $task->customer_id, $task->executor_id)) {
            throw new ForbiddenHttpException("Действие недоступно");
        }


        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                try {
                    $action->execute($task, $model);
                    if ($action->execute($task, $model)) {
                        Yii::$app->session->setFlash('success', 'Задание успешно завершено!');
                        return $this->redirect(['tasks/view', 'id' => $task->id]);
                    }
                } catch (\Exception $e) {
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            } else {
                $errors = implode('; ', array_map(fn($item) => implode(', ', $item), $model->getErrors()));
                Yii::$app->session->setFlash('error', 'Ошибка валидации данных: ' . $errors);
            }
        }

        return $this->render('//modals/_act_complete', [
            'model' => $model,
            'task' => $task
        ]);
    }
}
