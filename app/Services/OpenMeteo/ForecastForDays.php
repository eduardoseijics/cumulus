<?php

namespace App\Services\OpenMeteo;

use App\Services\OpenMeteo\OpenMeteoApi;

class ForecastForDays {

  /**
   * @return array Formatted weather data from the next seven days
   */
  public function getWeatherForNextSevenDays($params): array
  {
    $params['daily'] = 'temperature_2m_max,temperature_2m_min,precipitation_sum,weathercode';
    $queryString = http_build_query($params);

    $response = OpenMeteoApi::get($queryString);

    return $this->formatWeatherData($response['daily']);
  }

  /**
   * @param array $dailyData
   * @return array Formated weather data
   */
  private function formatWeatherData(array $dailyData): array
  {
    if(!is_array($dailyData)) return [];

    $formattedWeatherData = [];

    foreach ($dailyData['time'] as $index => $date) {
      $formattedWeatherData[] = [
        'date' => $date,
        'temperature' => [
          'max' => $dailyData['temperature_2m_max'][$index],
          'min' => $dailyData['temperature_2m_min'][$index]
        ],
        'precipitation' => $dailyData['precipitation_sum'][$index],
        'weather' => WeatherCodeManager::getDescription($dailyData['weathercode'][$index]),
      ];
    }

    return $formattedWeatherData;
  }
}
