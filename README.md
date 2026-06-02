# Flowforge

A Laravel 13 lead management and integration platform with tenant-aware API access, Facebook lead import hooks, and dynamic lead/status management.

## Project Summary

This project is built on Laravel and provides:

- Multi-tenant lead and lead-status management.
- API authentication using Laravel Sanctum.
- Facebook integration with OAuth connect and webhook lead import.
- Modular business logic via actions, DTOs, filters, services, and event listeners.
- Database-backed sessions, cache, queues, and audit logs.

## Key Features

- Tenant isolation via `tenant_id`, `TenantManager`, and global model scopes.
- Lead CRUD with UTM tracking, custom fields, source/type metadata, and soft deletes.
- Lead status lifecycle management with `is_default` and `is_closed` flags.
- Facebook integration support through `Integration` records and OAuth callbacks.
- Event-driven lead processing: created leads trigger webhooks, integrations, logging, and notifications.

## Requirements

- PHP ^8.3
- Composer
- Node.js / npm
- SQLite (default), MySQL, or another supported database
- Laravel dependencies from `composer.json`
- Frontend tooling via `vite` and `tailwindcss`

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
```

If you use the included composer `setup` script:

```bash
composer setup
```

## Environment Variables

Important `.env` values:

- `DB_CONNECTION` (default: sqlite)
- `SESSION_DRIVER=database`
- `QUEUE_CONNECTION=database`
- `FACEBOOK_APP_ID`
- `FACEBOOK_APP_SECRET`
- `FACEBOOK_REDIRECT_URI`
- `FACEBOOK_VERIFY_TOKEN`

## Running Locally

Start the app with:

```bash
php artisan serve
```

For local development with assets and background workers:

```bash
composer dev
```

This runs:

- `php artisan serve`
- `php artisan queue:listen`
- `php artisan pail` (log tailing)
- `npm run dev`

## API Endpoints

### Unauthenticated API

- `POST /api/v1/register` — register a new tenant user
- `POST /api/v1/login` — authenticate and return an API response
- `POST /api/v1/webhooks/facebook/lead` — receive Facebook lead webhook payloads
- `GET /api/v1/integrations/facebook/connect` — redirect to Facebook OAuth
- `GET /api/v1/integrations/facebook/callback` — handle Facebook OAuth callback

### Authenticated, Tenant-Aware API

These routes require `auth:sanctum` and tenant context:

- `GET /api/v1/me` — current authenticated user

#### Leads
- `GET /api/v1/leads`
- `POST /api/v1/leads`
- `GET /api/v1/leads/{lead}`
- `PUT /api/v1/leads/{lead}`

#### Lead Statuses
- `GET /api/v1/lead-statuses`
- `POST /api/v1/lead-statuses`
- `GET /api/v1/lead-statuses/{id}`
- `PUT /api/v1/lead-statuses/{id}`
- `DELETE /api/v1/lead-statuses/{id}`

## Database Schema Overview

The core domain tables are:

- `tenants`
- `users`
- `leads`
- `lead_statuses`
- `integration_providers`
- `integrations`
- `integration_logs`
- `audit_logs`
- `lead_fields`
- `lead_field_values`

### Lead model highlights

- `tenant_id`
- `first_name`, `last_name`
- `email`, `phone`
- `source`, `type`
- `lead_status_id`
- UTM fields
- Soft deletes

### Integration model highlights

- `tenant_id`
- `integration_provider_id`
- `name`
- `config` JSON for provider-specific settings
- `enabled`

## Architecture Notes

- Controllers delegate to actions and DTOs for business logic.
- Policies and gates authorize lead and lead status operations.
- `BelongsToTenant` trait and `TenantScope` enforce tenant isolation on models.
- `AppServiceProvider` registers event listeners for lead creation and update events.
- `ApiResponse` centralizes JSON success/error response structure.

## Testing

Run tests with:

```bash
composer test
```

The suite uses Pest and includes tenant isolation tests.

## Notes

- `routes/web.php` currently serves the default welcome landing page.
- Facebook integration requires valid app credentials and the callback URL to match `FACEBOOK_REDIRECT_URI`.
- The project is primarily API-driven; UI assets are supported through Vite.

## Useful Commands

- `composer install`
- `composer test`
- `php artisan migrate`
- `php artisan queue:listen`
- `npm run dev`
- `npm run build`

---

Feel free to ask for a second README version with example request payloads or API usage examples.