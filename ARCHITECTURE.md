# Workless Architecture

## Runtime Shape

Workless is a NestJS modular monolith. The running application is assembled in `src/app.module.ts` from these layers:

1. `ConfigModule`
2. `EventEmitterModule`
3. `ThrottlerModule`
4. `TenantModule`
5. `DatabaseModule`
6. `CacheModule`
7. `PlatformModule`
8. `CoreModule`
9. runtime business modules from `src/modules/runtime-modules.ts`

This means the project is not split into separate deployable services. Platform features and business modules run in one Nest process and share the same database connection and middleware pipeline.

## Bootstrap

`src/main.ts` is the HTTP bootstrap.

Current responsibilities:

- create the Nest app
- apply Helmet
- enable CORS
- serve static files from `public/`
- set global API prefix to `/api/v1`
- exclude `GET /` and `GET /auth/login` from the prefix
- enable `ValidationPipe`

Current route shape:

- HTML pages:
  - `GET /`
  - `GET /auth/login`
- API endpoints:
  - `/api/v1/...`

## Top-Level Source Layout

```text
src/
  app.module.ts
  main.ts
  app/
  core/
  database/
  modules/
```

### `src/app`

Platform concerns that are always present regardless of which runtime business modules are installed.

Current contents:

- `auth/`: JWT guard, strategy, `@Public()`
- `controllers/`: auth, home, users, notifications, permissions, profile, roles, settings
- `dto/`: login and user DTOs
- `entities/`: platform users and memberships
- `providers/`: permission guard, listeners, policies
- `services/`: auth, users, roles, notifications
- `views/`: landing page, login page, shared HTML components

`PlatformModule` wires these pieces together and also exports service contracts through the interfaces under `src/core/interfaces`.

### `src/core`

Cross-cutting infrastructure and runtime orchestration.

Current responsibilities:

- hook system
- event bus
- module metadata discovery
- module enabled guard
- module registry persistence
- module lifecycle commands and HTTP controller
- tenant context resolution
- HTML cache headers/interceptor
- cache infrastructure

Important files:

- `src/core/core.module.ts`
- `src/core/events/event-bus.service.ts`
- `src/core/events/hook.service.ts`
- `src/core/module/module.decorator.ts`
- `src/core/module/module.explorer.ts`
- `src/core/module/module-enabled.guard.ts`
- `src/core/registry/module-registry.entity.ts`
- `src/core/registry/module.registry.ts`
- `src/core/lifecycle/module.lifecycle.ts`
- `src/core/lifecycle/module-lifecycle.controller.ts`
- `src/core/tenant/tenant-context.middleware.ts`
- `src/core/http/html-cache.interceptor.ts`
- `src/core/infrastructure/cache/cache.module.ts`

### `src/database`

Database integration for the Nest app and operational runners.

Current contents:

- `database.module.ts`: registers TypeORM
- `typeorm.config.ts`: Postgres connection options from environment
- `platform-schema.runner.ts`: platform schema runner
- `seeder.runner.ts`: lifecycle-aware seeding entrypoint
- `migrations/`: platform migrations
- `seeders/`: platform seeders

The database configuration currently assumes PostgreSQL and enables `synchronize` only for non-production environments when `DB_SYNC=true`.

### `src/modules`

Business modules loaded at runtime.

Current runtime list from `src/modules/runtime-modules.ts`:

- `crm`
- `helpdesk`
- `org`

Current state:

- `crm` contains the most real implementation
- `helpdesk` and `org` mostly provide module/lifecycle scaffolding
- `apps` is present as a scaffold and is not currently loaded by `runtime-modules.ts`

## Runtime Module Loading

Runtime modules are not auto-scanned from the filesystem. They are loaded from the explicit list in `src/modules/runtime-modules.ts`.

Current behavior:

- if a module export is present, it is added to `AppModule.imports`
- if a module file is missing, the loader logs a warning and skips it
- expected exports:
  - `CrmModule`
  - `HelpdeskModule`
  - `OrgModule`

This makes module activation explicit at code level while still allowing operational enable/disable state through the module registry.

## Module Registry and Lifecycle

The module registry persists operational state in `ModuleRegistryEntity`.

Tracked state includes:

