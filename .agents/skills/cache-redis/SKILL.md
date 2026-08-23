---
name: cache-redis
description: Design and review Redis-backed caching in Workless. Use when changing CacheService usage, key naming, TTL policy, invalidation, tenant-aware caching, or HTML cache coordination in this NestJS monolith.
---

# Cache Redis

Use this skill when work touches cache behavior in Workless.

## Project Reality

Workless has a central cache abstraction under:

- `src/workless/infrastructure/cache/cache.service.ts`
- `src/workless/infrastructure/cache/cache.store.ts`
- `src/workless/infrastructure/cache/redis.provider.ts`

Redis is optional:

- if Redis is enabled, cache uses `ioredis`
- otherwise cache falls back to in-memory storage
- the shared cache contract currently lives at `src/app/interfaces/cache.interface.ts`
- `CacheModule` is wired globally from `src/app.module.ts`

The in-memory fallback is selected only when `REDIS_ENABLED` is false. Do not claim automatic failover when Redis is enabled but unavailable.

## Use This Skill For

- adding cache reads and writes to services
- defining key naming conventions
- choosing TTL values
- invalidating related cache on writes
- keeping cache keys tenant-aware
- reviewing whether list or dashboard caching will go stale
- coordinating data cache with HTML cache headers

## Read First

Before changing cache logic, inspect in this order:

1. `src/workless/infrastructure/cache/cache.service.ts`
2. `src/workless/infrastructure/cache/redis.provider.ts`
3. the active service using cache
4. the repository used by that service
5. `src/workless/http/html-cache.interceptor.ts` if page caching is involved

For an active reference, inspect:

- `src/modules/crm/services/crm-contact.service.ts`

## Workless Cache Rules

### 1. Cache at the Service Layer

Cache orchestration belongs in services, not controllers.

Preferred pattern:

- service builds the key
- service uses `CacheService.remember(...)`
- repository is called only on cache miss
- writes explicitly invalidate related keys or version namespaces

### 2. Keep Keys Tenant-Aware

Every business-data cache key must include tenant scope.

Preferred pattern:

```text
<module>:<tenantId>:<resource>:...
```

Examples:

- `crm:<tenantId>:contacts:<moduleVersion>:<id>`
- `crm:<tenantId>:contacts:list:<moduleVersion>:<collectionVersion>:<digest>`
- `crm:<tenantId>:dashboard:<moduleVersion>:<collectionVersion>`

### 3. Prefer Version-Key Invalidation

On create, update, or delete:

- clear detail cache for the affected record when needed
- roll collection or module version keys instead of scanning keys

Current CRM pattern combines:

- a module cache version key: `crm:module:version`
- tenant-level collection version keys: `crm:<tenantId>:contacts:version`

This is the preferred pattern for new module work.

### 4. Keep TTLs Intentional

Choose TTL by data volatility:

- detail views: longer TTL if data changes less often
- list endpoints: medium TTL
- dashboards or summaries: short TTL

Current CRM examples:

- dashboard: 60 seconds
- list: 120 seconds
- detail: 300 seconds

## HTML Cache Coordination

Data cache:

- handled through `CacheService`
- protects repository and database load

HTML cache:

- handled through response headers
- configured through `src/workless/http/html-cache.interceptor.ts`

Do not mix these concerns.

## Anti-Patterns

Avoid these in Workless:

- keys without tenant scope
- wildcard delete as the primary invalidation strategy
- cache logic in controllers
- caching raw request objects
- introducing a second cache implementation outside `src/workless/infrastructure/cache/*`
- claiming Redis behavior was verified when Redis was not enabled

## Verification

For cache-related changes:

1. confirm the edited service is the active wired service
2. run `npm run build` when the environment allows it
3. inspect create, update, and delete paths for invalidation
4. inspect list, detail, and dashboard reads for tenant-aware keys
5. state clearly whether Redis was actually enabled during verification
