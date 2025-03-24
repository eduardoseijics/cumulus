<?php

namespace App\Services\OpenMeteo;

use App\Services\DateService;
use App\Services\OpenMeteo\OpenMeteoApi;
use App\Services\OpenMeteo\OpenMeteoData;
use App\Services\OpenMeteo\WeatherSummary;
use App\Services\OpenMeteo\WeatherCodeManager;

class CurrentDayWeather
{

  /**
   * Get current weather information
   *
   * @param array $arrCity City data (latitude, longitude, name)
   * @return array Current weather information
   */
  public function getCurrentWeatherInfo(array $arrCity): ?array
  {
    $params = [
      'latitude'  => $arrCity['latitude'],
      'longitude' => $arrCity['longitude'],
      'current'   => 'temperature_2m,relative_humidity_2m,weather_code'
    ];

    $response = OpenMeteoApi::fetchWeatherData($params);
    $weatherData = new OpenMeteoData($response);

    return [
      'temperature' => $weatherData->getCurrentTemperature(),
      'humidity'    => $weatherData->getCurrentHumidity(),
      'description' => WeatherCodeManager::getDescription($weatherData->getCurrentWeatherCode()),
      'icon'        => WeatherSummary::getWeatherIcon($weatherData->getCurrentWeatherCode())
    ];
  }

  /**
   * Generate today's weather summary phrase
   *
   * @param array $arrCity City data
   * @return string Weather phrase
   */
  public function getTodayPhrase(array $arrCity): string
  {
    $params = [
      'latitude'  => $arrCity['latitude'],
      'longitude' => $arrCity['longitude'],
      'current'   => 'temperature_2m,relative_humidity_2m,weather_code'
    ];

    $response = OpenMeteoApi::fetchWeatherData($params);
    $weatherData = new OpenMeteoData($response);

    return WeatherSummary::generate(
      $weatherData->getCurrentWeatherCode(),
      $weatherData->getCurrentTemperature(),
      $weatherData->getCurrentHumidity(),
      $arrCity['name']
    );
  }

  /**
   * Get sunrise and sunset times
   *
   * @param array $arrCity City data
   * @return array Sunrise and sunset times
   */
  public function getSunriseAndSunset(array $arrCity): array
  {
    $params = [
      'latitude'  => $arrCity['latitude'],
      'longitude' => $arrCity['longitude'],
      'timezone'  => $arrCity['timezone'],
      'daily'     => 'sunrise,sunset'
    ];
    $response = OpenMeteoApi::fetchWeatherData($params);

    $sunrise = $response['daily']['sunrise'][0];
    $sunset  = $response['daily']['sunset'][0];

    $sunrise = DateService::extractTimeFromString($sunrise);
    $sunset  = DateService::extractTimeFromString($sunset);

    return [
      'cidade'        => $arrCity['name'],
      'nascer_do_sol' => $sunrise ?? 'N/A',
      'por_do_sol'    => $sunset ?? 'N/A'
    ];
  }

  public function getTemperatureDifference($arrCity)
  {
    $params = [
      'latitude'  => $arrCity['latitude'],
      'longitude' => $arrCity['longitude'],
      'timezone'  => $arrCity['timezone'],
      'daily'     => 'temperature_2m_max',
      'past_days' => 1
    ];

    $response = OpenMeteoApi::fetchWeatherData($params);
    return $this->getTemperatureComparison($response['daily'], $arrCity['name']);
  }

  /**
   * @param array $temperatureData
   * @param string $cityName
   * @return array
   */
  public function getTemperatureComparison(array $temperatureData, string $cityName): array
  {
    $temperatures = array_slice($temperatureData['temperature_2m_max'], -2);

    $comparisonResult = $this->compareTemperatures($temperatures[1], $temperatures[0]);

    return [
      'cidade'     => $cityName,
      'ontem'      => $temperatures[0] . "°C",
      'hoje'       => $temperatures[1] . "°C",
      'comparacao' => $comparisonResult
    ];
  }

  /**
   * @param float $todayMax
   * @param float $yesterdayMax
   * @return string Phrase telling which one is hotter
   */
  private function compareTemperatures(float $todayMax, float $yesterdayMax): string
  {
    if($todayMax > $yesterdayMax) {
      return "Hoje está mais quente que ontem.";
    } 
    
    if($yesterdayMax > $todayMax) {
      return "Ontem esteve mais quente que hoje.";
    }

    return "Hoje e ontem tiveram a mesma temperatura máxima.";
  }
}
