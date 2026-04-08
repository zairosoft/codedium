# Workless

> NestJS modular monolith for CRM, helpdesk, and tenant-aware SaaS backends.

Workless is a NestJS MVC-style backend organized as a modular monolith. The project keeps a module-first structure under `src/modules`, adds a central system registry and lifecycle layer under `src/core`, and supports PostgreSQL, Redis, tenant scoping, hooks, events, and server-rendered HTML endpoints.

![Screen](https://www.zairosoft.com/assets/2025/04/codedium.webp "Dashboards")

## Stack

- NestJS 11
- TypeORM
- PostgreSQL
- Redis / ioredis
- BullMQ
- class-validator / class-transformer
- Server-rendered HTML endpoints with reverse-proxy cache support

## Requirements

- Node.js 20+ recommended
- npm 10+
- PostgreSQL 14+ recommended
- Redis 6+ optional but recommended for cache/queue workloads

## Installation

1. Clone the repository

```bash
git clone https://github.com/zairosoft/workless.git
cd workless
```

2. Install dependencies

```bash
npm install
```

3. Create your environment file

```bash
cp .env.example .env
```

4. Configure environment values in `.env`

Base application settings:

- `NODE_ENV=development`
- `PORT=3000`

Required database settings:

- `DB_HOST`
- `DB_PORT`
- `DB_USERNAME`
- `DB_PASSWORD`
- `DB_NAME`

Optional Redis settings:

- `REDIS_ENABLED=true`
- `REDIS_HOST`
- `REDIS_PORT`
- `REDIS_PASSWORD`
- `REDIS_DB`

5. Run the application in development mode

```bash
npm run start:dev
```

The API starts with the global prefix:

```text
/api/v1
```

## Available Scripts

```bash
npm run start:dev
npm run build
npm run start
npm run test
npm run seed
npm run module:list
npm run module:install -- crm
npm run module:upgrade -- crm
npm run module:uninstall -- crm
```

## Project Structure

The project keeps its original module-oriented structure and expands it with core runtime services:

```text
src/
  app.module.ts
  main.ts
  common/
    database/
    interfaces/
    tenant/
  components/
    layouts/
  core/
    events/
    http/
    lifecycle/
    registry/
    system/
  infrastructure/
    cache/
    database/
    redis/
  modules/
    apps/
    auth/
    crm/
    helpdesk/
    notifications/
    org/
    permissions/
    users/
public/
```

## Module Layout

Business modules live in `src/modules/<module-name>` and stay self-contained.

Typical module layout:

```text
src/modules/crm/
  controllers/
  dto/
  hooks/
  migrations/
  models/
  policies/
  repositories/
  seeders/
  services/
  views/
  module.ts
```

## Current Architecture

### Core

- `src/core/system`: system module discovery and enable/disable rules
- `src/core/registry`: persistent module registry state plus in-memory runtime snapshot
- `src/core/lifecycle`: install / uninstall / upgrade flows and lifecycle event emission
- `src/core/events`: hook transformation flow and event bus dispatch
- `src/core/http`: HTML cache metadata and interceptor

### Infrastructure

- `src/infrastructure/database`: TypeORM bootstrap and seed runner
- `src/infrastructure/cache`: centralized cache service, `remember(...)` helper, and Redis provider
- `src/infrastructure/redis`: legacy Redis-related adapters still present in the repo

### Multi-Tenant

- Tenant context is resolved from `x-tenant-id`
- Shared-database tenancy is implemented through `tenantId` on entities
- Cache keys are tenant-aware

## CRM Example

The CRM module is the current reference implementation for the modular monolith approach.

Included concerns:

- thin controller layer
- service orchestration
- entity/domain methods
- repository persistence
- hooks before create/update
- event-driven notifications after writes
- tenant-aware caching
- module lifecycle support
- HTML-cacheable dashboard endpoint
- module-local rendered views

Relevant files:

- `src/modules/crm/module.ts`
- `src/modules/crm/controllers/crm-contact.controller.ts`
- `src/modules/crm/services/crm-contact.service.ts`
- `src/modules/crm/services/crm-module.lifecycle.ts`
- `src/modules/crm/views/crm-contact.view.ts`
- `src/modules/crm/views/crm-dashboard.page.ts`

Known repository edge:

- `src/modules/crm/crm.controller.ts` and `src/modules/crm/crm.service.ts` still exist as older duplicates
- the active wired CRM path is the `crm-contact.*` set above

## Caching

### Data Cache

Redis-backed caching is exposed through:

- `src/infrastructure/cache/cache.module.ts`
- `src/infrastructure/cache/cache.service.ts`

Used for:

- dashboard summaries
- list endpoints
- profile/detail endpoints

Preferred service pattern:

- build tenant-aware key
- use `CacheService.remember(...)`
- invalidate detail and collection version keys on writes

### HTML Cache

The application can emit cache headers for reverse proxies such as NGINX.

Current example:

- `GET /api/v1/crm/dashboard/page`

Headers are managed through the HTML cache interceptor in `src/core/http`.

## Build

```bash
npm run build
```

Build output is written to `dist/`.

## Seed Data

To run module installation/seed flows:

```bash
npm run seed
```

This currently installs discovered modules through the lifecycle service and runs their seeders where defined.

## Notes

- This repository is now NestJS-based, not Laravel/PHP-based.
- Some directories still exist as placeholders to preserve the original modular layout across features.
- `theme html/` contains design/template assets and is not the NestJS runtime source directory.
- `src/infrastructure/redis` remains in the repo for older support paths, but new cache work should prefer `src/infrastructure/cache`.

## Security

Please review [SECURITY.md](SECURITY.md).

## License

Released under the [GNU GENERAL PUBLIC License](license.txt).
