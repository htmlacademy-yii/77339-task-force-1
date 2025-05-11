<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use Taskforce\Service\Task\TaskStatuses;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $full_name
 * @property string $email
 * @property string $password
 * @property string $auth_key
 * @property int $city_id
 * @property int|null $avatar_id
 * @property string|null $date_created
 * @property string|null $birthdate
 * @property string|null $phone
 * @property string|null $telegram
 * @property string|null $about
 *
 * @property Files $avatar
 * @property Cities $city
 * @property Feedbacks[] $feedbacks
 * @property Reactions[] $reactions
 * @property Tasks[] $clientTasks
 * @property Tasks[] $workerTasks
 * @property UserCategories[] $userCategories
 */

class Users extends ActiveRecord implements IdentityInterface
{
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_REGISTER = 'register';

    const STATUS_FREE = 'Открыт для новых заказов';
    const STATUS_BUSY = 'Занят';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_LOGIN] = ['email', 'password'];
        $scenarios[self::SCENARIO_REGISTER] = ['full_name', 'email', 'password', '!password_repeat', '!is_worker'];

        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['full_name', 'email', 'password', 'city_id', 'auth_key'], 'required'],
            [['full_name', 'email', 'password', 'about'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 11],
            [['telegram'], 'string', 'max' => 64],
            [['email'], 'unique'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::class, 'targetAttribute' => ['city_id' => 'id']],
            [['avatar_id'], 'exist', 'skipOnError' => true, 'targetClass' => Files::class, 'targetAttribute' => ['avatar_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'full_name' => 'Full Name',
            'email' => 'Email',
            'password' => 'Password',
            'city_id' => 'City ID',
            'avatar_id' => 'Avatar ID',
            'date_created' => 'Date Created',
            'auth_key' => 'Auth Key',
            'birthdate' => 'Birthdate',
            'phone' => 'Phone',
            'telegram' => 'Telegram',
            'about' => 'About',
        ];
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }


    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
         return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Finds user by username
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    public function generateSafePassword($password) {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Gets query for [[Avatar]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAvatar()
    {
        return $this->hasOne(Files::class, ['id' => 'avatar_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Feedbacks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClientFeedbacks()
    {
        return $this->hasMany(Feedbacks::class, ['client_id' => 'id']);
    }

    /**
     * Gets query for [[Feedbacks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkerFeedbacks()
    {
        return $this->hasMany(Feedbacks::class, ['worker_id' => 'id']);
    }

    /**
     * Gets query for [[Reactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReactions()
    {
        return $this->hasMany(Reactions::class, ['worker_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClientTasks()
    {
        return $this->hasMany(Tasks::class, ['client_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkerTasks()
    {
        return $this->hasMany(Tasks::class, ['worker_id' => 'id']);
    }

    /**
     * Gets query for [[UserCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserCategories()
    {
        return $this->hasMany(UserCategories::class, ['user_id' => 'id']);
    }

    public function getActiveTasks()
    {
        return $this->getWorkerTasks()->where(['current_status' => TaskStatuses::STATUS_ACTIVE])->exists();
    }

    public function getFailedTasks()
    {
        return $this->getWorkerTasks()->where(['current_status' => TaskStatuses::STATUS_FAIL])->count();
    }

    public function getFinishedTasks()
    {
        return $this->getWorkerTasks()->where(['current_status' => TaskStatuses::STATUS_DONE])->count();
    }

    public function getUserStatus()
    {
        return $this->getActiveTasks() ? self::STATUS_BUSY : self::STATUS_FREE;
    }

    public function getWorkerRating()
    {
        $feedbacksSum = (int)$this->getWorkerFeedbacks()->sum('rating');

        $feedbacksAmount = $this->getWorkerFeedbacks()->count();
        $failedTasksAmount = $this->getFailedTasks();

        if (empty($feedbacksAmount + $failedTasksAmount)) {
            return 0;
        }

        return round($feedbacksSum / ($feedbacksAmount + $failedTasksAmount), 2);
    }

    public function calculateUserAge()
    {
        if (empty($this->birthdate)) {
            return '';
        }

        $now = new \DateTime();
        $birthdate = new \DateTime($this->birthdate);

        $diff = $now->diff($birthdate);

        return $diff->y;

    }

    public function calculateRatingPlace()
    {
        $users = self::find()->all();

        $rating = [];

        foreach ($users as $id => $user) {
            $rating[$id]['user_id'] = $user->id;
            $rating[$id]['rating'] = (double)Feedbacks::find()->where(['worker_id' => $user->id])->average('rating');
        }

        usort($rating, fn ($v1, $v2) => $v2['rating'] <=> $v1['rating']);

        $targetRatingKey = array_search($this->id, array_column($rating, 'user_id'));

        // индексы начинаются с 0, добавляем 1 для указания места в рейтинге
        $targetRatingValue = $rating[$targetRatingKey]['rating'] != 0 ? $targetRatingKey + 1 . ' место': 'пока не участвует в рейтинге';

        return $targetRatingValue;
    }

    public function getRegisterDateFormat()
    {
        return Yii::$app->formatter->asDate($this->date_created, 'php:d F, H:i');
    }

    public function getSpecialities()
    {
        $userSpecialities = $this->getUserCategories()->all();

        $uniqueKeys = array_unique(ArrayHelper::getColumn($userSpecialities, 'category_id'));

        return Categories::find()->where(['id' => $uniqueKeys])->all();
    }
}
