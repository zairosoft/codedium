# Modular Monolith Architecture

## Overview

This refactor keeps the application as a NestJS monolith, but changes the runtime model from flat feature modules to modular compiled modules with runtime state.

Implemented layers:

- `src/core`: module registry, module lifecycle, hook/event system, module enable/disable guard, HTML cache headers
- `src/modules`: self-contained business modules with `controllers`, `services`, `models`, `repositories`, `dto`, `hooks`, `policies`, `seeders`, `migrations`, `views`
- `src/infrastructure/database`: TypeORM bootstrap and lifecycle seeding entrypoint
- `src/infrastructure/cache`: centralized Redis-backed cache service with in-memory fallback
- `src/views`: server-rendered page builders for cacheable HTML pages
- `public`: static asset output target for Vite or other frontend builds

## Runtime Flow

1. `CoreModule` discovers System modules through `@SystemModule(...)` metadata.
2. `ModuleRegistryService` syncs discovered modules into `system_module_registry`.
3. `ModuleLifecycleService` executes `install`, `uninstall`, and `upgrade`.
4. `ModuleEnabledGuard` blocks controllers decorated with `@RequiresModule('module_name')` when the module is not installed and enabled.
5. `HookService` resolves `@Hook('event.name')` handlers and emits the same event through `EventEmitter2` for loose coupling.

## Module Registry

Entity: `src/core/registry/module-registry.entity.ts`

Tracked state:

- `name`
- `version`
- `status`: `installed | uninstalled | disabled`
- `enabled`
- `description`
- `dependencies`
- `installedAt`
- `upgradedAt`

API endpoints:

- `GET /api/v1/system/modules`
- `POST /api/v1/system/modules/:name/install`
- `POST /api/v1/system/modules/:name/uninstall`
- `POST /api/v1/system/modules/:name/upgrade`

CLI entrypoint:

- `npm run module:list`
- `npm run module:install -- crm`
- `npm run module:upgrade -- crm`
- `npm run module:uninstall -- crm`

## Module Lifecycle

Contract: `src/core/system/system-module.interface.ts`

Each System module implements:

- `install(context)`
- `uninstall(context)`
- `upgrade(context, fromVersion)`

Current CRM example:

- lifecycle provider: `src/modules/crm/services/crm-module.lifecycle.ts`
- migration: `src/modules/crm/migrations/crm-contact-index.migration.ts`
- seeder: `src/modules/crm/seeders/crm-contact.seeder.ts`

Install flow:

1. run idempotent module migrations
2. seed baseline data
3. mark registry state as installed and enabled
4. clear stale cache namespaces

Uninstall flow:

1. execute module-specific cleanup hooks
2. disable module
3. mark registry state as uninstalled

Upgrade flow:

1. read the current stored version
2. run module migrations for the target code version
3. clear cache namespaces
4. mark the new version as installed

## CRM Example Module

Implemented CRM module structure:

- `src/modules/crm/controllers/crm-contact.controller.ts`
- `src/modules/crm/services/crm-contact.service.ts`
- `src/modules/crm/services/crm-module.lifecycle.ts`
- `src/modules/crm/models/crm-contact.entity.ts`
- `src/modules/crm/repositories/crm-contact.repository.ts`
- `src/modules/crm/hooks/crm-contact.hooks.ts`
- `src/modules/crm/policies/crm-contact.policy.ts`
- `src/modules/crm/seeders/crm-contact.seeder.ts`
- `src/modules/crm/migrations/crm-contact-index.migration.ts`
- `src/modules/crm/views/crm-contact.view.ts`

Design choices:

- controllers stay thin and delegate orchestration to services
- entity methods own contact state mutation rules via `applyProfile`, `promoteToCustomer`, and `isCustomer`
- repository stays tenant-aware and hides persistence concerns from services
- hooks sanitize inbound DTOs before persistence
- module enablement is enforced centrally with `@RequiresModule('crm')`

## Cache Layer

Implementation:

- `src/infrastructure/cache/cache.module.ts`
- `src/infrastructure/cache/cache.service.ts`
- `src/infrastructure/cache/redis.provider.ts`

Public contract:

- `cacheService.get(key)`
- `cacheService.set(key, value, ttl)`
- `cacheService.del(key)`

### Cache Strategy

CRM cache keys are tenant-aware:

- contact detail: `crm:{tenantId}:contacts:{id}`
- collection namespace version: `crm:{tenantId}:contacts:version`
- list results: `crm:{tenantId}:contacts:list:{version}:{queryHash}`
- dashboard: `crm:{tenantId}:dashboard:{version}`

Recommended TTLs:

- dashboard summary: `60s`
- list endpoints: `120s`
- profile/detail endpoints: `300s`

### Invalidation

On create/update/delete:

- delete the contact detail key
- delete the collection version key
- let list and dashboard keys roll forward to a fresh namespace version

This avoids wildcard deletes and keeps invalidation Redis-friendly at scale.

## Hook / Event System

Files:

- `src/core/events/hook.decorator.ts`
- `src/core/events/hook.service.ts`

Pattern:

- `@Hook('customer.beforeCreate')`
- `await hookService.emit('customer.beforeCreate', payload)`

Why both hooks and events are kept:

- hooks support sequential payload transformation before domain work
- the same hook name is emitted through `EventEmitter2`, so other modules can react without direct imports

Current example:

- CRM sanitizes contact payloads through `CrmContactHooks`
- Notifications listens to `customer.afterCreate`

## HTML Cache Guidelines

Implemented example:

- endpoint: `GET /api/v1/crm/dashboard/page`
- decorator: `@HtmlCacheable({ maxAgeSeconds: 60, scope: 'public', vary: ['Accept-Encoding', 'X-Tenant-Id'] })`
- renderer: `src/views/crm/crm-dashboard.page.ts`

Guidelines:

- only mark pages `public` when content is shared and not user-specific
- include `X-Tenant-Id` in `Vary` when a page is tenant-aware
- keep authenticated or personalized pages `private` or `no-store`
- let NGINX or another reverse proxy own the HTML cache; the app only sets correct headers
- use surrogate keys only for broad page groups, not for per-user cache invalidation

Example NGINX policy:

```nginx
proxy_cache_path /var/cache/nginx levels=1:2 keys_zone=system_html:100m inactive=10m use_temp_path=off;

location /api/v1/crm/dashboard/page {
    proxy_cache system_html;
    proxy_cache_key "$scheme$request_method$host$request_uri$http_x_tenant_id";
    proxy_cache_valid 200 60s;
    add_header X-Proxy-Cache $upstream_cache_status;
    proxy_pass http://nestjs_upstream;
}
```

## Multi-Tenant Notes

Current implementation stays in the shared-database model:

- every business entity inherits `tenantId`
- request scope resolves tenant through `TenantContextMiddleware`
- cache keys include `tenantId`
- public HTML cache varies by tenant header

For future database-per-tenant evolution:

- keep `TenantContextService` as the single tenant resolution boundary
- move connection routing behind the infrastructure layer without changing module code

## Scaling Best Practices

- Keep modules isolated and communicate through hooks or domain events, not direct imports.
- Cache list and dashboard reads aggressively, but make invalidation deterministic and tenant-aware.
- Prefer namespace-version invalidation over Redis key scans.
- Use reverse-proxy HTML caching only for shared views with stable TTLs.
- Keep controllers transport-only and move orchestration into services and state rules into entities.
- Treat module lifecycle as an operational boundary: install, upgrade, and uninstall should stay idempotent.
- Add metrics around cache hit ratio, module lifecycle duration, queue depth, and slow queries before splitting architecture further.
- Delay microservices until module boundaries, traffic profile, and operational burden justify the extra complexity.

## Verification

Verified locally:

- `npm.cmd install`
- `npm.cmd run build`

Not runtime-verified in this workspace:

- database-backed module registry operations
- Redis connectivity
- HTTP endpoints against a running Nest process

Those require the target Postgres and optional Redis environment to be available.


