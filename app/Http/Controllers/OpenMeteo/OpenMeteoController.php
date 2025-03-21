<?php

namespace App\Http\Controllers\OpenMeteo;

use App\Facades\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\OpenMeteo\ForecastForDays;
use Symfony\Component\HttpFoundation\Response;

class OpenMeteoController extends Controller
{

  public function getCurrentDayWeatherPhrase(Request $request): Response
  {

    try {
      $obCurrentDayWeather = new CurrentDayWeatherController;
      $phrase = $obCurrentDayWeather->getTodayPhrase($request);
      return response()->json(['phrase' => $phrase], Response::HTTP_OK);
    } catch (\Throwable $th) {
      return response()->json(['error' => $th->getMessage()]);
    }
  }

  public function getCurrentWeather(Request $request)
  {
    try {
      $obCurrentDayWeather = new CurrentDayWeatherController;
      $response = $obCurrentDayWeather->getCurrentWeatherInfo($request);

      return response()->json($response, Response::HTTP_OK);
    } catch (\Throwable $th) {
      return response()->json(['error' => $th->getMessage()]);
    }
  }

  public function getWeatherForNextSevenDays(Request $request)
  {
    try {
      $obForecastForDaysService = new ForecastForDays;
      $response = $obForecastForDaysService->getWeatherForNextSevenDays($request);
      return response()->json($response, Response::HTTP_OK);
    } catch (\Throwable $th) {
      return response()->json(['error' => $th->getMessage()]);
    }
  }
}
