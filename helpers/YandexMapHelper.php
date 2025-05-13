<?php

namespace app\helpers;

use app\models\City;
use Yandex\Geo\Api;
use Yandex\Geo\Exception;
use yii\base\Component;
use yii\caching\CacheInterface;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

final class YandexMapHelper extends Component
{
    private string $apiKey;
    private ?CacheInterface $cache = null;
    private int $cacheDuration = 86400;
    private Api $apiClient;

    /**
     * @param string $apiKey
     * @param array $config
     */
    public function __construct(string $apiKey, $config = [])
    {
        $this->apiKey = $apiKey;
        $this->apiClient = new Api($this->apiKey);
        parent::__construct($config);
    }

    /**
     * @param string $address
     *
     * @return array|null
     */
    public function getCoordinates($address): ?array
    {
        $cacheKey = 'coords_' . md5($address);

        if ($this->cache && ($coords = $this->cache->get($cacheKey))) {
            return $coords;
        }

        try {
            $apiUrl = sprintf(
                'https://geocode-maps.yandex.ru/1.x/?format=json&geocode=%s&apikey=%s',
                urlencode($address),
                $this->apiKey
            );

            $response = file_get_contents($apiUrl);
            $data = json_decode($response, true);

            if (empty($data['response']['GeoObjectCollection']['featureMember'])) {
                throw new Exception('Адрес не найден');
            }

            $geoObject = $data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject'];
            $pos = $geoObject['Point']['pos'];
            list($lng, $lat) = explode(' ', $pos);

            $result = ['lat' => (float)$lat, 'lng' => (float)$lng];

            if ($this->cache) {
                $this->cache->set($cacheKey, $result, $this->cacheDuration);
            }

            return $result;
        } catch (\Exception $e) {
            Yii::error("Ошибка геокодирования: " . $e->getMessage());
            return null;
        }
    }

    /**
     * @param float $latitude
     * @param float $longitude
     *
     * @return array|ActiveRecord
     */
    public function findNearestCity(float $latitude, float $longitude): array|ActiveRecord
    {
        return City::find()->select(['*'])->orderBy(
            new Expression(
                "
            POWER(latitude - {$latitude}, 2) + 
            POWER(longitude - {$longitude}, 2)
        "
            )
        )->limit(1)->one();
    }

    /**
     * @param float|null $latitude
     * @param float|null $longitude
     *
     * @return string
     */
    public function getAddress(?float $latitude, ?float $longitude): string
    {
        if ($latitude === null || $longitude === null) {
            return 'Адрес не указан';
        }

        $cacheKey = "address_{$latitude}_{$longitude}";

        if ($this->cache && ($address = $this->cache->get($cacheKey))) {
            return $address;
        }

        try {
            $apiUrl = sprintf(
                'https://geocode-maps.yandex.ru/1.x/?format=json&geocode=%s,%s&apikey=%s',
                $longitude,
                $latitude,
                $this->apiKey
            );

            $response = file_get_contents($apiUrl);
            $data = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON response');
            }

            if (
                !empty(
                $data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']
                ['metaDataProperty']['GeocoderMetaData']['text']
                )
            ) {
                $address = $data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']
                ['metaDataProperty']['GeocoderMetaData']['text'];

                if ($this->cache) {
                    $this->cache->set($cacheKey, $address, $this->cacheDuration);
                }

                return $address;
            }
        } catch (\Exception $e) {
            Yii::error("Ошибка обратного геокодирования: " . $e->getMessage());
        }

        return 'Адрес не определен';
    }

    /**
     * @param CacheInterface 
     */
    public function setCache(CacheInterface $cache): void
    {
        $this->cache = $cache;
    }

    /**
     * @param int
     */
    public function setCacheDuration(int $seconds): void
    {
        $this->cacheDuration = $seconds;
    }
}