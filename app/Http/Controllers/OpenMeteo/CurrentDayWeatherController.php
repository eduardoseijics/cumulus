<?php

namespace App\Http\Controllers\OpenMeteo;

use App\Facades\ApiService;
use Illuminate\Http\Request;
use App\Facades\GeoCodingApi;

use App\Services\OpenMeteo\WeatherSummary;
use App\Services\OpenMeteo\OpenMeteoService;

class CurrentDayWeatherController {
  
  public function getDayPhrase(Request $request)
  {
    $params = $request->all();

    if (
      empty($params) ||
      (!isset($params['latitude']) || !isset($params['longitude'])) 
      && !isset($params['city'])
      ) return false;

    $queryString = http_build_query($params);
    
    // Chamando o serviço com a URL construída
    $response = ApiService::get('?' . $queryString)->json();

    if(empty($response) || !is_array($response)) return [];
    
    
    $obOpenMeteo = new OpenMeteoService($response);
    $weatherCode = $obOpenMeteo->getWeatherCode();
    $temperature = $obOpenMeteo->getCurrentTemperature();
    $humidity    = $obOpenMeteo->getHumidity();
    
    return WeatherSummary::generate($weatherCode, $temperature, $humidity);
  }
}