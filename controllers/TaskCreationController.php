<?php

namespace app\controllers;

use app\logic\Actions\CreateTaskAction;
use Yii;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

final class TaskCreationController extends SecuredController
{

    /**
     * @return array
     */
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
     * @return array
     */
    public function actions(): array
    {
        return [
            'create' => [
                'class' => CreateTaskAction::class,
            ],
        ];
    }

    /**
     * @param string|null
     * @return array
     */
    public function actionCityList($term = null): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (empty($term)) {
            return [];
        }

        $apiUrl = "https://geocode-maps.yandex.ru/1.x/?format=json&apikey=" .
            Yii::$app->params['yandexApiKey'] . "&geocode=" . urlencode(
                $term
            );

        try {
            $response = file_get_contents($apiUrl);
            $data = json_decode($response, true);

            $result = [];
            if (!empty($data['response']['GeoObjectCollection']['featureMember'])) {
                foreach ($data['response']['GeoObjectCollection']['featureMember'] as $item) {
                    $pos = $item['GeoObject']['Point']['pos'];
                    [$lng, $lat] = explode(' ', $pos);

                    $result[] = [
                        'value' => $item['GeoObject']['metaDataProperty']['GeocoderMetaData']['text'],
                        'latitude' => $lat,
                        'longitude' => $lng
                    ];
                }
            }

            return $result;
        } catch (\Exception $e) {
            Yii::error("Ошибка геокодирования: " . $e->getMessage());
            return [];
        }
    }

    /**
     * @param Task
     * @param array
     * @param array
     * @return string
     */
    protected function renderCreateForm($model, $categories, $cities): string
    {
        return $this->render('@app/views/tasks/create/create', [
            'model' => $model,
            'categories' => $categories,
            'cities' => $cities,
        ]);
    }
}
