<?php

declare(strict_types=1);

namespace app\controllers;

use app\logic\Actions\AcceptResponseAction;
use app\logic\Actions\RejectResponseAction;
use Throwable;
use Yii;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

final class ResponseController extends Controller
{
    public function behaviors() : array
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
                        'actions' => ['accept', 'reject'],
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
     * @throws Exception
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionAccept(int $id) : \yii\web\Response
    {
        $action = new AcceptResponseAction();
        $taskId = $action->run($id);

        return $this->redirect(['tasks/view', 'id' => $taskId]);
    }

    /**
     * @throws Throwable
     * @throws StaleObjectException
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionReject(int $id) : \yii\web\Response
    {
        $action = new RejectResponseAction();
        $taskId = $action->run($id);

        return $this->redirect(['tasks/view', 'id' => $taskId]);
    }
}
