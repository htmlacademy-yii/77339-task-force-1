<?php

namespace app\models;

use Taskforce\Service\Api\Geocoder;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "cities".
 *
 * @property int $id
 * @property string $name
 * @property string|null $latitude
 * @property string|null $longitude
 *
 * @property Tasks[] $tasks
 * @property Users[] $users
 */
class Cities extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cities';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'latitude', 'longitude'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
        ];
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Tasks::class, ['city_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(Users::class, ['city_id' => 'id']);
    }

    public static function getAllCityNames()
    {
        $cities = self::find()->asArray()->all();

        return ArrayHelper::map($cities, 'id', 'name');
    }

    public static function findCityIdByName(string $cityName): ?int
    {
        $city = self::find()->where(['name' => $cityName])->one();

        if (!empty($city)) {
            return $city->id;
        }

        $api = new Geocoder();
        $city = $api->getCoordinates($cityName, 1)[0];

        if (empty($city)) {
            return null;
        }

        $newCity = new self();
        $newCity->name = $city['city'];
        $newCity->latitude = $city['latitude'];
        $newCity->longitude = $city['longitude'];
        $result = $newCity->save();

        if ($result) {
            return $newCity->id;
        }

        return null;
    }
}
