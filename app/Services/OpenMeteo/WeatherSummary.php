<?php

namespace App\Services\OpenMeteo;

class WeatherSummary {

  public static function generate(int $weatherCode, string $temperature, string $humidity): string
  {
    $weatherDescription = WeatherCodeManager::getDescription($weatherCode);
    $translatedWeather = WeatherTranslator::translate($weatherDescription);

    $extraMessage = self::getWeatherMessage($weatherCode);

    return "Hoje está " . $translatedWeather . ", com " . $temperature . " e umidade de " . $humidity . ". " . $extraMessage;
  }
  
  /**
   *
   * @param int $weatherCode Weather code.
   * @return string Extra message.
   */
  private static function getWeatherMessage(int $weatherCode): string
  {
    $messages = [
      0   => "O céu está completamente limpo. Aproveite o dia!",
      1   => "O céu está quase sem nuvens. Aproveite o dia!",
      2   => "O céu está principalmente limpo.",
      3   => "O céu está parcialmente nublado.",
      45  => "Cuidado! Há névoa no ar.",
      48  => "Atenção! Névoa densa e formação de gelo.",
      51  => "Pode garoar levemente ao longo do dia.",
      53  => "Chuva leve intermitente. Melhor levar um guarda-chuva.",
      55  => "Garoa intensa, fique atento ao sair.",
      56  => "Garoa congelante leve. Cuidado com as ruas escorregadias.",
      57  => "Garoa congelante intensa. Tome cuidado com o gelo na pista.",
      61  => "Chuva leve prevista. Melhor se prevenir.",
      63  => "Chuva moderada ao longo do dia.",
      65  => "Chuva forte esperada. Melhor evitar sair sem proteção.",
      66  => "Chuva congelante leve. Risco de gelo nas ruas.",
      67  => "Chuva congelante forte. Evite dirigir se possível.",
      71  => "Pequena chance de neve.",
      73  => "Neve moderada prevista. Vista-se bem!",
      75  => "Nevasca intensa chegando! Prepare-se para o frio.",
      77  => "Neve granulada no caminho. Piso pode estar escorregadio.",
      80  => "Pancadas de chuva fracas durante o dia.",
      81  => "Pancadas de chuva moderadas. Não esqueça o guarda-chuva!",
      82  => "Chuva intensa ao longo do dia. Evite sair sem necessidade.",
      85  => "Pequenas pancadas de neve esperadas.",
      86  => "Fortes pancadas de neve. Cuidado ao dirigir.",
      95  => "Tempestade leve a moderada. Fique atento!",
      96  => "Tempestade com possibilidade de granizo. Fique em segurança!",
      99  => "Tempestade intensa com granizo previsto. Evite sair de casa!"
    ];

    return $messages[$weatherCode] ?? "Tenha um ótimo dia!";
  }
}