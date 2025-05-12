<?php

namespace app\models;

use yii\data\ActiveDataProvider;
use Taskforce\Service\Task\TaskStatuses;

class LandingTasksSearch extends Tasks
{
    public function search()
    {
        $query = Tasks::find()
            ->where(['current_status' => TaskStatuses::STATUS_NEW])
            ->limit(4)
            ->orderBy('id DESC');

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);
    }
}