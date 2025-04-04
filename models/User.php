<?php

namespace app\models;

use Yii;
use yii\base\InvalidConfigException;
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
 *
 * @property Category[] $category
 * @property City $city
 * @property Response[] $response
 * @property Review[] $review
 * @property Review[] $review0
 * @property Task[] $task
 * @property Task[] $task0
 * @property UserSpecialization[] $userSpecialization
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
            [['show_contacts'], 'default', 'value' => 1],
            [['name', 'email', 'password_hash', 'role'], 'required'],
            [['role', 'info'], 'string'],
            [['city_id', 'show_contacts'], 'integer'],
            [['birthday', 'created_at'], 'safe'],
            [['name', 'email', 'password_hash', 'avatar', 'telegram'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],
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
            'phone' => 'Phone',
            'show_contacts' => 'Show Contacts',
            'birthday' => 'Birthday',
            'info' => 'Info',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getCategories()
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])->viaTable('user_specializations', ['user_id' => 'id']);
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
     * Gets query for [[Responses]].
     *
     * @return ActiveQuery
     */
    public function getResponse(): ActiveQuery
    {
        return $this->hasMany(Response::class, ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return ActiveQuery
     */
    public function getReview(): ActiveQuery
    {
        return $this->hasMany(Review::class, ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews0]].
     *
     * @return ActiveQuery
     */
    public function getReview0(): ActiveQuery
    {
        return $this->hasMany(Review::class, ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return ActiveQuery
     */
    public function getTask(): ActiveQuery
    {
        return $this->hasMany(Task::class, ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks0]].
     *
     * @return ActiveQuery
     */
    public function getTask0(): ActiveQuery
    {
        return $this->hasMany(Task::class, ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[UserSpecializations]].
     *
     * @return ActiveQuery
     */
    public function getUserSpecialization(): ActiveQuery
    {
        return $this->hasMany(UserSpecialization::class, ['user_id' => 'id']);
    }


    /**
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
     * @return string
     */
    public function displayRole(): string
    {
        return self::optsRole()[$this->role];
    }

    /**
     * @return bool
     */
    public function isRoleCustomer(): bool
    {
        return $this->role === self::ROLE_CUSTOMER;
    }

    public function setRoleToCustomer(): void
    {
        $this->role = self::ROLE_CUSTOMER;
    }

    /**
     * @return bool
     */
    public function isRoleExecutor(): bool
    {
        return $this->role === self::ROLE_EXECUTOR;
    }

    public function setRoleToExecutor(): void
    {
        $this->role = self::ROLE_EXECUTOR;
    }
}
