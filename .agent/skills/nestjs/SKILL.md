---
name: nestjs-expert
description: Apply NestJS expertise to the Workless repository. Use when changing modules, providers, controllers, lifecycle wiring, TypeORM integration, tenant context, cache integration, or configuration in this NestJS monolith.
---

# NestJS Expert

Use this skill for NestJS-specific work in Workless.

## Project Reality

Workless is a NestJS modular monolith with:

- NestJS 11
- `ConfigModule.forRoot(...)` in `src/app.module.ts`
- global validation pipe in `src/main.ts`
- TypeORM + PostgreSQL
- cache services under `src/infrastructure/cache`
- tenant scoping through `TenantContextMiddleware` and `TenantContextService`
- runtime module discovery/registry/lifecycle under `src/core`
- domain event wrapper under `src/core/events/event-bus.service.ts`

This repo is backend-first. Do not assume React, Passport auth, or microservices unless the code for them exists.

## Read First

Before structural changes, inspect in this order:

1. `src/app.module.ts`
2. `src/core/core.module.ts`
3. target module `src/modules/<name>/module.ts`
4. related runtime files under:
   - `src/core/system`
   - `src/core/registry`
   - `src/core/lifecycle`
   - `src/core/events`

## Active Patterns In Workless

### Module Wiring

Feature modules are organized under `src/modules/<name>`.

Expected responsibilities:

- controllers are thin
- services orchestrate
- repositories own persistence
- DTOs validate public input
- hooks/events are used for extension points
- lifecycle services are added only when a module needs install/upgrade/uninstall behavior

### System Module Runtime

When a module participates in lifecycle management, inspect:

- `src/core/system/system-module.decorator.ts`
- `src/core/system/system-module.interface.ts`
- `src/core/system/system-module.explorer.ts`
- `src/core/registry/module.registry.ts`
- `src/core/lifecycle/module.lifecycle.ts`

Current low-risk runtime pattern:

- registry reads should prefer the in-memory runtime snapshot
- lifecycle operations should emit events through `EventBusService`
- `HookService` should stay focused on transformation/extensibility hooks

### Tenant Model

Tenant flow is:

1. `TenantContextMiddleware` reads `x-tenant-id`
2. `TenantContextService` stores it in async local storage
3. repositories/entities use `tenantId`
4. cache keys must remain tenant-aware

### Cache Model

Preferred cache path:

- `src/infrastructure/cache/*`

Use this for new cache work:

- `CacheModule`
- `CacheService`
- `redis.provider.ts`
- `CacheService.remember(...)` for cache-aside reads

Be careful not to drift into older Redis scaffolding under `src/infrastructure/redis/*` unless the task explicitly targets it.

## Workless-Specific Hazards

### CRM Has Legacy Duplicates

There are old and new CRM files side by side. Always check `src/modules/crm/module.ts` first to see what is active.

The active CRM path currently uses:

- `controllers/crm-contact.controller.ts`
- `services/crm-contact.service.ts`
- `models/crm-contact.entity.ts`
- `seeders/crm-contact.seeder.ts`
- `views/crm-dashboard.page.ts`

Do not patch older duplicates by accident unless cleanup is part of the task.

### Validation Reality

Current reliable verification:

```bash
npm run build
```

Important:

- `npm test` is still a placeholder
- runtime checks need Postgres, and optional Redis
- do not claim tests passed unless you actually ran them

## When Solving Problems

### Dependency Injection Issues

Check:

1. provider is registered in the target module
2. provider is exported only when needed externally
3. imports/exports match actual active files
4. duplicate legacy files are not confusing the dependency graph

### TypeORM Issues

Check:

1. entity path is the active one
2. `TypeOrmModule.forFeature(...)` includes the correct entity
3. env variables in `.env.example` match actual code usage
4. database config aligns with `src/infrastructure/database/typeorm.config.ts`

### Lifecycle Issues

Check:

1. module lifecycle service implements `SystemModuleLifecycle`
2. module metadata and registry names match
3. install/upgrade/uninstall flows are idempotent where possible
4. cache invalidation is included when lifecycle changes affect stale data

### Tenant / Cache Bugs

Check:

1. repositories filter by `tenantId`
2. service cache keys include tenant scope where needed
3. write paths invalidate or roll namespace-version keys

## Work Style

When editing this repo:

1. confirm the active file path first
2. follow the existing module shape
3. preserve tenant-awareness
4. prefer current runtime paths over legacy scaffolding
5. verify with `npm run build`

## Success Criteria

- changes match the active NestJS wiring
- no accidental edits to stale duplicate files
- tenant/cache/lifecycle behavior remains coherent
- the project still builds cleanly
