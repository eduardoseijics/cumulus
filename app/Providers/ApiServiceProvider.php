<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider {

  public function register()
  {
    $this->app->bind('api-service', function() {
      return Http::withOptions([
        'base_uri' => 'https://jsonplaceholder.typicode.com/'
      ])->withHeaders([
        'Content-type' => 'application/json'
      ]);
    });
  }
}