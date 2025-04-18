<?php

namespace app\models;

use DateMalformedStringException;
use DateTime;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password_hash
 * @property string $role
 * @property int|null $city_id
 * @property string|null $avatar
 * @property string|null $telegram
 * @property string|null $phone
 * @property int|null $show_contacts
 * @property string|null $birthday
 * @property string|null $info
 * @property string|null $created_at
 * @property int $accepts_orders
 *
 * @property Category[] $category
 * @property City $city
 * @property Response[] $response
 * @property Review[] $review
 * @property Review[] $review0
 * @property Task[] $task
 * @property Task[] $task0
 * @property UserSpecialization[] $userSpecialization
 * @property float|int $executor_rating
 * @property-read null|int $age
 * @property-read int $executorReviewsCount
 * @property-read ActiveQuery $categories
 * @property-read ActiveQuery $executorReviews
 * @property-read float $executorRating
 * @property int $executor_reviews_count
 */
class User extends ActiveRecord
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
            [['name', 'email', 'password_hash', 'role'], 'required'],
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
            'name' => 'Name',
            'email' => 'Email',
            'password_hash' => 'Password Hash',
            'role' => 'Role',
            'city_id' => 'City ID',
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

    /**
     * Определяет поведения модели.
     *
     * Добавляет автоматическое обновление рейтинга исполнителя и количества отзывов:
     * - При создании (EVENT_AFTER_INSERT)
     * - При обновлении (EVENT_AFTER_UPDATE)
     *
     * @return array Конфигурация поведений модели
     *
     * @uses calculateExecutorRating() Для расчёта текущего рейтинга исполнителя
     * @uses getExecutorReviews() Для получения связанных отзывов
     *
     * @example
     * При изменении статуса задания или добавлении отзыва автоматически
     * пересчитывает рейтинг исполнителя по формуле:
     * сумма оценок / (количество отзывов + проваленные задания)
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_AFTER_INSERT => ['executor_rating', 'executor_reviews_count'],
                    ActiveRecord::EVENT_AFTER_UPDATE => ['executor_rating', 'executor_reviews_count'],
                ],
                'value' => function ($event) {
                    if ($this->role === self::ROLE_EXECUTOR) {
                        return [
                            'executor_rating' => $this->calculateExecutorRating(),
                            'executor_reviews_count' => $this->getExecutorReviews()->count(),
                        ];
                    }
                    return null;
                }
            ]
        ];
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
}
