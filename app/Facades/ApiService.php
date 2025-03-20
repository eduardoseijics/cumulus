<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ApiService extends Facade {
  
  protected static function getFacadeAccessor()
  {
    return 'api-service';
  }
}