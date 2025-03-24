<?php

namespace App\Services\OpenMeteo;

use App\Services\DateService;

class RainForecast
{

  public function getRainForecast($arrCity)
  {
    // Prepare the parameters for the API request
    $params = $this->prepareApiParams($arrCity);

    // Fetch weather data from the API
    $response = OpenMeteoApi::fetchWeatherData($params);

    // Get the rainy days, forecasts, and total precipitation
    list($rainyDays, $rainyForecasts, $totalPrecipitation) = $this->getRainyDayData($response, 3);

    // Calculate the average precipitation
    $averagePrecipitation = $this->calculateAveragePrecipitation($rainyDays, $totalPrecipitation);

    // Prepare the weather forecast message
    $forecastMessage = $this->prepareForecastMessage($rainyDays, $rainyForecasts, $averagePrecipitation);

    // Return the result
    return [
      'cidade' => $arrCity['name'],
      'previsao' => $forecastMessage
    ];
  }

  /**
   * Prepare the parameters for the API request.
   */
  private function prepareApiParams($arrCity)
  {
    return [
      'latitude'  => $arrCity['latitude'],
      'longitude' => $arrCity['longitude'],
      'timezone'  => $arrCity['timezone'],
      'daily'     => 'precipitation_sum,precipitation_hours,precipitation_probability_max,weather_code'
    ];
  }

  /**
   * Extracts the rainy days, weather forecasts, and total precipitation for the given response.
   */
  private function getRainyDayData($response, $daysCount)
  {
    $rainyDays = [];
    $rainyForecasts = [];
    $totalPrecipitation = 0;

    for ($i = 0; $i < $daysCount; $i++) {
      if($response['daily']['precipitation_sum'][$i] > 0) {
        $rainyDays[]        =  DateService::formatDateForPortuguese($response['daily']['time'][$i]);
        $rainyForecasts[]   =  WeatherCodeManager::getDescription($response['daily']['weather_code'][$i]);
        $totalPrecipitation += $response['daily']['precipitation_sum'][$i];
      }
    }

    return [$rainyDays, $rainyForecasts, $totalPrecipitation];
  }

  /**
   * Calculates the average precipitation for the rainy days.
   */
  private function calculateAveragePrecipitation($rainyDays, $totalPrecipitation)
  {
    return count($rainyDays) > 0 ? $totalPrecipitation / count($rainyDays) : 0;
  }

  /**
   * Prepares the weather forecast message based on the rainy days and forecasts.
   */
  private function prepareForecastMessage($rainyDays, $rainyForecasts, $averagePrecipitation)
  {
    if(empty($rainyDays)) {
      return 'Nenhuma chuva prevista para os próximos 3 dias.';
    }

    $forecastMessage = 'Chuva prevista para os próximos 3 dias: ' . implode(', ', $rainyDays) . '. ';
    $forecastMessage .= 'Precipitação média: ' . round($averagePrecipitation, 2) . ' mm.';
    $forecastMessage .= ' Previsão do tempo: ' . implode(', ', $rainyForecasts);

    return $forecastMessage;
  }
}
