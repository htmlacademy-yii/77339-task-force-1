<?php

namespace app\models;

use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class TaskSearch extends Task
{
    public array $categoryIds = [];
    public string $noResponses = '0';
    public string $noLocation = '0';
    public $filterPeriod;

    public function rules(): array
    {
        return [
            [['categoryIds', 'noResponses', 'noLocation', 'filterPeriod'], 'safe'],
        ];
    }

    public function search(): ActiveDataProvider
    {
        $query = Task::find()->where(['status' => TaskStatus::STATUS_NEW]);

        if (!empty($this->categoryIds)) {
            $categoryIds = is_array($this->categoryIds)
                ? $this->categoryIds
                : array_filter(explode(',', $this->categoryIds));

            if (!empty($categoryIds)) {
                $query->andWhere(['category_id' => $categoryIds]);
            }
        }

        if ($this->noResponses) {
            $query->leftJoin('responses', 'responses.task_id = tasks.id')
                ->andWhere(['responses.id' => null]);
        }

        if ($this->noLocation) {
            $query->andWhere(['city_id' => null]);
        }

        if ($this->filterPeriod) {
            $query->andWhere([
                '>=',
                'created_at',
                date('Y-m-d H:i:s', time() - (int)$this->filterPeriod)
            ]);
        }

        return new ActiveDataProvider([
            'query' => $query->orderBy(['created_at' => SORT_DESC]),
            'pagination' => ['pageSize' => 5],
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]]
        ]);
    }
} 