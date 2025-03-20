<?php

namespace App\Services\OpenMeteo;

class WeatherTranslator {

  private static array $translations = [
    "Clear sky"                           => "Céu limpo",
    "Mainly clear"                        => "Predominantemente limpo",
    "Partly cloudy"                       => "Parcialmente nublado",
    "Overcast"                            => "Encoberto",
    "Fog"                                 => "Nevoeiro",
    "Depositing rime fog"                 => "Nevoeiro congelante",
    "Drizzle : Light intensity"           => "Garoa: Intensidade leve",
    "Drizzle : Moderate intensity"        => "Garoa: Intensidade moderada",
    "Drizzle : Dense intensity"           => "Garoa: Intensidade forte",
    "Freezing Drizzle : Light intensity"  => "Garoa congelante: Intensidade leve",
    "Freezing Drizzle : Dense intensity"  => "Garoa congelante: Intensidade forte",
    "Rain : Slight intensity"             => "Chuva: Intensidade leve",
    "Rain : Moderate intensity"           => "Chuva: Intensidade moderada",
    "Rain : Heavy intensity"              => "Chuva: Intensidade forte",
    "Freezing Rain : Light intensity"     => "Chuva congelante: Intensidade leve",
    "Freezing Rain : Heavy intensity"     => "Chuva congelante: Intensidade forte",
    "Snow fall : Slight intensity"        => "Neve: Intensidade leve",
    "Snow fall : Moderate intensity"      => "Neve: Intensidade moderada",
    "Snow fall : Heavy intensity"         => "Neve: Intensidade forte",
    "Snow grains"                         => "Grãos de neve",
    "Rain showers : Slight intensity"     => "Pancadas de chuva: Intensidade leve",
    "Rain showers : Moderate intensity"   => "Pancadas de chuva: Intensidade moderada",
    "Rain showers : Violent intensity"    => "Pancadas de chuva: Intensidade forte",
    "Snow showers : Slight intensity"     => "Pancadas de neve: Intensidade leve",
    "Snow showers : Heavy intensity"      => "Pancadas de neve: Intensidade forte",
    "Thunderstorm : Slight or moderate"   => "Tempestade: Leve ou moderada",
    "Thunderstorm with slight hail"       => "Tempestade com granizo leve",
    "Thunderstorm with heavy hail"        => "Tempestade com granizo forte",
    "Unknown weather condition"           => "Condição meteorológica desconhecida"
  ];

  public static function translate(string $description): string
  {
    return self::$translations[$description] ?? $description;
  }
}