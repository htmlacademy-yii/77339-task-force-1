<?php

namespace app\controllers;

use app\models\Task;
use Yii;
use yii\base\ExitException;
use yii\web\Controller;
use app\models\Category;

class TasksController extends Controller
{
    /**
     * @throws ExitException
     */
    public function actionIndex(): string
    {
        $task = new Task();
        $task->load(Yii::$app->request->post());

        $categories = Category::find()->all();

        $dataProvider = $task->getDataProvider();

        return $this->render('index', [
            'categories' => $categories,
            'dataProvider' => $dataProvider,
            'task' => $task,
        ]);
    }
}