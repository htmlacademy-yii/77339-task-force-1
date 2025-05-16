<?php

namespace app\models;

use app\interfaces\FilesUploadInterface;
use app\logic\Actions\CreateTaskAction;
use app\logic\AvailableActions;
use InvalidArgumentException;
use RuntimeException;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tasks".
 *
 * @property int                  $id
 * @property string               $title
 * @property string               $description
 * @property int                  $category_id
 * @property float|null           $budget
 * @property string               $status Статус задачи. Возможные значения:
 *          AvailableActions::STATUS_NEW,
 *          AvailableActions::STATUS_IN_PROGRESS,
 *          AvailableActions::STATUS_COMPLETED,
 *          AvailableActions::STATUS_FAILED,
 *          AvailableActions::STATUS_CANCELLED
 * @property int|null             $city_id
 * @property string|null          $ended_at
 * @property int                  $customer_id
 * @property int|null             $executor_id
 * @property string|null          $created_at
 * @property                      $latitude
 * @property                      $longitude
 *
 * @property Category             $category
 * @property City                 $city
 * @property User                 $customer
 * @property User                 $executor
 * @property File[]               $files
 * @property Response[]           $responses
 * @property-read string          $statusLabel
 * @property-read ActiveQuery     $searchQuery
 * @property Review[]             $reviews
 * @property FilesUploadInterface $fileUploader
 */
class Task extends ActiveRecord
{
    public $categoryIds;
    public $noResponses;
    public $noLocation;
    public $filterPeriod;
    public array $files = [];
    public string $location = '';
    public string $city_name = '';

    /**
     * {@inheritdoc}
     */
    public static function tableName() : string
    {
        return 'tasks';
    }

    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            [['budget', 'city_id', 'latitude', 'longitude', 'ended_at', 'executor_id'], 'default', 'value' => null],
            [['status'], 'in', 'range' => array_keys(AvailableActions::getStatusMap())],
            [
                ['category_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Category::class,
                'targetAttribute' => ['category_id' => 'id']
            ],
            [['description', 'status'], 'string'],
            [['category_id', 'city_id', 'customer_id', 'executor_id'], 'integer'],
            [['budget', 'latitude', 'longitude'], 'number'],
            [['latitude', 'longitude'], 'default', 'value' => null],
            [['city_id'], 'default', 'value' => null],
            [['city_id'], 'integer'],
            [['city_name'], 'string'],
            [['ended_at', 'created_at'], 'safe'],
            [['ended_at'], 'date', 'format' => 'php:Y-m-d'],
            [['ended_at'], 'compare', 'compareValue' => date('Y-m-d'), 'operator' => '>=', 'type' => 'date'],
            [['title'], 'string', 'max' => 255],
            [['location'], 'string', 'max' => 255],
            [['location'], 'safe'],
            [['categoryIds', 'noResponses', 'noLocation', 'filterPeriod'], 'safe'],
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
     * @param array
     * @return void
     */
    public function setLocation(array $location) : void
    {
        if (count($location) !== 2) {
            throw new InvalidArgumentException('Location must contain exactly 2 elements - latitude and longitude');
        }
        [$this->latitude, $this->longitude] =
            $location;
    }

    /**
     * @return array|null
     */
    public function getLocation() : ?array
    {
        if ($this->latitude === null || $this->longitude === null) {
            return null;
        }

        return [$this->latitude, $this->longitude];
    }

    /**
     * @return bool
     */
    public function hasLocation() : bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }

