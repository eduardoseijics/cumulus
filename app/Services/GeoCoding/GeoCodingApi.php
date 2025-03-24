<?php

namespace App\Services\GeoCoding;

use RuntimeException;
use App\Facades\GeoCodingApiFacade;

/**
 * 
 * @author Eduardo Seiji
 */
class GeoCodingApi {
  /**
   * Makes the request to get weather data based on the query string.
   *
   * @param string $queryString
   * @return array
   * @throws RuntimeException
   */
  public static function get($queryString): array
  {
    $response = GeoCodingApiFacade::get('?' . $queryString)->json();
    if (empty($response) || !isset($response)) {
      throw new RuntimeException('333Houve um erro na requisição, tente novamente mais tarde');
    }
    return $response;
  }
}
