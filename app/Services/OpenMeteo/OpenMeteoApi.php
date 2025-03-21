<?php

namespace App\Services\OpenMeteo;

use RuntimeException;
use App\Facades\OpenMeteoApiFacade;

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

    if (empty($response) || !isset($response)) {
      throw new RuntimeException('Houve um erro na requisição, tente novamente mais tarde');
    }

    return $response;
  }
}
