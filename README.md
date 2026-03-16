# Feature Flag Platform + Car Damage Reports (Laravel 12)

Production-style take-home backend using a modular monolith with DDD-inspired boundaries.

Architecture notes are documented in `ARCHITECTURE_DECISION.md`.

## Assumptions
- No API Authentication and Authorisations
- Feature flag rules are **not user-specific** in this scope.
- `rule_based` rollout is deterministic by **flag key only** (no `user_id` targeting).
- This keeps behavior simple and predictable for evaluator review.

## Folder Structure

`app/Modules/FeatureFlags`
- `Domain` enums + repository contracts
- `Application` use cases and evaluation engine
- `Infrastructure` Eloquent models and repositories
- `Presentation` API, web controllers, requests, middleware

`app/Modules/CarDamageReports`
- `Domain` repository contracts
- `Application` use cases
- `Infrastructure` models and repositories
- `Presentation` API controllers and requests

## APIs

### Feature Flags Admin API
- `GET /api/admin/feature-flags`
- `POST /api/admin/feature-flags`
- `GET /api/admin/feature-flags/{id}`
- `PUT /api/admin/feature-flags/{id}`
- `DELETE /api/admin/feature-flags/{id}`

### Feature Flag Evaluation API
- `GET /api/feature-flags/evaluate`

Note: `user_id` can still be sent for backward compatibility, but evaluation output does not vary by user in this assignment.

### Car Damage Reports API
- `GET /api/reports`
- `POST /api/reports`
- `GET /api/reports/{id}`
- `PUT /api/reports/{id}` (`allow_report_editing` enforced)
- `POST /api/reports/{id}/photos` (`allow_photo_upload` enforced)
- `GET /api/reports/{id}/history`

## Blade Admin UI

- `GET /admin/feature-flags`
- `GET /admin/feature-flags/create`
- `GET /admin/feature-flags/{id}/edit`

## Feature Flag Evaluation Rules

Applied in this order:
1. `enabled = false` -> `false`
2. `starts_at` in future -> `false`
3. `expires_at` in past -> `false`
4. `rule_based` -> percentage rollout by deterministic hash of `key`
5. otherwise `enabled`

## Cache Strategy

- Evaluated results are cached globally with key format: `flags:v{version}`
- TTL configured by `FEATURE_FLAGS_CACHE_TTL` (default `60`)
- Invalidation strategy: bump global cache version on create/update/delete

Tradeoff:
- Version bump is simple and robust, but it invalidates the full evaluation cache.

## Run Application (Evaluator-Friendly)

### Option 1: Docker (recommended)

For the fastest review setup:

```bash
docker compose up --build
```

Then, in order to load Blade admin UI styling/scripts:

```bash
npm install
npm run build
```

Notes:
- API-only evaluation can skip Node build step.
- Backend and APIs are available at `http://localhost:8000`.
- Database migrations and seeders run automatically in Docker startup.


With Docker, the backend container automatically runs:
- `composer install` (if needed)
- `php artisan key:generate --force`
- `php artisan migrate --force`
- `php artisan db:seed --force`

### Option 2: Local (without Docker)

Prerequisites:
- PHP `^8.2`
- Composer
- Node.js + npm
- PostgreSQL
- Redis

Setup:

```bash
cp .env.example .env
composer install
npm install
php artisan key:generate
php artisan migrate --seed
```

Run:

```bash
composer run dev
```

This starts Laravel server, queue listener, logs, and Vite concurrently.

## Seed Data

By default, seeders are imported for testing and evaluator convenience.

- `DatabaseSeeder` calls:
  - `FeatureFlagSeeder`
  - `CarDamageReportSeeder`

You can reseed locally with:

```bash
php artisan db:seed
```

## Test

```bash
php artisan test
```
