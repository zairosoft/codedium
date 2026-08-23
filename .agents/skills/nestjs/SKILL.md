---
name: nestjs-expert
description: Apply NestJS expertise to the Workless repository. Use when changing modules, providers, controllers, lifecycle wiring, TypeORM integration, tenant context, cache integration, or configuration in this NestJS monolith.
---

# NestJS Expert

Use this skill for NestJS-specific work in Workless.

## Project Reality

Workless is a NestJS modular monolith with:

- application features under `src/app`
- shared runtime engine under `src/workless`
- plugin modules under `src/modules`
- `ConfigModule.forRoot(...)` in `src/app.module.ts`
- global validation pipe in `src/main.ts`
- TypeORM + PostgreSQL
- optional Redis-backed cache under `src/workless/infrastructure/cache`
- tenant scoping through `src/workless/tenant/*`
- runtime module registry and lifecycle under `src/workless/lifecycle`, `src/workless/module`, and `src/workless/registry`

Views are server-rendered React TSX through `react-dom/server`; this is not a hydrated SPA. Passport JWT auth is active. Do not assume client-side routing or microservices.

## Read First

Before structural changes, inspect in this order:

1. `src/app.module.ts`
2. `src/workless/workless.module.ts`
3. `src/modules/modules.ts`
4. target module `src/modules/<name>/module.ts` or target application feature under `src/app`
5. related runtime files under:
   - `src/workless/module`
   - `src/workless/registry`
   - `src/workless/lifecycle`
   - `src/workless/events`
   - `src/workless/infrastructure`
   - `src/workless/tenant`

## Active Patterns In Workless

### Layering

- `src/app` contains application auth, users, permissions, locales, and shared views
- `src/workless` contains the shared runtime engine
- `src/modules` contains optional plugin modules

### Module Wiring

Expected responsibilities:

- controllers are thin
- services orchestrate
- repositories own persistence
- DTOs validate public input
- policies own permission and rule checks
- hooks and events are used for extension points
- lifecycle services are added only when a module needs install, upgrade, or uninstall behavior
- backend code should avoid a separate `models/` layer and instead use `entities/`, `dto/`, and the owning module's `interfaces/`

### Runtime System

When a module participates in lifecycle management, inspect:

- `src/workless/module/module.decorator.ts`
- `src/workless/module/module.interface.ts`
- `src/workless/module/module.explorer.ts`
- `src/workless/registry/module.registry.ts`
- `src/workless/lifecycle/module.lifecycle.ts`
- `src/workless/lifecycle/module-lifecycle.runner.ts`

Current low-risk runtime pattern:

- registry reads should prefer discoverable modules only
- lifecycle operations emit domain events
- module loading should tolerate missing plugin directories

### Tenant Model

Tenant flow is:

1. `TenantContextMiddleware` reads `x-tenant-id`
2. `TenantContextService` stores normalized state in async local storage
3. repositories and entities use `tenantId`
4. cache keys must remain tenant-aware

### Cache Model

Preferred cache path:

- `src/workless/infrastructure/cache/*`

Use this for new cache work:

- `CacheModule`
- `CacheService`
- `redis.provider.ts`
- `CacheService.remember(...)`

Page cache headers live under:

- `src/workless/http/html-cache.interceptor.ts`

## Workless-Specific Hazards

### Runtime vs Scaffold

`agent`, `helpdesk`, and `org` are currently scaffold-heavy. Confirm active providers and controllers before making behavior claims.

### CRM Reference Path

Use `src/modules/crm` as the main reference for:

- controller and service wiring
- repository pattern
- hook and event usage
- lifecycle integration
- tenant-aware cache keys
- backend module structure without `models/`

### Verification Reality

Current reliable baseline:

```bash
npm run build
```

Useful setup commands:

```bash
npm run db:migrate
npm run seed
```

Important:

- `npm test` is still a placeholder
- runtime checks need Postgres, and optional Redis
- do not claim tests passed unless they actually ran

## When Solving Problems

### Dependency Injection Issues

Check:

1. provider is registered in the active module
2. provider is exported only when needed externally
3. imports and exports match current paths
4. modules depend on core interfaces, not app services

### TypeORM Issues

Check:

1. entity path is the active one
2. `TypeOrmModule.forFeature(...)` includes the correct entity
3. schema preparation paths align with `src/database/*`
4. database config aligns with `src/database/typeorm.config.ts`

### Lifecycle Issues

Check:

1. module lifecycle service implements `SystemModuleLifecycle`
2. module metadata and registry names match
3. install, upgrade, and uninstall flows are idempotent where possible
4. lifecycle changes invalidate stale cache correctly

### Tenant and Cache Bugs

Check:

1. repositories filter by `tenantId`
2. service cache keys include tenant scope
3. write paths invalidate detail and collection or version keys
4. HTML cache keys remain module and tenant aware

## Success Criteria

- changes match active NestJS wiring
- no accidental edits to stale or scaffold-only paths
- tenant, cache, and lifecycle behavior remain coherent
- the project still builds cleanly when the environment is ready
