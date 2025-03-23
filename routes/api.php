<?php

use App\Http\Controllers\OpenMeteo\OpenMeteoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(OpenMeteoController::class)->group(function() {
  /**
   * Get the weather description for the current day.
   */
  Route::get('clima/hoje/frase', 'getCurrentDayWeatherPhrase');

  /**
   * Get the current weather data.
   */
  Route::get('clima/atual', 'getCurrentWeather');

  /**
   * Get the weather forecast for the upcoming days.
   */
  Route::get('clima/proximos-7-dias', 'getWeatherForNextSevenDays');

  /**
   * Get the average temperature of yesterday.
   */
  Route::get('clima/ontem', 'getYesterdayWeather');

  /**
   * WORK IN PROGRESS
   * Convert temperature values.
   */
  Route::get('clima/conversao-temperatura', 'convertTemperature');

  /**
   * WORK IN PROGRESS
   * Get sunrise and sunset times.
   */
  Route::get('clima/nascer-por-do-sol', 'getSunriseSunset');

  /**
   * WORK IN PROGRESS
   * Get the rain forecast.
   */
  Route::get('clima/probabilidade-chuva', 'getRainForecast');

  /**
   * WORK IN PROGRESS
   * Compare yesterday's and today's temperatures.
   */
  Route::get('clima/comparar-temperatura', 'compareYesterdayTodayTemperature');
});