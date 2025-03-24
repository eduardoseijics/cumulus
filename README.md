# OpenMeteo Weather API Wrapper

Este projeto fornece uma interface para consumir a API OpenMeteo, permitindo obter dados meteorológicos atuais de forma simplificada.

## Requisitos

- PHP 8.0+
- Composer
- Laravel 10+

## Instalação

1. Clone este repositório:
   ```sh
   git clone https://github.com/eduardoseijics/cumulus.git
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


### Collection

As rotas e exemplos da api estão disponível em https://app.swaggerhub.com/apis/EduardoSeijicastrosilva/cumulus-api/1.0.0
### Obter Clima Atual

A API permite buscar o clima atual com base nas coordenadas (latitude e longitude). Para isso, envie uma requisição `GET` para:

```sh
GET /api/clima/hoje/frase?city=São Paulo
```

**Exemplo de resposta:**
```json
{
    "phrase": "Hoje está Encoberto em São Paulo, com 26.2°C e umidade de 68%. O céu está parcialmente nublado."
}
```

## Tratamento de Erros

Caso os parâmetros obrigatórios não sejam informados, a API retornará um erro:

```json
{
  "error": "Cidade não encontrada."
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

