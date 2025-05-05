<?php
namespace app\controllers;

use app\interfaces\FilesUploadInterface;
use app\models\Category;
use app\models\City;
use app\models\Task;
use Yii;
use yii\db\Exception;
use yii\db\Transaction;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;

final class TaskCreationController extends SecuredController
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

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function () {
                    throw new ForbiddenHttpException('Только заказчики могут создавать задания');
                },
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return Yii::$app->user->identity->isRoleCustomer();
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string|yii\web\Response
     * @throws Exception
     */
    public function actionCreate(): string|yii\web\Response
    {
        $model = new Task();
        $model->scenario = Task::SCENARIO_CREATE;
        $model->setFileUploader($this->fileUploader);
        
        $categories = Category::find()->all();
        $cities = City::find()->all();

        if (Yii::$app->request->isPost) {
            return $this->handleTaskCreation($model);
        }

        return $this->renderCreateForm($model, $categories, $cities);
    }

    /**
     * @param Task $model
     * @return string|yii\web\Response
     * @throws Exception
     */
    private function handleTaskCreation(Task $model): string|yii\web\Response
    {
        $model->load(Yii::$app->request->post());
        $model->customer_id = Yii::$app->user->id;
        $model->status = Task::STATUS_NEW;
        $model->files = UploadedFile::getInstances($model, 'files');

        if (!$model->validate()) {
            return $this->renderCreateForm($model, Category::find()->all(), City::find()->all());
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$model->save()) {
                throw new Exception('Failed to save task');
            }

            if (!empty($model->files)) {
                $uploadedFiles = $model->processFiles($model->files);
                if (empty($uploadedFiles)) {
                    throw new Exception('Failed to process files');
                }
            }

            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Задание успешно создано!');
            return $this->redirect(['tasks/view', 'id' => $model->id]);
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Ошибка при создании задания: ' . $e->getMessage());
            return $this->renderCreateForm($model, Category::find()->all(), City::find()->all());
        }
    }

    /**
     * @param Task $model
     * @param array $categories
     * @param array $cities
     * @return string
     */
    protected function renderCreateForm(Task $model, array $categories, array $cities): string
    {
        return $this->render('@app/views/tasks/create/create', [
            'model' => $model,
            'categories' => $categories,
            'cities' => $cities,
        ]);
    }
}
