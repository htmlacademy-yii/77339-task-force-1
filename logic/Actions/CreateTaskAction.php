<?php

namespace app\logic\Actions;

use app\helpers\YandexMapHelper;
use app\interfaces\FilesUploadInterface;
use app\logic\AvailableActions;
use app\models\Category;
use app\models\City;
use app\models\Task;
use Yii;
use yii\base\Action;
use yii\db\Exception;
use yii\db\Expression;
use yii\web\Response;
use yii\web\UploadedFile;

final class CreateTaskAction extends Action
{
    public const string SCENARIO_CREATE = 'create';

    private FilesUploadInterface $fileUploader;

    public function __construct(string $id, $controller, FilesUploadInterface $fileUploader, array $config = [])
    {
        parent::__construct($id, $controller, $config);

        $this->fileUploader = $fileUploader;
    }

    /**
     * @throws Exception
     */
    public function run(): Response|string
    {
        $model = new Task();
        $model->scenario = self::SCENARIO_CREATE;
        $this->configureModelValidation($model);
        $model->setFileUploader($this->fileUploader);

        $categories = Category::find()->all();
        $cities = City::find()->all();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->customer_id = Yii::$app->user->id;
            $model->status = AvailableActions::STATUS_NEW;
            $model->files = UploadedFile::getInstances($model, 'files');

            if (!empty($model->location)) {
                $addressParts = array_map('trim', explode(',', $model->location));
                $cityName = $addressParts[0] ?? '';

                $mapHelper = new YandexMapHelper(Yii::$app->params['yandexApiKey']);
                $coordinates = $mapHelper->getCoordinates($model->location);

                if ($coordinates) {
                    $model->latitude = $coordinates['lat'];
                    $model->longitude = $coordinates['lng'];

                    $nearestCity = City::find()
                        ->orderBy(
                            new Expression(
                                "POWER(latitude - {$model->latitude}, 2) + 
                 POWER(longitude - {$model->longitude}, 2)"
                            )
                        )
                        ->one();

                    if ($nearestCity) {
                        $model->city_id = $nearestCity->id;
                    }
                }
            }

            if ($model->validate() && $model->save(false)) {
                $this->handleFileUpload($model);
                Yii::$app->session->setFlash('success', "Задание успешно создано!");
                return $this->controller->redirect(['tasks/view', 'id' => $model->id]);
            }
        }

        return $this->controller->render('@app/views/tasks/create/create', [
            'model' => $model,
            'categories' => $categories,
            'cities' => $cities,
        ]);
    }

    private function configureModelValidation(Task $model): void
    {
        $model->defineScenario(self::SCENARIO_CREATE, [
            'title',
            'description',
            'category_id',
            'budget',
            'city_id',
            'latitude',
            'longitude',
            'ended_at',
            'files'
        ]);

        $validators = $model->getValidators();

        $rules = [
            [
                ['title', 'description', 'category_id', 'customer_id'],
                'required',
                'message' => 'Поле обязательно для заполнения.'
            ],
            [['title'], 'string', 'min' => 10],
            [['description'], 'string', 'min' => 30],
            ['budget', 'integer', 'min' => 1],
            ['ended_at', 'date', 'format' => 'php:Y-m-d'],
            ['ended_at', 'validateDeadline'],
            [['files'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 10],
        ];

        foreach ($rules as $rule) {
            $validator = \yii\validators\Validator::createValidator(
                $rule[1],
                $model,
                (array)$rule[0],
                array_slice($rule, 2)
            );
            $validators->append($validator);
        }
    }

    private function handleFileUpload(Task $task): void
    {
        if (!empty($task->files)) {
            $task->processFiles($task->files);
        }
    }
}
