<?php

namespace App\Services\OpenMeteo;

use Carbon\Carbon;
use App\Services\OpenMeteo\OpenMeteoApi;
use App\Services\OpenMeteo\OpenMeteoData;
use App\Services\OpenMeteo\WeatherSummary;
use App\Services\OpenMeteo\WeatherCodeManager;

class CurrentDayWeather
{
  /**
   * Fetch weather data with dynamic parameters
   *
   * @param array $params Request parameters
   * @return array Weather data
   */
  private function fetchWeatherData(array $params): array
  {
    $queryString = http_build_query($params);
    $response = OpenMeteoApi::get($queryString);
    if (empty($response) || !is_array($response)) {
      throw new \RuntimeException('Erro na requisição, tente novamente mais tarde.');
    }

    return $response;
  }

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

    $response = $this->fetchWeatherData($params);
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

    $response = $this->fetchWeatherData($params);
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
    $response = $this->fetchWeatherData($params);

    $sunrise = $response['daily']['sunrise'][0];
    $sunset = $response['daily']['sunset'][0];

    $sunrise = $this->extractTimeFromString($sunrise);
    $sunset = $this->extractTimeFromString($sunset);

    return [
      'cidade'        => $arrCity['name'],
      'nascer_do_sol' => $sunrise ?? 'N/A',
      'por_do_sol'    => $sunset ?? 'N/A'
    ];
  }

  /**
   * Convert time from string
   */
  public function extractTimeFromString($dateString): string
  {
    $date = Carbon::parse($dateString);

    return $date->format('H:i');
}
}