<?php

namespace App\Services;

use InvalidArgumentException;

class TemperatureConverter
{

  /**
   * Converts temperature between Celsius, Fahrenheit, and Kelvin.
   *
   * @param float $value Temperature to be converted.
   * @param string $from Source unit (celsius, fahrenheit, kelvin).
   * @param string $to Target unit (celsius, fahrenheit, kelvin).
   * @return float Converted temperature.
   * @throws InvalidArgumentException If the conversion is invalid.
   */
  public static function convert(float $value, string $from, string $to): float
  {
    $from = strtolower($from);
    $to   = strtolower($to);

    if ($from === $to) {
      return $value; 
    }

    return match ($from) {
      'celsius'    => self::fromCelsius($value, $to),
      'fahrenheit' => self::fromFahrenheit($value, $to),
      'kelvin'     => self::fromKelvin($value, $to),
      default      => throw new InvalidArgumentException("Invalid source unit: '$from'.")
    };
  }

  private static function fromCelsius(float $value, string $to): float
  {
    return match ($to) {
      'fahrenheit' => ($value * 9 / 5) + 32,
      'kelvin'     => $value + 273.15,
      default      => throw new InvalidArgumentException("Invalid target unit: '$to'.")
    };
  }

  private static function fromFahrenheit(float $value, string $to): float
  {
    return match ($to) {
      'celsius' => ($value - 32) * 5 / 9,
      'kelvin'  => ($value - 32) * 5 / 9 + 273.15,
      default   => throw new InvalidArgumentException("Invalid target unit: '$to'.")
    };
  }

  private static function fromKelvin(float $value, string $to): float
  {
    return match ($to) {
      'celsius'    => $value - 273.15,
      'fahrenheit' => ($value - 273.15) * 9 / 5 + 32,
      default      => throw new InvalidArgumentException("Invalid target unit: '$to'.")
    };
  }
}
