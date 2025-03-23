<?php

namespace App\Services\OpenMeteo;

use App\Services\OpenMeteo\OpenMeteoApi;
use Illuminate\Http\Request;
use App\Services\OpenMeteo\WeatherSummary;
use App\Services\OpenMeteo\OpenMeteoData;
use App\Services\OpenMeteo\WeatherCodeManager;

class PastDaysWeather {

  /**
   * 
   * @param Request $request
   * @return array
   */
  public function getYesterdayWeather(Request $request)
  {
    
    $params               = [];
    $params['latitude']  = $request->input('latitude');
    $params['longitude'] = $request->input('longitude');
    $params['daily']      = 'weather_code,temperature_2m_max,temperature_2m_min';
    $params['past_days']  = 1;
    
    $weatherData = $this->getWeatherData($params);
    
    if(empty($weatherData)) {
      return [];
    }

    return $weatherData;
  }

  /**
   * 
   * @param array $params Information from request
   * @return array Weather data
   */
  private function getWeatherData(array $params)
  {
    $queryString = http_build_query($params);
    
    $response = OpenMeteoApi::get($queryString);
    if(empty($response) || !is_array($response)) throw new \RuntimeException('Houve um erro na requisição, tente novamente mais tarde dasda');


    $obOpenMeteoData = new OpenMeteoData($response);
    return [
      'daily' => $obOpenMeteoData->getDaily()
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
}