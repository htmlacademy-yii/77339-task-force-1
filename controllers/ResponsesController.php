<?php

namespace app\controllers;

use app\models\Response;
use Yii;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;


final class ResponsesController extends Controller
{

    /**
     * @return array
     */
    public function behaviors() : array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return Yii::$app->user->identity->role === 'executor';
                        },
                    ]
                ],
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function actionCreate() : \yii\web\Response
    {
        $model = new Response();
        $request = Yii::$app->request;

        if ($request->isPost) {
            $model->load($request->post());

            $taskId = $request->post('task_id');
            $userId = Yii::$app->user->id;

            $existingResponse = Response::find()->where(['task_id' => $taskId, 'executor_id' => $userId])->one();

            if ($existingResponse) {
                Yii::$app->session->setFlash('error', 'Вы уже откликались на это задание.');

                return $this->redirect(Yii::$app->request->referrer);
            }

            $model->task_id = $taskId;
            $model->executor_id = $userId;
            $model->created_at = date('Y-m-d H:i:s');

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Ваш отклик успешно добавлен!');

                return $this->redirect(['tasks/view', 'id' => $taskId]);
            } else {
                Yii::$app->session->setFlash('error', 'Ой, что-то пошло не так!');
            }

            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->redirect(['tasks/index']);
    }
}
