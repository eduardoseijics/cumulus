<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class OpenMeteoApiFacade extends Facade {
  
  protected static function getFacadeAccessor()
  {
    return 'open-meteo-api-service';
  }
}