# Feature Flag Platform + Car Damage Reports (Laravel 12)

Production-style take-home backend using a modular monolith with DDD-inspired boundaries.

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
- `GET /api/feature-flags/evaluate?user_id=123`

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
4. `rule_based` -> percentage rollout by deterministic hash of `key:user_id`
5. otherwise `enabled`

## Cache Strategy

- Evaluated results are cached by context with key format: `flags:v{version}:{sha1(user_id)}`
- TTL configured by `FEATURE_FLAGS_CACHE_TTL` (default `60`)
- Invalidation strategy: bump global cache version on create/update/delete

Tradeoff:
- Version bump is simple and robust for high traffic, but it invalidates all contexts (not just affected subset).

## Run Locally (Docker)

```bash
docker compose up --build
```

Backend runs at `http://localhost:8000`.

## Run Locally (without Docker)

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

## Test

```bash
php artisan test
```
