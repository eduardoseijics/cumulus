<?php

namespace App\Services\OpenMeteo;

use App\Services\OpenMeteo\OpenMeteoApi;
use Illuminate\Http\Request;
use App\Services\OpenMeteo\WeatherSummary;
use App\Services\OpenMeteo\OpenMeteoData;
use App\Services\OpenMeteo\WeatherCodeManager;

use function Psy\debug;

class CurrentDayWeather {

  /**
   * 
   * @param array $params Information from request
   * @return array Weather data
   */
  private function getWeatherData(array $params)
  {
    $params['current'] = 'temperature_2m,relative_humidity_2m,weather_code';
    $queryString = http_build_query($params);
    $response = OpenMeteoApi::get($queryString);
    
    if(empty($response) || !is_array($response)) throw new \RuntimeException('Houve um erro na requisição, tente novamente mais tarde');

    $obOpenMeteoData = new OpenMeteoData($response);
    return [
      'temperature' => $obOpenMeteoData->getCurrentTemperature(),
      'humidity'    => $obOpenMeteoData->getCurrentHumidity(),
      'weatherCode' => $obOpenMeteoData->getCurrentWeatherCode(),
      'icon'        => WeatherSummary::getWeatherIcon($response['current']['weather_code'])
    ];
  }

  /**
   * 
   * @param Request $request
   * @return array|null Current weather information
   */
  public function getCurrentWeatherInfo(Request $request): ?array
  {
    $request->validate([
      'latitude' => 'required',
      'longitude' => 'required'
    ]);

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

  /**
   * 
   * @param Request $request
   * @return string Today phrase talking about weather
   */
  public function getTodayPhrase(array $arrCity): string
  {
    $data = [];
    $data['latitude'] = $arrCity['latitude'];
    $data['longitude'] = $arrCity['longitude'];
    $weatherData = $this->getWeatherData($data);

    if(empty($weatherData)) {
      return [];
    }

    return WeatherSummary::generate($weatherData['weatherCode'], $weatherData['temperature'], $weatherData['humidity'], $arrCity['name']);
  }
}