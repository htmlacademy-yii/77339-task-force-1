<?php

namespace app\controllers;

use app\models\Users;
use yii\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;

class UserController extends Controller
{
    public function actionIndex()
    {

    }

    public function actionView($id)
    {
        $user = Users::findOne((int)$id);

        if (!$user) {
            throw new NotFoundHttpException("Пользователь с ID $id не найден");
        }

        $userData = [];

        $userAge = $user->calculateUserAge();

        $userData['userPhone'] = \Yii::$app->formatter->asPhone($user->phone);
        $userData['rating'] = $user->workerRating;
        $userData['age'] = Yii::$app->i18n->format(
            '{n, plural, =0{возраст не указан} =1{один год} one{# год} few{# года} many{# лет} other{# лет}}',
            ['n' => $userAge],
            'ru_RU'
        );
        $userData['status'] = $user->getUserStatus();
        $userData['ratingPlace'] = $user->calculateRatingPlace();
        $userData['specialities'] = $user->getSpecialities();

        return $this->render('view', compact('user', 'userData'));
    }
}