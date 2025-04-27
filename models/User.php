<?php

namespace app\models;

use app\handlers\UserAfterSaveHandler;
use DateMalformedStringException;
use DateTime;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Class User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $password_hash
 * @property string|null $auth_key
 * @property string $role
 * @property string|null $avatar
 * @property string|null $telegram
 * @property string|null $phone
 * @property string|null $birthday
 * @property string|null $info
 * @property string|null $created_at
 * @property float|null $executor_rating
 * @property int|null $executor_reviews_count
 * @property int|null $city_id
 * @property int|null $show_contacts
 *
 * @property-read City|null $city
 * @property-read Category[] $categories
 * @property-read Response[] $responses
 * @property-read Review[] $reviews
 * @property-read Review[] $executorReviews
 * @property-read Task[] $tasks
 * @property-read Task[] $executorTasks
 * @property-read null|int $age
 * @property-read int $executorReviewsCount
 * @property-read ActiveQuery $failedTasks
 * @property-write mixed $password
 * @property-read null|string $authKey
 * @property-read int $executorRank
 * @property-read UserSpecialization[] $userSpecializations
 */
class User extends ActiveRecord implements IdentityInterface
{

    /**
     * ENUM field values
     */
    const string ROLE_CUSTOMER = 'customer';
    const string ROLE_EXECUTOR = 'executor';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['city_id', 'avatar', 'telegram', 'phone', 'birthday', 'info'], 'default', 'value' => null],
            [['show_contacts', 'executor_reviews_count'], 'default', 'value' => 0],
            [['executor_rating'], 'default', 'value' => 0.00],
            [['accepts_orders'], 'boolean'],
            [['role', 'info'], 'string'],
            [['city_id', 'show_contacts', 'executor_reviews_count'], 'integer'],
            [['birthday', 'created_at'], 'safe'],
            [['name', 'email', 'password_hash', 'avatar', 'telegram'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],
            [['executor_rating'], 'number', 'min' => 0, 'max' => 5],
            [['executor_rating'], 'default', 'value' => 0],
            ['role', 'in', 'range' => array_keys(self::optsRole())],
            [['email'], 'unique'],
            [['email'], 'email'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Ваше имя',
            'email' => 'Электронная почта',
            'role' => 'Role',
            'avatar' => 'Avatar',
            'telegram' => 'Telegram',
            'accepts_orders' => 'Is accept',
            'phone' => 'Phone',
            'show_contacts' => 'Show Contacts',
            'birthday' => 'Birthday',
            'info' => 'Info',
            'created_at' => 'Created At',
            'executor_rating' => 'Рейтинг исполнителя',
            'executor_reviews_count' => 'Количество отзывов',
        ];
    }

    /**
     * Рассчитывает количество отзывов исполнителя
     *
     * @return int
     */
    public function getExecutorReviewsCount(): int
    {
        return $this->getExecutorReviews()->count();
    }

    /**
     * Получает отзывы исполнителя
     *
     * @return ActiveQuery
     */
    public function getExecutorReviews(): ActiveQuery
    {
        return $this->hasMany(Review::class, ['executor_id' => 'id']);
    }

    public function behaviors(): array
    {
        return [
            'afterSaveHandler' => [
                'class' => UserAfterSaveHandler::class,
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function setPassword($password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Рассчитывает рейтинг исполнителя
     *
     * @return float|int
     */
    public function calculateExecutorRating(): float|int
    {
        $totalRating = (float)$this->getExecutorReviews()->sum('rating');
        $reviewCount = (int)$this->getExecutorReviewsCount();
        $failedTasksCount = (int)$this->getFailedTasks()->count();

        return ($reviewCount + $failedTasksCount) > 0 ? $totalRating / ($reviewCount + $failedTasksCount) : 0;
    }

    /**
     * Возвращает место в рейтинге среди исполнителей
     * @return int
     */
    public function getExecutorRank(): int
    {
        $subQuery = self::find()
            ->select(['id', 'executor_rating'])
            ->where(['role' => self::ROLE_EXECUTOR])
            ->andWhere(['not', ['executor_rating' => null]])
            ->orderBy(['executor_rating' => SORT_DESC]);

        $rank = (new \yii\db\Query())
            ->select(['rank' => 'COUNT(*) + 1'])
            ->from(['u' => $subQuery])
            ->where(['>', 'u.executor_rating', $this->executor_rating])
            ->scalar();

        return $rank ?: 1;
    }

    /**
     * Обновляет вычисляемые значения в базе данных
     *
     * @return void
     */
    public function updateExecutorStars(): void
    {
        if ($this->role === self::ROLE_EXECUTOR) {
            $this->executor_rating = $this->calculateExecutorRating();
            $this->executor_reviews_count = $this->getExecutorReviewsCount();

            $this->updateAttributes(['executor_rating', 'executor_reviews_count']);
        }
    }

    /**
     * Ищет пользователя по email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail(string $email): ?self
    {
        return self::findOne(['email' => $email]);
    }

    /**
     * Валидирует пароль
     *
     * @param string $password
     * @return bool
     */
    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Получает категории
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getCategories(): ActiveQuery
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])->viaTable('user_specializations', ['user_id' => 'id']);
    }

    /**
     * Получает id города пользователя
     *
     * @return ActiveQuery
     */
    public function getCity(): ActiveQuery
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    /**
     * Получает возраст пользователя
     *
     * @throws DateMalformedStringException
     */
    public function getAge(): ?int
    {
        if (empty($this->birthday)) {
            return null;
        }

        $birthday = new DateTime($this->birthday);
        $today = new DateTime();
        $interval = $today->diff($birthday);

        return $interval->y;
    }

    /**
     * Получает отклики исполнителя
     *
     * @return ActiveQuery
     */
    public function getResponses(): ActiveQuery
    {
        return $this->hasMany(Response::class, ['executor_id' => 'id']);
    }

    /**
     * Получает отзывы заказчика
     *
     * @return ActiveQuery
     */
    public function getReviews(): ActiveQuery
    {
        return $this->hasMany(Review::class, ['customer_id' => 'id']);
    }


    /**
     * Получает задания заказчика
     *
     * @return ActiveQuery
     */
    public function getTasks(): ActiveQuery
    {
        return $this->hasMany(Task::class, ['customer_id' => 'id']);
    }

    /**
     * Получает задания исполнителя
     *
     * @return ActiveQuery
     */
    public function getExecutorTasks(): ActiveQuery
    {
        return $this->hasMany(Task::class, ['executor_id' => 'id']);
    }

    /**
     * Получает специализации пользователя
     *
     * @return ActiveQuery
     */
    public function getUserSpecializations(): ActiveQuery
    {
        return $this->hasMany(UserSpecialization::class, ['user_id' => 'id']);
    }


    /**
     *Возвращает массив доступных ролей пользователя
     *
     * column role ENUM value labels
     * @return string[]
     */
    public static function optsRole(): array
    {
        return [
            self::ROLE_CUSTOMER => 'customer',
            self::ROLE_EXECUTOR => 'executor',
        ];
    }

    /**
     * Возвращает текстовое представление роли текущего пользователя
     *
     * @return string
     */
    public function displayRole(): string
    {
        return self::optsRole()[$this->role];
    }

    /**
     * Проверяет, является ли пользователь заказчиком
     *
     * @return bool
     */
    public function isRoleCustomer(): bool
    {
        return $this->role === self::ROLE_CUSTOMER;
    }

    /**
     * Устанавливает роль "Заказчик" для текущего пользователя
     *
     * @return void
     */
    public function setRoleToCustomer(): void
    {
        $this->role = self::ROLE_CUSTOMER;
    }

    /**
     * Проверяет, является ли пользователь исполнителем
     *
     * @return bool
     */
    public function isRoleExecutor(): bool
    {
        return $this->role === self::ROLE_EXECUTOR;
    }

    /**
     * Устанавливает роль "Исполнитель" для текущего пользователя.
     *
     * @return void
     */
    public function setRoleToExecutor(): void
    {
        $this->role = self::ROLE_EXECUTOR;
    }

    /**
     * Получает проваленные задания у исполнителя
     *
     * @return ActiveQuery
     */
    private function getFailedTasks(): ActiveQuery
    {
        return $this->hasMany(Task::class, ['executor_id' => 'id'])
            ->andWhere(['status' => Task::STATUS_FAILED]);
    }

    /**
     * @throws Exception
     */
    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }

    public static function findIdentity($id): User|IdentityInterface|null
    {
        return static::findOne($id);
    }

    /**
     * @param $token
     * @param $type
     * @return IdentityInterface|null
     */
    public static function findIdentityByAccessToken($token, $type = null): ?IdentityInterface
    {
        return null;
    }

    /**
     * @return int|string
     */
    public function getId(): int|string
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getAuthKey(): ?string
    {
        return $this->auth_key;
    }

    /**
     * @param $authKey
     * @return bool|null
     */
    public function validateAuthKey($authKey): ?bool
    {
        return $this->auth_key === $authKey;
    }
}
