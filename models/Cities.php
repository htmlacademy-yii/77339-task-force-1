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

    public static function refineCityIdByName(string $cityName): ?int
    {
        $city = self::find()->where(['name' => $cityName])->one();

        if (!empty($city)) {
            return $city->id;
        }

        $api = new Geocoder();
        $cities = $api->getCoordinates($cityName, 1);

        if ($cities === null || empty($cities) || !isset($cities[0])) {
            Yii::error('Failed to get coordinates for city: ' . $cityName);
            return null;
        }

        $city = $cities[0];
        if (empty($city['city']) || empty($city['latitude']) || empty($city['longitude'])) {
            Yii::error('Invalid city data received from geocoder: ' . json_encode($city));
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

        Yii::error('Failed to save new city: ' . json_encode($newCity->errors));
        return null;
    }

    public static function findCityIdByName(array $city): ?int
    {
        $result = self::find()->where(['name' => $city['name']])->one();

        if (!empty($result)) {
            return $result->id;
        }

        $newCity = new self();
        $newCity->name = $city['name'];
        $newCity->latitude = $city['latitude'];
        $newCity->longitude = $city['longitude'];
        $result = $newCity->save();

        if ($result) {
            return $newCity->id;
        }

        return null;
    }
}
