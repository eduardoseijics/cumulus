<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider {

  public function register()
  {
    $this->app->bind('open-meteo-api-service', function() {
      return Http::withOptions([
        'base_uri' => 'https://api.open-meteo.com/v1/forecast/'
      ])->withHeaders([
        'Content-type' => 'application/json; charset=utf-8'
      ]);
    });

    $this->app->bind('geocoding-api-service', function() {
      return Http::withOptions([
        'base_uri' => 'https://geocoding-api.open-meteo.com/v1/'
      ])->withHeaders([
        'Content-type' => 'application/json; charset=utf-8'
      ]);
    });
  }
}