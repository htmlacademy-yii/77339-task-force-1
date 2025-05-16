<?php

namespace app\controllers;

use app\interfaces\FilesUploadInterface;
use app\logic\AvailableActions;
use app\models\Response;
use app\models\Task;
use Yii;
use yii\data\ActiveDataProvider;
use app\models\Category;
use yii\web\NotFoundHttpException;

class TasksController extends SecuredController
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

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $task = new Task();
        if (Yii::$app->request->isGet) {
            $task->load(Yii::$app->request->get());
        }

        $task->setFileUploader($this->fileUploader);

        $categories = Category::find()->all();

        $dataProvider = $task->getDataProvider();

        return $this->render('index/index', [
            'categories' => $categories,
            'dataProvider' => $dataProvider,
            'task' => $task,
        ]);
    }

    /**
     * @param int
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        $task = Task::findOne($id);

        if (!$task) {
            throw new NotFoundHttpException('Задание не найдено.');
        }

        $availableActions = new AvailableActions(
            $task->customer_id, $task->status, $task->executor_id
        );

        $responsesQuery = Response::find()->where(['task_id' => $id])->with(['executor.executorReviews']);

        if (Yii::$app->user->id !== $task->customer_id) {
            $responsesQuery->andWhere(['executor_id' => Yii::$app->user->id]);
        }

        $responsesDataProvider = new ActiveDataProvider([
            'query' => $responsesQuery,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ]
        ]);

        return $this->render('view-task/view', [
            'task' => $task,
            'responsesDataProvider' => $responsesDataProvider,
            'availableActions' => $availableActions,
            'currentUserId' => Yii::$app->user->id,
            'taskId' => $id,
        ]);
    }
}
