# Game Ranking API

Microsserviço backend responsável por disponibilizar rankings e métricas de jogos para integração com o ecossistema GameVerse.

![Laravel](https://img.shields.io/badge/Laravel-10-red)
![PHP](https://img.shields.io/badge/PHP-%3E%3D8.1-blue)
![SQLite](https://img.shields.io/badge/SQLite-Database-green)
![Scribe](https://img.shields.io/badge/Docs-Scribe-purple)

---

## Sobre o Projeto

Este serviço centraliza dados de engajamento de jogos e disponibiliza endpoints JSON para que um site, frontend ou outro microsserviço consiga exibir rankings dinâmicos.

O projeto contém apenas o backend da API. A interface visual do usuário final fica em outro projeto consumidor.

---

## Integrantes

* Kaiky Andrade de Oliveira
* Gabriel Henrique Lina Batista Pereira Nunes

---

## Responsabilidades do Microsserviço

* Fornecer ranking semanal, mensal e anual de jogos
* Listar os jogos mais jogados
* Consultar histórico de pontuação de um jogo
* Filtrar rankings por plataforma
* Retornar dados estatísticos em formato JSON
* Proteger as rotas da API usando JWT
* Disponibilizar documentação interativa com Scribe

---

## Tecnologias Utilizadas

* PHP >= 8.1
* Laravel 10
* SQLite
* Composer
* Laravel Scribe
* PHPUnit

---

## Estrutura do Repositório

Este repositório não está organizado como monorepo. Ele contém apenas o microsserviço backend da API de rankings de jogos.

| Serviço | Pasta | Descrição |
| ------- | ----- | --------- |
| API de Rankings de Jogos | `/` | Backend Laravel responsável pelas rotas, autenticação JWT, rankings e documentação Scribe |

Principais pastas:

| Pasta | Finalidade |
| ----- | ---------- |
| `app/Http/Controllers` | Controllers da API |
| `app/Http/Middleware` | Middleware de autenticação JWT |
| `app/Models` | Models Eloquent |
| `routes/api.php` | Rotas da API |
| `routes/web.php` | Rotas web básicas |
| `database/migrations` | Estrutura das tabelas |
| `database/seeders` | Dados iniciais para demonstração |
| `resources/views/scribe` | Documentação HTML gerada pelo Scribe |
| `public/vendor/scribe` | Assets públicos da documentação |
| `tests/Feature` | Testes dos endpoints e da documentação |

---

## Requisitos

Antes de executar o projeto, instale:

* PHP >= 8.1
* Composer
* Git
* SQLite

---

## Instalação

Clone o repositório:

```bash
git clone https://github.com/gabriellina640/api-ranking-jogos.git
cd api-ranking-jogos
```

Instale as dependências:

```bash
composer install
```

Crie o arquivo `.env`:

```bash
cp .env.example .env
```

Gere a chave da aplicação:

```bash
php artisan key:generate
```

Crie o banco SQLite local:

```bash
touch database/database.sqlite
```

No `.env`, configure:

```env
DB_CONNECTION=sqlite
```

Execute as migrations e seeders:

```bash
php artisan migrate:fresh --seed
```

---

## Variáveis de Ambiente

Principais variáveis usadas pelo projeto:

| Variável | Descrição |
| -------- | --------- |
| `APP_NAME` | Nome da aplicação |
| `APP_ENV` | Ambiente da aplicação |
| `APP_KEY` | Chave interna do Laravel |
| `APP_DEBUG` | Ativa ou desativa debug |
| `APP_URL` | URL base da aplicação |
| `DB_CONNECTION` | Driver do banco, atualmente `sqlite` |
| `JWT_ISSUER` | Emissor esperado no token JWT: `https://sistema-distribuido-trabalho-faculd.vercel.app` |
| `JWT_AUDIENCE` | Audiência esperada no token JWT: `internal-apis` |
| `JWT_PUBLIC_KEY_PEM` | Chave pública usada para validar JWT RS256 |
| `SCRIBE_AUTH_KEY` | Token usado apenas para gerar exemplos 200 na documentação |

---

## Autenticação

As rotas da API usam autenticação via JWT no padrão Bearer Token.

Todas as requisições para os endpoints `/api/v1/*` devem enviar:

```http
Authorization: Bearer SEU_TOKEN_JWT
Accept: application/json
```

O token precisa:

* usar algoritmo `RS256`
* ter `iss` igual ao valor de `JWT_ISSUER`
* ter `aud` igual ao valor de `JWT_AUDIENCE`
* ter `sub` preenchido
* ter `exp` válido
* ter assinatura compatível com `JWT_PUBLIC_KEY_PEM`

Sem o header `Authorization`, a API retorna `401`.

---

## Executando o Projeto

Inicie o servidor Laravel:

```bash
php artisan serve
```

A aplicação ficará disponível em:

```text
http://localhost:8000
```

---

## Documentação da API

Gere a documentação:

```bash
php artisan scribe:generate
```

Acesse:

```text
http://localhost:8000/docs
```

Links auxiliares:

| Recurso | URL |
| ------- | --- |
| Documentação interativa | `http://localhost:8000/docs` |
| Collection Postman | `http://localhost:8000/docs.postman` |
| OpenAPI | `http://localhost:8000/docs.openapi` |

---

## Rotas da API

| Método | Endpoint | Descrição | Autenticação |
| ------ | -------- | --------- | ------------ |
| GET | `/api/v1/rankings/weekly` | Lista o top 10 jogos por pontuação semanal | JWT |
| GET | `/api/v1/rankings/monthly` | Lista o top 10 jogos por pontuação mensal | JWT |
| GET | `/api/v1/rankings/yearly` | Lista o top 10 jogos por pontuação anual | JWT |
| GET | `/api/v1/rankings/history?id={id}` | Retorna o histórico de pontuação usando query string | JWT |
| GET | `/api/v1/rankings/history/{id}` | Retorna o histórico de pontuação de um jogo | JWT |
| GET | `/api/v1/rankings/platforms/{platform}` | Lista jogos filtrados por plataforma | JWT |
| GET | `/api/v1/games` | Lista os jogos cadastrados com seus IDs | JWT |
| GET | `/api/v1/games/most-played` | Lista o top 10 jogos por jogadores ativos | JWT |

Existe também a rota técnica `GET /api/test-auth`, usada apenas para validar o token JWT. Ela não faz parte da documentação pública principal.

---

## Exemplos de Requisição

Ranking semanal:

```http
GET /api/v1/rankings/weekly HTTP/1.1
Host: localhost:8000
Accept: application/json
Authorization: Bearer SEU_TOKEN_JWT
```

Ranking por plataforma:

```http
GET /api/v1/rankings/platforms/Steam HTTP/1.1
Host: localhost:8000
Accept: application/json
Authorization: Bearer SEU_TOKEN_JWT
```

Histórico de um jogo:

```http
GET /api/v1/rankings/history/1 HTTP/1.1
Host: localhost:8000
Accept: application/json
Authorization: Bearer SEU_TOKEN_JWT
```

Histórico de um jogo usando query string:

```http
GET /api/v1/rankings/history?id=1 HTTP/1.1
Host: localhost:8000
Accept: application/json
Authorization: Bearer SEU_TOKEN_JWT
```

Listar jogos para o frontend escolher o ID:

```http
GET /api/v1/games HTTP/1.1
Host: localhost:8000
Accept: application/json
Authorization: Bearer SEU_TOKEN_JWT
```

---

## Exemplo de Resposta

```json
[
  {
    "id": 1,
    "name": "Counter-Strike 2",
    "platform": "Steam",
    "active_players": 1086549,
    "weekly_points": 729,
    "monthly_points": 1215,
    "yearly_points": 71182,
    "created_at": "2026-05-18T21:57:31.000000Z",
    "updated_at": "2026-05-18T21:57:31.000000Z"
  }
]
```

---

## Retornos Esperados

| Código | Situação | Exemplo |
| ------ | -------- | ------- |
| 200 | Requisição autenticada com sucesso | Lista de jogos ou histórico |
| 401 | Token ausente, inválido ou expirado | `{"message":"Missing Authorization header"}` |
| 404 | Jogo inexistente em `/rankings/history/{id}` | Resposta padrão do Laravel para model não encontrado |
| 422 | ID ausente ou inválido em `/rankings/history?id={id}` | Erro de validação |
| 500 | Erro inesperado no servidor | Falha interna |

Observação: quando uma plataforma não possui jogos, `/api/v1/rankings/platforms/{platform}` retorna `200` com lista vazia.

---

## Testes

Execute:

```bash
php artisan test
```

Os testes cobrem:

* exigência de autenticação JWT
* ranking semanal, mensal e anual
* jogos mais jogados
* histórico por jogo
* filtro por plataforma
* rota técnica de teste de autenticação
* correspondência entre OpenAPI/Scribe e rotas públicas da API

---

## Integração com o Projeto Consumidor

O site ou microsserviço consumidor deve:

1. obter um JWT válido no serviço de autenticação do ecossistema;
2. enviar o token no header `Authorization`;
3. consumir os endpoints `/api/v1/*`;
4. tratar respostas `401` quando o token estiver ausente, inválido ou expirado.

Este microsserviço atualmente não emite tokens. Ele apenas valida tokens JWT RS256.

---

## Dados do Serviço

Atualmente os dados são armazenados na tabela `games` e podem ser populados pelos seeders do Laravel.

Campos principais:

| Campo | Descrição |
| ----- | --------- |
| `name` | Nome do jogo |
| `platform` | Plataforma do jogo |
| `active_players` | Quantidade de jogadores ativos |
| `weekly_points` | Pontuação semanal |
| `monthly_points` | Pontuação mensal |
| `yearly_points` | Pontuação anual |

---

## Fluxo Principal

1. O consumidor solicita um ranking.
2. A API valida o JWT.
3. O controller consulta a tabela `games`.
4. Os dados são ordenados conforme o endpoint.
5. A resposta JSON é retornada ao consumidor.

---

## Arquivos da Entrega

Este repositório contém:

* `README.md`
* `.env.example`
* código-fonte Laravel
* migrations
* seeders
* testes
* documentação Scribe gerada

A pasta `vendor/` não deve ser enviada para o GitHub.

---

## Contato

Projeto acadêmico desenvolvido para a disciplina de Microsserviços no contexto do ecossistema GameVerse.
