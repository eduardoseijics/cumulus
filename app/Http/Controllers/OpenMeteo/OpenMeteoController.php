<?php

namespace App\Http\Controllers\OpenMeteo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\TemperatureConverter;
use App\Services\OpenMeteo\RainForecast;
use App\Services\OpenMeteo\ForecastForDays;
use App\Services\OpenMeteo\PastDaysWeather;
use App\Services\OpenMeteo\CurrentDayWeather;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\OpenMeteo\GeoCodingApiController\CityLocator;

/**
 * @author Eduardo Seiji
 */
class OpenMeteoController extends Controller
{

  /**
   * Handle request execution and exception handling.
   */
  private function handleRequest(callable $callback): Response
  {
    try {
      return response()->json($callback(), Response::HTTP_OK);
    } catch (\Throwable $th) {
      return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  /**
   * Validate and retrieve request data.
   */
  private function validateAndGetData(Request $request, array $rules): array
  {
    $request->validate($rules);
    return $request->all();
  }

  /**
   * Retrieve city coordinates using CityLocator.
   */
  private function getCityCoordinates(string $city): array
  {
    return (new CityLocator)->find($city);
  }

  /**
   * Get current weather phrase based on city.
   */
  public function getCurrentDayWeatherPhrase(Request $request): Response
  {
    return $this->handleRequest(function () use ($request) {
      $data = $this->validateAndGetData($request, ['city' => 'required']);
      $arrCity = $this->getCityCoordinates($data['city']);
      return ['phrase' => (new CurrentDayWeather)->getTodayPhrase($arrCity)];
    });
  }

  /**
   * Get current weather information.
   */
  public function getCurrentWeather(Request $request): Response
  {
    return $this->handleRequest(function () use ($request) {
      $data = $this->validateAndGetData($request, ['city' => 'required']);
      $arrCity = $this->getCityCoordinates($data['city']);
      return (new CurrentDayWeather)->getCurrentWeatherInfo($arrCity);
    });
  }

  /**
   * Get yesterday's weather data.
   */
  public function getYesterdayWeather(Request $request): Response
  {
    return $this->handleRequest(function () use ($request) {
      $data = $this->validateAndGetData($request, ['city' => 'required']);
      $arrCity = $this->getCityCoordinates($data['city']);
      return (new PastDaysWeather)->getYesterdayWeather($arrCity);
    });
  }

  /**
   * Get weather forecast for the next seven days.
   */
  public function getWeatherForNextSevenDays(Request $request): Response
  {
    return $this->handleRequest(function () use ($request) {
      $data = $this->validateAndGetData($request, ['city' => 'required']);
      $arrCity = $this->getCityCoordinates($data['city']);
      return (new ForecastForDays)->getWeatherForNextSevenDays($arrCity);
    });
  }

  /**
   * Return converted temperature based on the user's request.
   */
  public function getConvertedTemperature(Request $request): Response
  {
    return $this->handleRequest(function () use ($request) {
      $rules = [
        'temperature' => 'required|numeric',
        'from'        => 'required|string|in:celsius,fahrenheit,kelvin',
        'to'          => 'required|string|in:celsius,fahrenheit,kelvin'
      ];
      $data = $this->validateAndGetData($request, $rules);

      // Convert temperature
      $convertedTemperature = TemperatureConverter::convert($data['temperature'], $data['from'], $data['to']);

      return [
        'from_unit'       => $data['from'],
        'to_unit'         => $data['to'],
        'original_temp'   => $data['temperature'],
        'converted_temp'  => $convertedTemperature
      ];
    });
  }

  /**
   * Get sunrise and sunset times for a specific city.
   */
  public function getSunriseSunset(Request $request): Response
  {
    return $this->handleRequest(function () use ($request) {
      $data = $this->validateAndGetData($request, ['city' => 'required']);
      $arrCity = $this->getCityCoordinates($data['city']);
      return (new CurrentDayWeather)->getSunriseAndSunset($arrCity);
    });
  }

  /**
   * Get rain forecast for the next 3 days.
   */
  public function getRainForecast(Request $request): Response
  {
    return $this->handleRequest(function () use ($request) {
      $data = $this->validateAndGetData($request, ['city' => 'required']);
      $arrCity = $this->getCityCoordinates($data['city']);
      return (new RainForecast)->getRainForecast($arrCity);
    });
  }

  /**
   * Compare temperatures between yesterday and today.
   */
  public function compareYesterdayTodayTemperature(Request $request): Response
  {
    return $this->handleRequest(function () use ($request) {
      $data = $this->validateAndGetData($request, ['city' => 'required']);
      $arrCity = $this->getCityCoordinates($data['city']);
      return (new CurrentDayWeather)->getTemperatureDifference($arrCity);
    });
  }
}
