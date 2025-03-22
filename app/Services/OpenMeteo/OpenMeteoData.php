<?php

namespace App\Services\OpenMeteo;

/**
 * 
 * @author Eduardo Seiji
 */
class OpenMeteoData {

  private array $weatherData;

  /**
   * @param array $weatherData Response from open-meteo api
   */
  public function __construct(array $weatherData)
  {
      $this->weatherData = $weatherData;
  }

  /**
   * Returns currents temperature with its unit
   * @return string|null Current temperature
   */
  public function getCurrentTemperature(): ?string
  {
    $temperature = $this->weatherData['current']['temperature_2m'] ?? null;
    $unit        = $this->weatherData['current_units']['temperature_2m'] ?? '';

    return $temperature !== null ? "{$temperature}{$unit}" : null;
  }

  /**
   * @return string|null Current humidity
   */
  public function getCurrentHumidity(): ?string
  {
    $humidity = $this->weatherData['current']['relative_humidity_2m'] ?? null;
    $unit     = $this->weatherData['current_units']['relative_humidity_2m'] ?? '';

    return $humidity !== null ? "{$humidity}{$unit}" : null;
  }

  /**
   * 
   * @return int|null Current weather code
   */
  public function getCurrentWeatherCode()
  {
    return $this->weatherData['current']['weather_code'] ?? null;
  }

  /**
   * 
   * @return array|null Daily
   */
  public function getDaily()
  {
    return $this->weatherData['daily'] ?? null;
  }
}