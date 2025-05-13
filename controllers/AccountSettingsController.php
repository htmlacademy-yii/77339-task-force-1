<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\AccountSettingsForm;
use app\models\Category;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\Exception;
use yii\web\UploadedFile;

final class AccountSettingsController extends SecuredController
{
    /**
     * @throws Exception
     * @throws \yii\base\Exception
     * @throws InvalidConfigException
     */
    public function actionSettings()
    {
        $user = Yii::$app->user->identity;
        $model = new AccountSettingsForm();
        $model->loadFromUser($user);

        if ($model->load(Yii::$app->request->post())) {
            $model->avatar = UploadedFile::getInstance($model, 'avatar');

            if ($model->validate()) {
                $model->applyToUser($user);

                if (!empty($model->new_password)) {
                    $user->setPassword($model->new_password);
                }

                if ($user->save()) {
                    $user->updateCategories($model->categories);
                    Yii::$app->session->setFlash('success', 'Настройки успешно сохранены');

                    return $this->redirect(['/users/view', 'id' => $user->id]);
                }
            }
        }

        $categories = Category::find()->all();

        return $this->render('settings', [
            'model' => $model,
            'categories' => $categories,
            'user' => $user,
        ]);
    }

    /**
     * @return array
     */
    public function actionSecurity()
    {
        $user = Yii::$app->user->identity;
        $model = new AccountSettingsForm();
        $model->loadFromUser($user);

        if ($model->load(Yii::$app->request->post())) {
            $model->avatar = UploadedFile::getInstance($model, 'avatar');

            if ($model->validate()) {
                $model->applyToUser($user);

                if (!empty($model->new_password)) {
                    $user->setPassword($model->new_password);
                }

                if ($user->save()) {
                    $user->updateCategories($model->categories);
                    Yii::$app->session->setFlash('success', 'Настройки успешно сохранены');

                    return $this->redirect(['/users/view', 'id' => $user->id]);
                }
            }
        }

        return $this->render('security', [
            'model' => $model,
            'user' => $user,
        ]);
    }
}
