<?php

namespace App\Services\OpenMeteo;

/**
 * 
 * @author Eduardo Seiji
 */
class OpenMeteoService {

  private array $weatherData;

  public function __construct(array $weatherData)
  {
      $this->weatherData = $weatherData;
  }

  public function getCurrentTemperature(): ?string
  {
    $temperature = $this->weatherData['current']['temperature_2m'] ?? null;
    $unit        = $this->weatherData['current_units']['temperature_2m'] ?? '';

    return $temperature !== null ? "{$temperature}{$unit}" : null;
  }

  public function getHumidity()
  {
    $humidity          =  $this->weatherData['current']['relative_humidity_2m'] ?? null;
    $unit              =  $this->weatherData['current_units']['relative_humidity_2m'] ?? '';

    return $humidity !== null ? "{$humidity}{$unit}" : null;
  }

  public function getWeatherCode()
  {
    return $this->weatherData['current']['weather_code'] ?? null;
  }
}