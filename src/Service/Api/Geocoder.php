<?php

namespace Taskforce\Service\Api;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Yii;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7;
use yii\helpers\ArrayHelper;

class Geocoder
{
    const URI = 'https://geocode-maps.yandex.ru/1.x/';
    const LOCATION_KEY = 'response.GeoObjectCollection.featureMember';
    const LOCATION_COUNTRY_KEY = 'GeoObject.metaDataProperty.GeocoderMetaData.AddressDetails.Country.CountryName';
    const LOCATION_CITY_KEY = 'GeoObject.metaDataProperty.GeocoderMetaData.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality.LocalityName';
    const LOCATION_ADDRESS_KEY = 'GeoObject.name';
    const LOCATION_COORDINATES_KEY = 'GeoObject.Point.pos';

    public function getCoordinates(string $address, $resultsCount = 5): ?array
    {
        $apiKey = Yii::$app->params['apiKeyGeocoder'];

        $client = new Client([
            'base_uri' => self::URI,
        ]);

        $result = [];
        try {
            $request = new Request('GET', self::URI);
            $response = $client->send($request, [
                'query' => [
                    'apikey' => $apiKey,
                    'format' => 'json',
                    'geocode' => $address,
                    'results' => $resultsCount
                ],
                'timeout' => 2
            ]);

            $content = $response->getBody()->getContents();
            $responseData = json_decode($content, true);
            $locationCollection = ArrayHelper::getValue($responseData, self::LOCATION_KEY);

            if (empty($locationCollection)) {
                return null;
            }

            foreach ($locationCollection as $locationObject) {
                $coordinates = explode(' ', ArrayHelper::getValue($locationObject, self::LOCATION_COORDINATES_KEY));
                if (count($coordinates) !== 2) {
                    continue;
                }
                
                $city = ArrayHelper::getValue($locationObject, self::LOCATION_CITY_KEY);
                $location = ArrayHelper::getValue($locationObject, self::LOCATION_ADDRESS_KEY);

                $result[] = [
                    'country' => ArrayHelper::getValue($locationObject, self::LOCATION_COUNTRY_KEY),
                    'city' => $city,
                    'location' => $location,
                    'latitude' => $coordinates[0],
                    'longitude' => $coordinates[1],
                    'address' => empty($city) || $city === $location
                        ? $location
                        : $city . ', ' . $location
                ];
            }

            return empty($result) ? null : $result;

        } catch (RequestException $e) {
            Yii::error('Geocoder API error: ' . $e->getMessage());
            return null;
        }
    }
}