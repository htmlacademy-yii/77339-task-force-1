<?php

namespace app\models;

use app\models\Users;
use Yii;
use yii\base\Model;

/**
 * RegisterSocialForm is the model behind the login social form.
 *
 * @property-read Users|null $user
 *
 */
class RegisterSocialForm extends Model
{
    public $location;
    public $latitude;
    public $longitude;

    public $is_worker;

    protected $cityId;
    protected $userRole;

    public function attributeLabels()
    {
        return [
            'location' => 'Мой город',
            'is_worker' => 'Я собираюсь откликаться на заказы',
        ];
    }


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['location', 'latitude', 'longitude'], 'required', 'message' => 'Пожалуйста выберите город из списка автоподстановки'],
            [['location'], 'string', 'min' => 2],
            [['latitude', 'longitude'], 'safe'],
            ['is_worker', 'boolean'],
            ['is_worker', 'default', 'value' => null],
        ];
    }


    /**
     * Finds cityId by [[location]]
     *
     * @return int
     */
    public function getCityId()
    {
        $city = [
            'name' => $this->location,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude
        ];

        $this->cityId = Cities::findCityIdByName($city);

        return $this->cityId;
    }

    /**
     * Finds userRole by [[is_worker]]
     *
     * @return int
     */
    public function getUserRole()
    {
        $this->userRole = empty($this->is_worker) ? 'client' : 'worker';
        return $this->userRole;
    }
}
