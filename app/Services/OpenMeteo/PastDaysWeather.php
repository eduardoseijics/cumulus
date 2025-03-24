<?php

namespace App\Services\OpenMeteo;

use DateTime;
use Illuminate\Http\Request;
use App\Services\OpenMeteo\OpenMeteoApi;
use App\Services\OpenMeteo\OpenMeteoData;
use App\Services\OpenMeteo\WeatherSummary;
use App\Services\OpenMeteo\WeatherCodeManager;

class PastDaysWeather {

  /**
   * 
   * @param Request $request
   * @return array
   */
  public function getYesterdayWeather($arrCity)
  {
    $params              = [];
    $params['latitude']  = $arrCity['latitude'];
    $params['longitude'] = $arrCity['longitude'];
    $params['timezone']  = $arrCity['timezone'];
    $params['daily']     = 'weather_code,temperature_2m_max,temperature_2m_min';
    $params['past_days'] = 1;
    
    $weatherData = $this->getWeatherData($params);

    // Mapping only yesterday data
    $yesterdayData = array_map(function($weatherData) {
      if (isset($weatherData[0])) {
          return $weatherData[0];
      } 
      
      return null;      
    }, $weatherData['daily']);

    $data = DateTime::createFromFormat('Y-m-d', $yesterdayData['time']);  
    $formattedDate = $data->format('d/m/Y');

    return [
      'cidade'                => $arrCity['name'],
      'data'                  => $formattedDate,
      'temperatura_minima_2m' => $yesterdayData['temperature_2m_min'],
      'temperatura_maxima_2m' => $yesterdayData['temperature_2m_max'],
    ];
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