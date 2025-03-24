<?php

namespace App\Services\OpenMeteo;

use RuntimeException;
use App\Facades\OpenMeteoApiFacade;

use function Psy\debug;

/**
 * 
 * @author Eduardo Seiji
 */
class OpenMeteoApi {
  
  /**
   * Makes the request to get weather data based on the query string.
   *
   * @param string $queryString
   * @return array
   * @throws RuntimeException
   */
  public static function get($queryString): array
  {
    $response = OpenMeteoApiFacade::get('?' . $queryString)->json();
    if(empty($response) || !isset($response)) {
      throw new RuntimeException('Houve um erro na requisição, tente novamente mais tarde');
    }

    return $response;
  }

  /**
   * Fetch weather data with dynamic parameters
   *
   * @param array $params Request parameters
   * @return array Weather data
   */
  public static function fetchWeatherData(array $params): array
  {
    $queryString = http_build_query($params);
    $response = OpenMeteoApi::get($queryString);
    if(empty($response) || !is_array($response)) {
      throw new \RuntimeException('Erro na requisição, tente novamente mais tarde.');
    }

    return $response;
  }
}
