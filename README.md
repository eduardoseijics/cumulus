# OpenMeteo Weather API Wrapper

Este projeto fornece uma interface para consumir a API OpenMeteo, permitindo obter dados meteorológicos atuais de forma simplificada.

## Requisitos

- PHP 8.0+
- Composer
- Laravel 10+

## Instalação

1. Clone este repositório:
   ```sh
   git clone https://github.com/seu-usuario/seu-repositorio.git
   ```

2. Acesse o diretório do projeto:
   ```sh
   cd seu-repositorio
   ```

3. Instale as dependências do Composer:
   ```sh
   composer install
   ```

4. Inicie o projeto
   ```sh
   php artisan serve
   ```

## Uso

### Obter Clima Atual

A API permite buscar o clima atual com base nas coordenadas (latitude e longitude). Para isso, envie uma requisição `GET` para:

```sh
GET /api/clima/hoje/frase?latitude={latitude}&longitude={longitude}&current=temperature_2m,weather_code,relative_humidity_2m
```

**Exemplo de resposta:**
```json
{
    "phrase": "Hoje está Parcialmente nublado, com 31.1°C e umidade de 44%. O céu está principalmente limpo."
}
```

## Tratamento de Erros

Caso os parâmetros obrigatórios não sejam informados, a API retornará um erro:

```json
{
  "error": "Latitude ou longitude não informadas."
}
```

Caso ocorra algum erro na requisição à API OpenMeteo, a resposta será:

```json
{
  "error": "Houve um erro na requisição, tente novamente mais tarde."
}
```

## Contribuição

Sinta-se à vontade para contribuir com melhorias! Faça um fork do projeto, crie uma branch com suas alterações e envie um pull request.

## Licença

Este projeto está sob a licença MIT.

