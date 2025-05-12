<?php

namespace app\controllers;

use app\models\Categories;
use app\models\EditProfileForm;
use app\models\Users;
use Yii;
use yii\helpers\VarDumper;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

class ProfileController extends BaseAuthController
{
    public $defaultAction = 'settings';

    public function actionSettings()
    {
        /** @var Users $user*/
        $user = Users::findModel(Yii::$app->user->identity->id);
        $categories = Categories::getAllCategoriesNames();

        $model = new EditProfileForm($user);
        $model->scenario = EditProfileForm::SCENARIO_PROFILE_SETTINGS;

        if (Yii::$app->request->getIsPost()) {
            $model->load(Yii::$app->request->post());
            $model->avatar = UploadedFile::getInstance($model, 'avatar');

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($model);
            }

            if ($model->updateProfile()) {
                return \Yii::$app->user->can('worker')
                    ? $this->redirect(['user/view', 'id' => $user->id])
                    : $this->goHome();
            }
        }

        return $this->render('settings', compact('model', 'categories'));
    }

    public function actionSecurity()
    {
        /** @var Users $user*/
        $user = Users::findModel(Yii::$app->user->identity->id);

        $model = new EditProfileForm($user);
        $model->scenario = EditProfileForm::SCENARIO_SECURITY_SETTINGS;

        if (
            $model->load(Yii::$app->request->post())
            && $model->changePassword()
        ) {
            return \Yii::$app->user->can('worker')
                ? $this->redirect(['user/view', 'id' => $user->id])
                : $this->goHome();
        }

        return $this->render('security', compact('model'));
    }
}