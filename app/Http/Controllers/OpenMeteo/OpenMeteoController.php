<?php

namespace App\Http\Controllers\OpenMeteo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\OpenMeteo\ForecastForDays;
use App\Services\OpenMeteo\PastDaysWeather;
use App\Services\OpenMeteo\CurrentDayWeather;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\OpenMeteo\GeoCodingApiController\CityLocator;
use Exception;

/**
 * @author Eduardo Seiji
 */
class OpenMeteoController extends Controller {

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
   * Validate request data and return processed input.
   */
  private function validateAndGetData(Request $request, array $rules): array
  {
    $request->validate($rules);
    return $request->all();
  }

  /**
   * Retrieve city coordinates from CityLocator.
   */
  private function getCityCoordinates(string $city): array
  {
    return (new CityLocator)->find($city);
  }

  /**
   * Get current day phrase with weather information.
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
   * Get yesterday's weather information.
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
}