    /**
     * @return void
     */
    public function clearLocation() : void
    {
        $this->latitude =
            null;
        $this->longitude =
            null;
        $this->city_id =
            null;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() : array
    {
        return [
            'id' => 'ID',
            'title' => 'Опишите суть работы',
            'description' => 'Подробности задания',
            'category_id' => 'Категория',
            'budget' => 'Бюджет',
            'status' => 'Status',
            'city_id' => 'Локация',
            'location' => 'Локация',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'ended_at' => 'Срок исполнения',
            'customer_id' => 'Customer ID',
            'executor_id' => 'Executor ID',
            'created_at' => 'Created At',
        ];
    }

    public function scenarios() : array
    {
        $scenarios =
            parent::scenarios();
        $scenarios[CreateTaskAction::SCENARIO_CREATE] =
            [
                'title',
                'description',
                'category_id',
                'budget',
                'location',
                'city_id',
                'latitude',
                'longitude',
                'ended_at',
                'files'
            ];

        return $scenarios;
    }

    /**
     * @return void
     */
    public function defineScenario($name, $attributes) : void
    {
        $scenarios =
            $this->scenarios();
        $scenarios[$name] =
            $attributes;
        $this->setScenario($name);
    }

    private FilesUploadInterface $fileUploader;

    /**
     * @return void
     */
    public function setFileUploader(FilesUploadInterface $fileUploader) : void
    {
        $this->fileUploader =
            $fileUploader;
    }

    /**
     * @return array
     */
    public function processFiles(array $files) : array
    {
        if ($this->isNewRecord) {
            throw new RuntimeException('Невозможно обработать файлы для несохраненной задачи');
        }

        return $this->fileUploader->upload($files, $this->id);
    }

    /**
     * @return array
     */
    public static function getStatusLabels() : array
    {
        return AvailableActions::getStatusMap();
    }

    /**
     * @param string
     * @param array 
     * @return bool
     */
    public function validateDeadline($attribute, $params) : bool
    {
        if ($this->$attribute && strtotime($this->$attribute) <= strtotime('now')) {
            $this->addError($attribute, 'Срок исполнения не может быть раньше текущей даты');
        }

        return true;
    }

    /**
     * @return string
     */
    public function getStatusLabel() : string
    {
        return AvailableActions::getStatusMap()[$this->status] ?? $this->status;
    }

    /**
     * @return ActiveQuery
     */
    public function getSearchQuery() : ActiveQuery
    {
        $query =
            self::find()->where(['status' => AvailableActions::STATUS_NEW]);
        $query->andWhere(['>=', 'ended_at', date('Y-m-d')]);

        if (!empty($this->categoryIds)) {
            $categoryIds =
                is_array($this->categoryIds) ? $this->categoryIds : array_filter(
                    explode(',', $this->categoryIds)
                );

            if (!empty($categoryIds)) {
                $query->andWhere(['category_id' => $categoryIds]);
            }
        }

        if ($this->noResponses) {
            $query->leftJoin('responses', 'responses.task_id = tasks.id')->andWhere(['responses.id' => null]);
        }

        if ($this->noLocation) {
            $query->andWhere(['city_id' => null]);
        }

        if (!empty($this->filterPeriod)) {
            $period =
                (int)$this->filterPeriod;
            if ($period > 0) {
                $query->andWhere(['>=', 'tasks.created_at', date('Y-m-d H:i:s', time() - $period)]);
            }
        }

        return $query->orderBy(['created_at' => SORT_DESC]);
    }

    /**
     * @return ActiveDataProvider
     */
    public function getDataProvider($pageSize = 5) : ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => $this->getSearchQuery(),
            'pagination' => ['pageSize' => $pageSize],
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);
    }

    /**
     * @return ActiveQuery
     */
    public function getCategory() : ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * @return ActiveQuery
     */
    public static function findNewTasks() : ActiveQuery
    {
        return self::find()->where(['status' => AvailableActions::STATUS_NEW]);
    }

    /**
     * @return ActiveQuery
     */
    public static function findWithoutExecutor() : ActiveQuery
    {
        return self::find()->where(['executor_id' => null]);
    }

    /**
     * @return ActiveQuery
     */
    public static function filterByPeriod(ActiveQuery $query, int $hours) : ActiveQuery
    {
        return $query->andWhere(['>=', 'created_at', time() - $hours * 3600]);
    }

    /**
     * @return ActiveQuery
     */
    public static function filterByCategories(ActiveQuery $query, array $categoryIds) : ActiveQuery
    {
        return $query->andWhere(['category_id' => $categoryIds]);
    }

    /**
     * @return ActiveQuery
     */
    public function getCity() : ActiveQuery
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCustomer() : ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'customer_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getExecutor() : ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'executor_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getFiles() : ActiveQuery
    {
        return $this->hasMany(File::class, ['task_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getResponses() : ActiveQuery
    {
        return $this->hasMany(Response::class, ['task_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getReviews() : ActiveQuery
    {
        return $this->hasMany(Review::class, ['task_id' => 'id']);
    }

    /**
     * @return bool
     */
    public function isStatusNew() : bool
    {
        return $this->status === AvailableActions::STATUS_NEW;
    }

    public function setStatusToNew() : void
    {
        $this->status =
            AvailableActions::STATUS_NEW;
    }

    /**
     * @return bool
     */
    public function isStatusInProgress() : bool
    {
        return $this->status === AvailableActions::STATUS_IN_PROGRESS;
    }

    public function setStatusToInProgress() : void
    {
        $this->status =
            AvailableActions::STATUS_IN_PROGRESS;
    }

    /**
     * @return bool
     */
    public function isStatusCompleted() : bool
    {
        return $this->status === AvailableActions::STATUS_COMPLETED;
    }

    public function setStatusToCompleted() : void
    {
        $this->status =
            AvailableActions::STATUS_COMPLETED;
    }

    /**
     * @return bool
     */
    public function isStatusFailed() : bool
    {
        return $this->status === AvailableActions::STATUS_FAILED;
    }

    /**
     * @return void
     */
    public function setStatusToFailed() : void
    {
        $this->status =
            AvailableActions::STATUS_FAILED;
    }

    /**
     * @return bool
     */
    public function isStatusCanceled() : bool
    {
        return $this->status === AvailableActions::STATUS_CANCELLED;
    }

    /**
     * @return void
     */
    public function setStatusToCanceled() : void
    {
        $this->status =
            AvailableActions::STATUS_CANCELLED;
    }
}
