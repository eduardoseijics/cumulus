<?php

namespace App\Http\Controllers;

use App\Facades\ApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\ApiController;

class OpenWeatherController extends ApiController {

  public function getCurrentDayWeatherPhrase() 
  {
    return ApiService::get('todos/1')->json();
  }
}