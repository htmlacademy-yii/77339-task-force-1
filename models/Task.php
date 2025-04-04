<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

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
 * @property Review[] $reviews
 */
class Task extends ActiveRecord
{

    /**
     * ENUM field values
     */
    const string STATUS_NEW = 'new';
    const string STATUS_IN_PROGRESS = 'in_progress';
    const string STATUS_COMPLETED = 'completed';
    const string STATUS_FAILED = 'failed';
    const string STATUS_CANCELED = 'canceled';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['budget', 'city_id', 'latitude', 'longitude', 'ended_at', 'executor_id'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 'new'],
            [['title', 'description', 'category_id', 'customer_id'], 'required'],
            [['description', 'status'], 'string'],
            [['category_id', 'city_id', 'customer_id', 'executor_id'], 'integer'],
            [['budget', 'latitude', 'longitude'], 'number'],
            [['ended_at', 'created_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['customer_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['executor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'category_id' => 'Category ID',
            'budget' => 'Budget',
            'status' => 'Status',
            'city_id' => 'City ID',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'ended_at' => 'Ended At',
            'customer_id' => 'Customer ID',
            'executor_id' => 'Executor ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return ActiveQuery
     */
    public function getCity(): ActiveQuery
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return ActiveQuery
     */
    public function getCustomer(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'customer_id']);
    }

    /**
     * Gets query for [[Executor]].
     *
     * @return ActiveQuery
     */
    public function getExecutor(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'executor_id']);
    }

    /**
     * Gets query for [[Files]].
     *
     * @return ActiveQuery
     */
    public function getFile(): ActiveQuery
    {
        return $this->hasMany(File::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return ActiveQuery
     */
    public function getResponse(): ActiveQuery
    {
        return $this->hasMany(Response::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return ActiveQuery
     */
    public function getReview(): ActiveQuery
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
        return $this->status === self::STATUS_NEW;
    }

    public function setStatusToNew(): void
    {
        $this->status = self::STATUS_NEW;
    }

    /**
     * @return bool
     */
    public function isStatusInProgress(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    public function setStatusToInProgress(): void
    {
        $this->status = self::STATUS_IN_PROGRESS;
    }

    /**
     * @return bool
     */
    public function isStatusCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function setStatusToCompleted(): void
    {
        $this->status = self::STATUS_COMPLETED;
    }

    /**
     * @return bool
     */
    public function isStatusFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function setStatusToFailed(): void
    {
        $this->status = self::STATUS_FAILED;
    }

    /**
     * @return bool
     */
    public function isStatusCanceled(): bool
    {
        return $this->status === self::STATUS_CANCELED;
    }

    public function setStatusToCanceled(): void
    {
        $this->status = self::STATUS_CANCELED;
    }
}
