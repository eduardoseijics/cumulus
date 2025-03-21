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
   * Get a descriptive weather phrase for the current day.
   */
  Route::get('dia-atual', 'getCurrentDayWeatherPhrase');

  /**
   * Get the current weather conditions.
   */
  Route::get('clima-atual', 'getCurrentWeather');

  /**
   * Get the weather forecast for the next seven days.
   */
  Route::get('proximos-sete-dias', 'getWeatherForNextSevenDays');

  /**
   * Get the average temperature for yesterday.
   */
  Route::get('temperatura-media-ontem', 'getYesterdayWeather');

  /**
   * Convert temperature units.
   */
  Route::get('converter-temperatura', 'getCurrentDayWeather');

  /**
   * Get sunrise and sunset times for the current day.
   */
  Route::get('nascer-por-do-sol', 'getCurrentDayWeather');

  /**
   * Get the probability of rain for the current day.
   */
  Route::get('previsao-chuva', 'getCurrentDayWeather');

  /**
   * Compare yesterday's and today's temperature.
   */
  Route::get('temperatura-ontem-hoje', 'getCurrentDayWeather');
});