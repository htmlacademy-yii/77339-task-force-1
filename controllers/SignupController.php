<?php

namespace app\controllers;

use app\logic\SignupUserAction;
use app\models\City;
use app\models\SignupForm;
use Yii;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\Response;

class SignupController extends Controller
{
    /**
     * @throws Exception
     */
    public function actionIndex(): Response|string
    {
        $model = new SignupForm();
        $cities = City::find()->select(['name', 'id'])->indexBy('id')->column();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $signupAction = new SignupUserAction();
            $user = $signupAction->execute($model);

            if ($user) {
                Yii::$app->user->login($user);
                return $this->goHome();
            }
        }
        return $this->render('index', [
            'model' => $model,
            'cities' => $cities
        ]);
    }
}
