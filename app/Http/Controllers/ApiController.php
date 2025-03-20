<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller{

  public function get() : JsonResponse {
    return response()->json([], Response::HTTP_OK);
  }

  public function post() {}

  public function put() {}

  public function delet() {}

  private function send() {
    
  }
}