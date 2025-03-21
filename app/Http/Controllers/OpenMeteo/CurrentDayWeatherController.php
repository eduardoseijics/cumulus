<?php

namespace App\Http\Controllers\OpenMeteo;
use App\Facades\ApiService;
use App\Services\OpenMeteo\OpenMeteoApi;
use Illuminate\Http\Request;
use App\Services\OpenMeteo\WeatherSummary;
use App\Services\OpenMeteo\OpenMeteoData;
use App\Services\OpenMeteo\WeatherCodeManager;

class CurrentDayWeatherController {

  private function getWeatherData(array $params)
  {
    if(
      empty($params) ||
      !isset($params['latitude']) || !isset($params['longitude'])
    ) return [];
    
    $params['current'] .= ',weather_code';
    $queryString = http_build_query($params);

    $response = OpenMeteoApi::fetchWeatherData($queryString);

    if(empty($response) || !is_array($response)) return [];

    $obOpenMeteo = new OpenMeteoData($response);
    return [
      'temperature' => $obOpenMeteo->getCurrentTemperature(),
      'humidity'    => $obOpenMeteo->getCurrentHumidity(),
      'weatherCode' => $obOpenMeteo->getCurrentWeatherCode(),
      'icon'        => WeatherSummary::getWeatherIcon($response['current']['weather_code'])
    ];
  }

  public function getCurrentWeatherInfo(Request $request)
  {
    $params = $request->all();

    $weatherData = $this->getWeatherData($params);

    if(empty($weatherData)) {
      return [];
    }

    $description = WeatherCodeManager::getDescription($weatherData['weatherCode']);

    return [
      'temperature' => $weatherData['temperature'],
      'humidity'    => $weatherData['humidity'],
      'description' => $description,
      'icon'        => WeatherSummary::getWeatherIcon($weatherData['weatherCode'])
    ];
  }

  public function getTodayPhrase(Request $request)
  {
    $params = $request->all();

    $weatherData = $this->getWeatherData($params);

    if(empty($weatherData)) {
      return [];
    }

    return WeatherSummary::generate($weatherData['weatherCode'], $weatherData['temperature'], $weatherData['humidity']);
  }
}