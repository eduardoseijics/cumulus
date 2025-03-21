<?php

namespace App\Services\OpenMeteo;

use App\Services\OpenMeteo\OpenMeteoApi;

class ForecastForDays {

  public function getWeatherForNextSevenDays($request)
  {
    $params = $request->all();

    // Validações
    $this->validateCoordinates($params);

    // Configura os parâmetros para a requisição
    $params['daily'] = 'temperature_2m_max,temperature_2m_min,precipitation_sum,weathercode';
    $queryString = http_build_query($params);

    // Faz a requisição e formata a resposta
    $response = OpenMeteoApi::fetchWeatherData($queryString);

    // Formata os dados para resposta
    return $this->formatWeatherData($response['daily']);
  }

  private function validateCoordinates($params)
  {
    if (empty($params['latitude']) || empty($params['longitude'])) {
      throw new \InvalidArgumentException('Latitude ou longitude não informadas.');
    }
  }



  private function formatWeatherData($dailyData)
  {
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
