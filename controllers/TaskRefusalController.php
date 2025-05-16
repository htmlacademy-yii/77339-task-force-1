<?php

namespace app\controllers;

use app\logic\AvailableActions;
use app\models\Task;
use Yii;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

final class TaskRefusalController extends Controller
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
                        'actions' => ['refusal'],
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return Yii::$app->user->identity->role === 'executor';
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function actionRefusal($id): Response
    {
        $task = Task::findOne($id);

        if (!$task) {
            throw new NotFoundHttpException('Задание не найдено.');
        }

        $task->status = AvailableActions::STATUS_FAILED;
        $task->save();

        return $this->redirect(['/tasks/view', 'id' => $task->id]);
    }
}
