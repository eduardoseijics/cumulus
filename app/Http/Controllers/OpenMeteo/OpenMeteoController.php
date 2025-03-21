<?php

namespace App\Http\Controllers\OpenMeteo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\OpenMeteo\ForecastForDays;
use App\Services\OpenMeteo\CurrentDayWeather;
use Symfony\Component\HttpFoundation\Response;

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
        'latitude' => 'required',
        'longitude' => 'required',
      ]);

      $phrase = (new CurrentDayWeather)->getTodayPhrase($request);
      
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
        'latitude' => 'required',
        'longitude' => 'required',
      ]);

      $response = (new CurrentDayWeather)->getCurrentWeatherInfo($request);

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
    $request->validate([
      'latitude' => 'required',
      'longitude' => 'required'
    ]);
    
    try {
      $obForecastForDaysService = new ForecastForDays;
      $response = $obForecastForDaysService->getWeatherForNextSevenDays($request);
      return response()->json($response, Response::HTTP_OK);
    } catch (\Throwable $th) {
      return response()->json(['error' => $th->getMessage()]);
    }
  }
}
