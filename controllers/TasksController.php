<?php

namespace app\controllers;

use app\interfaces\FilesUploadInterface;
use app\models\Response;
use app\models\Task;
use app\models\TaskSearch;
use Yii;
use yii\data\ActiveDataProvider;
use app\models\Category;
use yii\web\NotFoundHttpException;
use yii\web\Controller;

class TasksController extends Controller
{
    private FilesUploadInterface $fileUploader;

    public function __construct(
        $id,
        $module,
        FilesUploadInterface $fileUploader,
        $config = []
    ) {
        $this->fileUploader = $fileUploader;
        parent::__construct($id, $module, $config);
    }

    public function actionIndex()
    {
        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * @param int $id ID задания
     * @return string
     * @throws NotFoundHttpException
     * @used-by \app\config\web::urlManager
     * @used-by \app\views\tasks\_task-list.php
     */
    public function actionView($id)
    {
        $task = $this->findModel($id);
        $responsesDataProvider = $this->getResponsesDataProvider($id);

        return $this->render('view-task/view', [
            'task' => $task,
            'responsesDataProvider' => $responsesDataProvider,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Задание не найдено.');
    }

    private function getResponsesDataProvider(int $taskId): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Response::find()
                ->where(['task_id' => $taskId])
                ->with('executor.executorReviews'),
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ]
        ]);
    }
}
