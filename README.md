# Foundry

Manifest-driven Laravel microservice scaffolder. Accepts a JSON manifest describing an app and produces a fully wired Laravel microservice — GitHub repo created, code generated via Blueprint + Ollama, quality gate passed, Podman Quadlet deployed on Odin.

## Stack

- **Laravel 13** — API-only
- **spatie/laravel-data** — typed manifest DTOs
- **Laravel Horizon** — queue visibility and workers
- **Laravel Socialite** — GitHub OAuth for repo automation
- **spatie/laravel-webhook-client** — Bifrost event ingestion
- **FrankenPHP** — HTTP server (Octane mode)

## Architecture

```
POST /api/scaffold (ManifestData JSON)
    ↓
RepoGeneratorJob
    → GitHub: create repo, seed issues, register Bifrost source
    ↓
CodeGeneratorJob
    → Blueprint scaffold
    → Ollama fills in business logic
    → Quality gate (Pint, PHPStan, 100% coverage)
    → Podman Quadlet deploy on Odin
```

## Manifest Schema

```json
{
  "name": "billing-service",
  "description": "Handles billing and subscriptions",
  "stack": {
    "framework": "laravel",
    "type": "microservice",
    "php": "8.2",
    "auth": "github-socialite",
    "queue": "redis",
    "db": "sqlite"
  },
  "models": [
    {
      "name": "Plan",
      "attributes": ["name", "price_cents"],
      "relationships": [
        { "type": "hasMany", "model": "Subscription" }
      ]
    }
  ],
  "bifrost": {
    "source": "billing-service",
    "events": ["subscription.created"]
  },
  "quality": {
    "runner": true,
    "coverage": 100,
    "pint": true,
    "phpstan": true
  },
  "deploy": {
    "host": "odin",
    "quadlet": true
  }
}
```

## Development

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan test --coverage --min=100
./vendor/bin/pint
```

## Quality Gate

100% test coverage required. Pest BDD syntax enforced. Laravel Pint + PHPStan level 8.
