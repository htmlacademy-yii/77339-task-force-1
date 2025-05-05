<?php

namespace app\models;

use AllowDynamicProperties;
use app\interfaces\FilesUploadInterface;
use app\interfaces\TaskValidatorInterface;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use app\models\TaskStatus;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $category_id
 * @property float|null $budget
 * @property string $status
 * @property int|null $city_id
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $ended_at
 * @property int $customer_id
 * @property int|null $executor_id
 * @property string|null $created_at
 *
 * @property Category $category
 * @property City $city
 * @property User $customer
 * @property User $executor
 * @property File[] $files
 * @property Response[] $responses
 * @property-read string $statusLabel
 * @property-read ActiveQuery $searchQuery
 * @property Review[] $reviews
 * @property FilesUploadInterface $fileUploader
 * @property TaskValidatorInterface $validator
 */
class Task extends ActiveRecord
{
    public array $categoryIds = [];
    public string $noResponses = '0';
    public string $noLocation = '0';
    public $filterPeriod;
    public array $files = [];

    /**
     * ENUM field values
     */
    public const string STATUS_NEW = 'new';
    public const string STATUS_IN_PROGRESS = 'in_progress';
    public const string STATUS_COMPLETED = 'completed';
    public const string STATUS_FAILED = 'failed';
    public const string STATUS_CANCELED = 'canceled';

