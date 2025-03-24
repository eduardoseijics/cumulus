<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class GeoCodingApiFacade extends Facade {
  
  protected static function getFacadeAccessor()
  {
    return 'geocoding-api-service';
  }
}