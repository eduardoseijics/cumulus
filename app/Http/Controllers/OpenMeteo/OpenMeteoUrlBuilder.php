<?php

namespace App\Services;

class OpenWeatherUrlBuilder {

  protected string $city = '';
  protected string $latitude = '';
  protected string $longitude = '';
  protected string $units = '';

  public function __construct()
  {
  }

  public function setCity(string $city): self
  {
      $this->city = $city;
      return $this;
  }

  public function setUnits(string $units): self
  {
      $this->units = $units;
      return $this;
  }

  public function setLatitude(string $latitude): self
  {
      $this->latitude = $latitude;
      return $this;
  }

  public function build(): string
  {
      $queryParams = [
      ];

      return http_build_query($queryParams);
  }
}