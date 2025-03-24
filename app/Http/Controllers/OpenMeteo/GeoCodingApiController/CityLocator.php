<?php

namespace App\Http\Controllers\OpenMeteo\GeoCodingApiController;

use App\Services\GeoCoding\GeoCodingApi;

class CityLocator {

  /**
   * Returns an array with city information
   * @param string $cityName
   * @return array
   */
  public function find(string $cityName): array
  {
    $response = GeoCodingApi::get('name='.$cityName.'&count=1&language=en&format=json');
    if(!isset($response['results'])) throw new \Exception('Cidade não encontrada.');    
    return current($response['results']);
  }
}