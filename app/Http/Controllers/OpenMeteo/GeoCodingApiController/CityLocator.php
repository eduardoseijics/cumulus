<?php

namespace App\Http\Controllers\OpenMeteo\GeoCodingApiController;

use App\Services\GeoCoding\GeoCodingApi;


class CityLocator {

  private string $cityName;

  /**
   * 
   * Returns city name
   * @return string
   */
  public function getCityName(): string
  {
    return $this->cityName;
  }

  /**
   * 
   */
  public function find(string $cityName): array
  {
    $response = GeoCodingApi::get('name='.$cityName.'&count=1&language=en&format=json');
    if(!isset($response['results'])) throw new \Exception('Cidade não encontrada.');    
    return current($response['results']);
  }
}