    public const string SCENARIO_CREATE = 'create';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'tasks';
    }

    public function rules(): array
    {
        return [
            [['budget', 'city_id', 'latitude', 'longitude', 'ended_at', 'executor_id'], 'default', 'value' => null],
            [['status'], 'default', 'value' => TaskStatus::STATUS_NEW],
            [
                ['title', 'description', 'category_id', 'customer_id'],
                'required',
                'on' => self::SCENARIO_CREATE,
                'message' => 'Поле обязательно для заполнения'
            ],
            [['title'], 'string', 'min' => 10, 'on' => self::SCENARIO_CREATE],
            [['description'], 'string', 'min' => 30, 'on' => self::SCENARIO_CREATE],
            [
                ['category_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Category::class,
                'targetAttribute' => ['category_id' => 'id']
            ],
            ['budget', 'integer', 'min' => 1, 'on' => self::SCENARIO_CREATE],
            ['ended_at', 'date', 'format' => 'php:Y-m-d', 'on' => self::SCENARIO_CREATE],
            ['ended_at', 'validateDeadline', 'on' => self::SCENARIO_CREATE],
            [['files'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 10, 'on' => self::SCENARIO_CREATE],
            [['description', 'status'], 'string'],
            [['category_id', 'city_id', 'customer_id', 'executor_id'], 'integer'],
            [['budget', 'latitude', 'longitude'], 'number'],
            [['ended_at', 'created_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            ['status', 'in', 'range' => TaskStatus::getValidStatuses()],
            [
                ['customer_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['customer_id' => 'id']
            ],
            [
                ['executor_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['executor_id' => 'id']
            ],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'title' => 'Опишите суть работы',
            'description' => 'Подробности задания',
            'category_id' => 'Категория',
            'budget' => 'Бюджет',
            'status' => 'Status',
            'city_id' => 'Локация',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'ended_at' => 'Срок исполнения',
            'customer_id' => 'Customer ID',
            'executor_id' => 'Executor ID',
            'created_at' => 'Created At',
        ];
    }

    private FilesUploadInterface $fileUploader;

    public function setFileUploader(FilesUploadInterface $fileUploader): void
    {
        $this->fileUploader = $fileUploader;
    }

    public function processFiles(array $files): array
    {
        if ($this->isNewRecord) {
            throw new \RuntimeException('Невозможно обработать файлы для несохраненной задачи');
        }

        return $this->fileUploader->upload($files, $this->id);
    }

    /**
     * @return array
     */
    public static function getStatusLabels(): array
    {
        return [
            self::STATUS_NEW => 'Новое',
            self::STATUS_IN_PROGRESS => 'В работе',
            self::STATUS_COMPLETED => 'Выполнено',
            self::STATUS_FAILED => 'Провалено',
            self::STATUS_CANCELED => 'Отменено'
        ];
    }

    public function validateDeadline($attribute, $params): bool
    {
        if ($this->$attribute && strtotime($this->$attribute) <= strtotime('now')) {
            $this->addError($attribute, 'Срок исполнения не может быть раньше текущей даты');
        }
        return true;
    }

    /**
     * @return string
     */
    public function getStatusLabel(): string
    {
        return TaskStatus::getLabel($this->status);
    }

    /**
     * Поиск задач с фильтрами
     */
    public function getSearchQuery(): ActiveQuery
    {
        $query = self::find()->where(['status' => self::STATUS_NEW]);

        if (!empty($this->categoryIds)) {
            $categoryIds = is_array($this->categoryIds)
                ? $this->categoryIds
                : array_filter(explode(',', $this->categoryIds));

            if (!empty($categoryIds)) {
                $query->andWhere(['category_id' => $categoryIds]);
            }
        }

        if ($this->noResponses) {
            $query->leftJoin('responses', 'responses.task_id = tasks.id')
                ->andWhere(['responses.id' => null]);
        }

        if ($this->noLocation) {
            $query->andWhere(['city_id' => null]);
        }

        if ($this->filterPeriod) {
            $query->andWhere([
                '>=',
                'created_at',
                date('Y-m-d H:i:s', time() - (int)$this->filterPeriod)
            ]);
        }

        return $query->orderBy(['created_at' => SORT_DESC]);
    }

    /**
     * Создает DataProvider для использования в GridView/ListView
     */
    public function getDataProvider($pageSize = 5): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => $this->getSearchQuery(),
            'pagination' => ['pageSize' => $pageSize],
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]]
        ]);
    }

    /**
     * Gets a query for [[Category]].
     *
     * @return ActiveQuery
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Фильтрация по статусу "Новые"
     */
    public static function findNewTasks(): ActiveQuery
    {
        return self::find()->where(['status' => self::STATUS_NEW]);
    }

    /**
     * Фильтрация задач без исполнителя
     */
    public static function findWithoutExecutor(): ActiveQuery
    {
        return self::find()->where(['executor_id' => null]);
    }

    /**
     * Фильтрация задач по периоду
     */
    public static function filterByPeriod(ActiveQuery $query, int $hours): ActiveQuery
    {
        return $query->andWhere(['>=', 'created_at', time() - $hours * 3600]);
    }

    /**
     * Фильтрация по категориям
     */
    public static function filterByCategories(ActiveQuery $query, array $categoryIds): ActiveQuery
    {
        return $query->andWhere(['category_id' => $categoryIds]);
    }

    /**
     * Gets a query for [[City]].
     *
     * @return ActiveQuery
     */
    public function getCity(): ActiveQuery
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    /**
     * Gets a query for [[Customer]].
     *
     * @return ActiveQuery
     */
    public function getCustomer(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'customer_id']);
    }

    /**
     * Gets a query for [[Executor]].
     *
     * @return ActiveQuery
     */
    public function getExecutor(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'executor_id']);
    }

    /**
     * Gets a query for [[Files]].
     *
     * @return ActiveQuery
     */
    public function getFiles(): ActiveQuery
    {
        return $this->hasMany(File::class, ['task_id' => 'id']);
    }

    /**
     * Gets a query for [[Responses]].
     *
     * @return ActiveQuery
     */
    public function getResponses(): ActiveQuery
    {
        return $this->hasMany(Response::class, ['task_id' => 'id']);
    }

    /**
     * Gets a query for [[Reviews]].
     *
     * @return ActiveQuery
     */
    public function getReviews(): ActiveQuery
    {
        return $this->hasMany(Review::class, ['task_id' => 'id']);
    }


    /**
     * column status ENUM value labels
     * @return string[]
     */
    public static function optsStatus(): array
    {
        return [
            self::STATUS_NEW => 'new',
            self::STATUS_IN_PROGRESS => 'in_progress',
            self::STATUS_COMPLETED => 'completed',
            self::STATUS_FAILED => 'failed',
            self::STATUS_CANCELED => 'canceled',
        ];
    }

    /**
     * @return string
     */
    public function displayStatus(): string
    {
        return self::optsStatus()[$this->status];
    }

    /**
     * @return bool
     */
    public function isStatusNew(): bool
    {
        return $this->status === TaskStatus::STATUS_NEW;
    }

    public function setStatus(string $status): void
    {
        if (!in_array($status, TaskStatus::getValidStatuses())) {
            throw new \InvalidArgumentException('Invalid status');
        }
        $this->status = $status;
    }

    /**
     * @return bool
     */
    public function isStatusInProgress(): bool
    {
        return $this->status === TaskStatus::STATUS_IN_PROGRESS;
    }

    public function isStatusCompleted(): bool
    {
        return $this->status === TaskStatus::STATUS_COMPLETED;
    }

    public function isStatusFailed(): bool
    {
        return $this->status === TaskStatus::STATUS_FAILED;
    }

    public function isStatusCanceled(): bool
    {
        return $this->status === TaskStatus::STATUS_CANCELED;
    }
}
