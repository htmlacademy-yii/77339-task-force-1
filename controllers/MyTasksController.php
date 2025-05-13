<?php

namespace app\controllers;

use app\controllers\SecuredController;
use app\logic\AvailableActions;
use app\models\Task;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

final class MyTasksController extends SecuredController
{
    /**
     * @return string
     */
    public function actionIndex() : string
    {
        $user = Yii::$app->user->identity;
        $status = Yii::$app->request->get('status', 'new');

        if ($user->isRoleCustomer()) {
            return $this->renderCustomerTasks($status);
        } elseif ($user->isRoleExecutor()) {
            return $this->renderExecutorTasks($status);
        }

        throw new NotFoundHttpException('Страница не найдена.');
    }

    /**
     * @param string $statusFilter
     *
     * @return string
     */
    private function renderCustomerTasks(string $statusFilter) : string
    {
        $query = Task::find()->where(['customer_id' => Yii::$app->user->id]);

        switch ($statusFilter) {
            case 'new':
                $query->andWhere(['status' => AvailableActions::STATUS_NEW]);
                $title = 'Новые задания';
                break;
            case 'in_progress':
                $query->andWhere(['status' => AvailableActions::STATUS_IN_PROGRESS]);
                $title = 'В процессе';
                break;
            case 'closed':
                $query->andWhere(
                    [
                        'in',
                        'status',
                        [
                            AvailableActions::STATUS_COMPLETED,
                            AvailableActions::STATUS_CANCELLED,
                            AvailableActions::STATUS_FAILED
                        ]
                    ]
                );
                $title = 'Закрытые';
                break;
            default:
                $query->andWhere(['status' => AvailableActions::STATUS_NEW]);
                $title = 'Новые задания';
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'title' => $title,
            'statusFilter' => $statusFilter,
            'isCustomer' => true,
        ]);
    }

    /**
     * @param string $statusFilter
     *
     * @return string
     */
    private function renderExecutorTasks(string $statusFilter) : string
    {
        $query = Task::find()->innerJoinWith('responses')->where(['responses.executor_id' => Yii::$app->user->id]);

        switch ($statusFilter) {
            case 'in_progress':
                $query->andWhere(['status' => AvailableActions::STATUS_IN_PROGRESS]);
                $title = 'В процессе';
                break;
            case 'expired':
                $query->andWhere(['status' => AvailableActions::STATUS_IN_PROGRESS])->andWhere(
                    ['<', 'ended_at', date('Y-m-d H:i:s')]
                );
                $title = 'Просроченные задания';
                break;

            case 'closed':
                $query->andWhere([
                    'in',
                    'status',
                    [
                        AvailableActions::STATUS_COMPLETED,
                        AvailableActions::STATUS_FAILED
                    ]
                ]);
                $title = 'Закрытые';
                break;

            default:
                $query->andWhere(['status' => AvailableActions::STATUS_IN_PROGRESS]);
                $title = 'В процессе';
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'title' => $title,
            'statusFilter' => $statusFilter,
            'isCustomer' => false,
        ]);
    }
}
