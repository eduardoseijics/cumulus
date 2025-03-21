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
  Route::get('dia-atual', 'getCurrentDayWeatherPhrase');
  Route::get('clima-atual', 'getCurrentWeather');
  Route::get('proximos-sete-dias', 'getWeatherForNextSevenDays');
  Route::get('temperatura-media-ontem', 'getYesterdayWeather');
  Route::get('converter-temperatura', 'getCurrentDayWeather');
  Route::get('nascer-por-do-sol', 'getCurrentDayWeather');
  Route::get('previsao-chuva', 'getCurrentDayWeather');
  Route::get('temperatura-ontem-hoje', 'getCurrentDayWeather');
});