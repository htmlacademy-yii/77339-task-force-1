<?php

namespace app\controllers;

use app\models\Categories;
use app\models\Reactions;
use app\models\Tasks;

use Taskforce\Service\Enum\ReactionStatuses;
use Taskforce\Service\Task\TaskService;
use Taskforce\Service\Task\TaskActions;
use Taskforce\Service\Task\TaskStatuses;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use app\models\TasksSearch;
use Yii;
use yii\web\NotFoundHttpException;

class TasksController extends BaseAuthController
{
    public function actionIndex($id = null)
    {
        $categories = Categories::find()->asArray()->all();
        $categories = ArrayHelper::map($categories, 'id', 'name');

        $searchModel = new TasksSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->post(), $id);

        $tasks = $dataProvider->getModels();

        return $this->render('index', compact('searchModel','tasks', 'categories'));
    }

    public function actionView($id, $isAjax = false)
    {
        $task = Tasks::getTaskByPrimary((int)$id);

        $taskMap = $this->getTaskMapCoordinates($task);

        $files = [];
        if (!empty($task->taskFiles)) {
            foreach ($task->taskFiles as $key => $file) {
                $name = $file->file->file_path;
                $link = '/web/uploads/' . $name;
                $files[$key] = [
                    'name' => $name,
                    'link' => $link,
                    'size' => filesize(Yii::getAlias('@webroot') . '/uploads/' . $name)
                ];
            }
        }

        $currentUser = \Yii::$app->user->identity->id;

        $taskService = new TaskService((int)$id);
        $taskActions = $taskService->getAvailableActions();

        $actionColors = [
            TaskActions::ACTION_REACT => 'button--blue',
            TaskActions::ACTION_REJECT => 'button--orange',
            TaskActions::ACTION_FINISH => 'button--pink',
            TaskActions::ACTION_CANCEL => 'button--yellow'
        ];

        $actionsToDisplay = [];
        foreach ($taskActions as $key => $action) {
            if ($action['code'] === TaskActions::ACTION_START) {
                // Действие Принять отклик реализуется для каждого отклика и не участвует в верхнем блоке кнопок
                continue;
            }
            $action['color'] = $actionColors[$action['code']];
//            $action['code'] = $this->camelCaseToSnakeCase($action['code']);
            $actionsToDisplay[] = $action;
        }

        $reactions = [];
        foreach ($task->reactions as $key => $reaction) {
            $feedbacksCount = $reaction->worker->getWorkerFeedbacks()->count();

            $reactions[$key] = [
                'id' => $reaction->id,
                'user_id' => $reaction->worker->id,
                'img' => $reaction->worker->avatar->file_path,
                'name' => $reaction->worker->full_name,
                'rating' => floor($reaction->worker->workerRating),
                'feedbacks_count' => Yii::$app->i18n->format(
                    '{n, plural, =0{нет отзывов} =1{один отзыв} one{# отзыв} few{# отзыва} many{# отзывов} other{# отзывов}}',
                    ['n' => $feedbacksCount],
                    'ru_RU'
                ),
                'comment' => $reaction->comment,
                'price' => $reaction->worker_price,
                'published' => $reaction->getPublishedTimePassed(),
                'display' => $reaction->worker->id === $currentUser
                    || $task->client_id === $currentUser,
                'showButtons' => $task->client_id === $currentUser
                    && $reaction->status !== ReactionStatuses::Reject->value
                    && $task->current_status === TaskStatuses::STATUS_NEW
            ];
        }

        $displayReactions = !empty(
            array_filter(
                $reactions,
                fn ($reaction) => $reaction['display'] === true
            )
        );

        if ($isAjax) {
            return $this->renderPartial('view', compact('task', 'reactions', 'files', 'displayReactions', 'actionsToDisplay', 'taskMap'));
        }

        return $this->render('view', compact('task', 'reactions', 'files', 'displayReactions', 'actionsToDisplay', 'taskMap'));
    }

    public function actionClientStart()
    {
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request;
            $params = json_decode($request->getRawBody());

            $taskId = $params->taskId;
            $reactionId = $params->reactionId;
            $workerId = $params->workerId;

            $reaction = new Reactions();
            $reaction->setAcceptReactionStatus((int)$reactionId);

            $task = new TaskService((int)$taskId);
            $task->startTaskAction((int)$workerId);

            $data = $this->actionView($taskId, true);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            return [
                'success' => true,
                'data' => $data,
            ];
        }
    }

    public function actionRejectReaction()
    {
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request;
            $params = json_decode($request->getRawBody());

            $taskId = $params->taskId;
            $reactionId = $params->reactionId;

            $reaction = new Reactions();
            $reaction->setRejectReactionStatus((int)$reactionId);

            $data = $this->actionView($taskId, true);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            return [
                'success' => true,
                'data' => $data,
            ];
        }
    }

    public function actionClientFinish()
    {
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request;
            $params = json_decode($request->getRawBody());

            $taskId = $params->taskId;

            $task = new TaskService((int)$taskId);
            $task->finishTaskAction($params);

            $data = $this->actionView($taskId, true);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            return [
                'success' => true,
                'data' => $data,
            ];
        }
    }

    public function actionClientCancel()
    {
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request;
            $params = json_decode($request->getRawBody());

            $taskId = $params->taskId;

            $task = new TaskService((int)$taskId);
            $task->cancelTaskAction();

            $data = $this->actionView($taskId, true);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            return [
                'success' => true,
                'data' => $data,
            ];
        }
    }

    public function actionWorkerReject()
    {
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request;
            $params = json_decode($request->getRawBody());

            $taskId = $params->taskId;

            $task = new TaskService((int)$taskId);
            $task->rejectTaskAction();

            $data = $this->actionView($taskId, true);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            return [
                'success' => true,
                'data' => $data,
            ];
        }
    }

    public function actionWorkerReact()
    {
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request;
            $params = json_decode($request->getRawBody());

            $taskId = $params->taskId;

            $reaction = new Reactions();
            $reaction->addWorkerReaction($params);

            $data = $this->actionView($taskId, true);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            return [
                'success' => true,
                'data' => $data,
            ];
        }
    }

    protected function camelCaseToSnakeCase($string): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }

    protected function getTaskMapCoordinates(Tasks $task): array
    {
        $latitude = '';
        $longitude = '';
        if (!empty($task->latitude) && !empty($task->longitude)) {
            $latitude = $task->latitude;
            $longitude = $task->longitude;
        } elseif (!empty($task->city_id)) {
            $latitude =  $task->city->latitude;
            $longitude =  $task->city->longitude;
        }

        return [
            'latitude' => $latitude,
            'longitude' => $longitude
        ];
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();

        return $this->goHome();
    }
}