- module name
- version
- status
- enabled flag
- description
- dependencies
- timestamps such as installed and upgraded dates

Current lifecycle surface:

- controller: `src/core/lifecycle/module-lifecycle.controller.ts`
- service: `src/core/lifecycle/module.lifecycle.ts`
- CLI runner: `src/core/lifecycle/module-lifecycle.runner.ts`

Current HTTP endpoints:

- `GET /api/v1/system/modules`
- `POST /api/v1/system/modules/:name/install`
- `POST /api/v1/system/modules/:name/uninstall`
- `POST /api/v1/system/modules/:name/upgrade`

Current CLI commands:

- `npm run module:list`
- `npm run module:install -- <name>`
- `npm run module:upgrade -- <name>`
- `npm run module:uninstall -- <name>`

## Guards and Request Pipeline

Global guards in the current app:

- `ThrottlerGuard` from `AppModule`
- `JwtAuthGuard` from `PlatformModule`
- `PermissionGuard` from `PlatformModule`
- `ModuleEnabledGuard` from `CoreModule`

Important consequence:

- endpoints are authenticated by default unless marked with `@Public()`
- module-enabled checks are centralized in core
- tenant context middleware runs for all routes before controller handling

## Tenant Model

The current tenant model is shared-database multi-tenancy.

Implemented pieces:

- `TenantContextMiddleware`
- `TenantContextService`
- `TenantScopedEntity`

Current behavior:

- tenant is resolved from request context
- entities can inherit tenant-scoped fields
- cache keys can include tenant identifiers
- HTML cache can vary by tenant header

## Hooks and Events

Workless keeps both a hook pipeline and an event bus.

Use cases:

- hooks: pre-processing and payload transformation before domain writes
- events: post-action notifications and loose coupling between modules

Important files:

- `src/core/events/hook.decorator.ts`
- `src/core/events/hook.service.ts`
- `src/core/events/event-bus.service.ts`

## Cache and HTML Rendering

The cache layer lives under:

- `src/core/infrastructure/cache/cache.module.ts`
- `src/core/infrastructure/cache/cache.service.ts`

HTML cache support lives under:

- `src/core/http/html-cache.decorator.ts`
- `src/core/http/html-cache.interceptor.ts`

Views are server-rendered with KITA JSX/TSX from `src/app/views` and `src/modules/*/views`.

Current public HTML examples:

- `src/app/views/home/home.page.tsx`
- `src/app/views/auth/login.page.tsx`
- `src/modules/crm/views/crm-dashboard.page.tsx`

## CRM as the Reference Module

`crm` is the most complete example of the intended module shape.

Current CRM implementation includes:

- controller: `src/modules/crm/controllers/crm-contact.controller.ts`
- DTOs: `src/modules/crm/dto/*`
- entity: `src/modules/crm/entities/crm-contact.entity.ts`
- repository: `src/modules/crm/repositories/crm-contact.repository.ts`
- hooks: `src/modules/crm/hooks/crm-contact.hooks.ts`
- policies: `src/modules/crm/policies/*`
- lifecycle: `src/modules/crm/lifecycle/crm-module.lifecycle.ts`
- migration: `src/modules/crm/migrations/crm-contact-index.migration.ts`
- seeder: `src/modules/crm/seeders/crm-contact.seeder.ts`
- views: `src/modules/crm/views/*`

This is the best place to copy structure from when adding a new runtime business module.

## Frontend Asset Pipeline

The live runtime serves assets from `public/`.

Current CSS pipeline:

- source: `public/assets/css/app.css`
- output: `public/assets/css/tailwindcss.css`
- config: `tailwind.config.js`

Current scripts:

- `npm run dev:css`
- `npm run build:css`

`vite.config.ts` and `vitest.config.ts` exist in the repository, but the current application runtime is still the Nest server plus static files from `public/`.

## Non-Runtime Reference Material

`theme/` is reference material only.

It should not be treated as part of the runtime architecture, import graph, or asset dependency chain.

## Verification

This document was aligned to the current repository structure, runtime wiring, and scripts in:

- `src/main.ts`
- `src/app.module.ts`
- `src/app/platform.module.ts`
- `src/core/core.module.ts`
- `src/database/*`
- `src/modules/runtime-modules.ts`
- `package.json`
