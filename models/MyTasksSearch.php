<?php

namespace app\models;

use yii\data\ActiveDataProvider;
use Taskforce\Service\Task\TaskStatuses;
use yii\helpers\VarDumper;

class MyTasksSearch extends Tasks
{
    const PAGE_SIZE = 5;

    public function search($userRole, $status)
    {
        $currentUserId = \Yii::$app->user->identity->id;

        switch ($userRole) {
            case 'worker':
                $query = Tasks::find()
                    ->where(['worker_id' => $currentUserId]);
                switch ($status) {
                    case 'active':
                        $query->andWhere(['current_status' => TaskStatuses::STATUS_ACTIVE]);
                        break;
                    case 'expired':
                        $query
                            ->andWhere(['current_status' => TaskStatuses::STATUS_ACTIVE])
                            ->andWhere(['<', 'expired_at', (new \DateTime())->format('Y-m-d')]);
                        break;
                    case 'closed':
                        $query->andWhere(['in', 'current_status', [TaskStatuses::STATUS_DONE, TaskStatuses::STATUS_FAIL]]);
                        break;
                }
            break;
            case 'client':
                $query = Tasks::find()
                    ->where(['client_id' => $currentUserId]);
                switch ($status) {
                    case 'new':
                        $query->andWhere(['current_status' => TaskStatuses::STATUS_NEW]);
                        break;
                    case 'active':
                        $query->andWhere(['current_status' => TaskStatuses::STATUS_ACTIVE]);
                        break;
                    case 'closed':
                        $query->andWhere([
                            'in',
                            'current_status',
                            [TaskStatuses::STATUS_UNDO, TaskStatuses::STATUS_DONE, TaskStatuses::STATUS_FAIL]
                        ]);
                        break;
                }
                break;
        }

        $query->orderBy('id DESC');

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => self::PAGE_SIZE
            ]
        ]);
    }
}