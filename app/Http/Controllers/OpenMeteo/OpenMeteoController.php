<?php

namespace App\Http\Controllers\OpenMeteo;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller; 
use Symfony\Component\HttpFoundation\Response;

class OpenMeteoController extends Controller {

  public function getCurrentDayWeatherPhrase(Request $request) : Response
  {
    $obCurrentDayWeather = new CurrentDayWeatherController;
    $phrase = $obCurrentDayWeather->getDayPhrase($request);
    return response()->json(['phrase' => $phrase], Response::HTTP_OK);
  }

  public function getWeatherForNextSevenDays() {
    return $this->getWeatherForNextDays(7);
  }

  public function getWeatherForNextDays($days = 7) 
  {
    
  }

  public function getYesterdayWeather()
  {
    return '';
  }
}