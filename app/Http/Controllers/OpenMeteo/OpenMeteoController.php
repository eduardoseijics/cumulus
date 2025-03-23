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
 * 
 * @author Eduardo Seiji
 */
class OpenMeteoController extends Controller {

  /**
   * @param Request $request
   * @return Response Current day phrase with weather informations
   */
  public function getCurrentDayWeatherPhrase(Request $request): Response
  {
    try {
      $request->validate([
        'city' => 'required'
      ]);

      $data = $request->all();
      
      $arrCity = (new CityLocator)->find($data['city']);

      $phrase = (new CurrentDayWeather)->getTodayPhrase($arrCity);
      
      return response()->json(['phrase' => $phrase], Response::HTTP_OK);
    } catch (\Throwable $th) {
      return response()->json(['error' => $th->getMessage()]);
    }
  }

  /**
   * @param Request $request
   * @return Response Current day weather informations
   */
  public function getCurrentWeather(Request $request): Response
  {
    try {
      $request->validate([
        'city' => 'required'
      ]);

      $data = $request->all();
      
      $arrCity = (new CityLocator)->find($data['city']);

      $response = (new CurrentDayWeather)->getCurrentWeatherInfo($arrCity);

      return response()->json($response, Response::HTTP_OK);
    } catch (\Throwable $th) {
      return response()->json(['error' => $th->getMessage()]);
    }
  }

  /**
   * 
   * @param Request $request
   * @return Response Yesterday Weather Information
   */
  public function getYesterdayWeather(Request $request)
  {
    try {
      $request->validate([
        'latitude' => 'required',
        'longitude' => 'required'
      ]);
      $obPastDaysWeather = new PastDaysWeather;

      $response = $obPastDaysWeather->getYesterdayWeather($request);
      return response()->json($response, Response::HTTP_OK);
    } catch (\Throwable $th) {
      return response()->json(['error' => $th->getMessage()]);
    }
  }

  /**
   * @param Request $request
   * @return Response Next seven days weather information
   */
  public function getWeatherForNextSevenDays(Request $request): Response
  {
    try {      
      $request->validate([
        'latitude' => 'required',
        'longitude' => 'required'
      ]);
      
      $obForecastForDaysService = new ForecastForDays;
      $response = $obForecastForDaysService->getWeatherForNextSevenDays($request);
      return response()->json($response, Response::HTTP_OK);
    } catch (\Throwable $th) {
      return response()->json(['error' => $th->getMessage()]);
    }
  }
}
