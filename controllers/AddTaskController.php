<?php

namespace app\controllers;

use app\models\Categories;
use app\models\Tasks;
use app\models\Users;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use app\models\AddTaskForm;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use Taskforce\Service\Api\Geocoder;

class AddTaskController extends BaseAuthController
{
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (!\Yii::$app->user->can('client')) {
                throw new \yii\web\ForbiddenHttpException('Доступ закрыт.');
            }
            return true;
        } else {
            return false;
        }
    }

    public function actionIndex()
    {
        $model = new AddTaskForm();

        $categories = Categories::getAllCategoriesNames();

        //        Убедимся, что форма была отправлена
        if (Yii::$app->request->getIsPost()) {

        //            Загрузим в модель все данные из POST
            $model->load(Yii::$app->request->post());
            $model->files = UploadedFile::getInstances($model, 'files');

            $taskId = $model->addTask();
            if (!empty($taskId)) {
                return Yii::$app->response->redirect(['tasks/view', 'id' => $taskId]);
            }
        }

        return $this->render('index', compact('model', 'categories'));
    }
}