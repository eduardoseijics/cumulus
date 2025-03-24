<?php

namespace App\Services;

use Carbon\Carbon;

class DateService {
  /**
   * Formats a date to the 'dddd, D de MMMM de YYYY' format in Portuguese.
   * Example: "Monday, 24 of March of 2025"
   *
   * @param string $dateString The date string to be formatted.
   * @return string The formatted date.
   */
  public static function formatDateForPortuguese($dateString): string
  {
    $date = Carbon::parse($dateString);
    return ucfirst($date->locale('pt_BR')->isoFormat('dddd'));
  }

  /**
   * Extracts and returns the time from a date string.
   * Example: "09:26"
   *
   * @param string $dateString The date string to be analyzed.
   * @return string The extracted time in the 'H:i' format.
   */
  public static function extractTimeFromString($dateString): string
  {
    $date = Carbon::parse($dateString);
    return $date->format('H:i');
  }

  /**
   * Converts a date string to the 'd/m/Y' format.
   * Example: "24/03/2025"
   *
   * @param string $dateString The date string to be converted.
   * @return string The date converted to the 'd/m/Y' format.
   */
  public static function convertToDateFormat($dateString): string
  {
    $date = Carbon::parse($dateString);
    return $date->format('d/m/Y');
  }
}
