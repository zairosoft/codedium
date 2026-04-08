---
name: cache-redis
description: Design and review Redis-backed caching in Workless. Use when changing CacheService usage, key naming, TTL policy, invalidation, tenant-aware caching, or HTML cache coordination in this NestJS monolith.
---

# Cache Redis

Use this skill when work touches cache behavior in Workless.

## Project Reality

Workless already has a central cache abstraction:

- `src/infrastructure/cache/cache.service.ts`
- `src/infrastructure/cache/cache.store.ts`
- `src/infrastructure/cache/redis.provider.ts`

Redis is optional.

Current behavior:

- if `REDIS_ENABLED=true`, the app uses Redis through `ioredis`
- if `REDIS_ENABLED=false`, the app falls back to in-memory cache

This skill is about cache design in this repository, not generic Redis operations.

## Use This Skill For

- adding cache reads/writes to services
- defining key naming conventions
- choosing TTL values
- invalidating related cache on writes
- keeping cache keys tenant-aware
- reviewing whether list caching will go stale
- coordinating data cache with HTML cache headers

Do not use this skill for:

- low-level Redis administration
- distributed locking unless the repo explicitly adds it
- queue design unless cache behavior is directly involved

## Read First

Before changing cache logic, inspect in this order:

1. `src/infrastructure/cache/cache.service.ts`
2. `src/infrastructure/cache/redis.provider.ts`
3. the active service using cache
4. the repository used by that service
5. `src/core/http/html-cache.interceptor.ts` if page caching is involved

For example cache usage, inspect:

- `src/modules/crm/services/crm-contact.service.ts`

## Workless Cache Rules

### 1. Cache at the Service Layer

Cache orchestration belongs in services, not controllers.

Preferred pattern:

- service builds the key
- service checks cache
- service fetches from repository on miss
- service writes back to cache

### 2. Keep Keys Tenant-Aware

Every cache key for business data must include tenant scope.

Preferred pattern:

```text
<module>:<tenantId>:<resource>:...
```

Examples:

- `crm:<tenantId>:contacts:<id>`
- `crm:<tenantId>:contacts:list:<version>:<digest>`
- `crm:<tenantId>:dashboard:<version>`

### 3. Prefer Explicit Invalidation

On create, update, or delete:

- delete detail cache for the affected record
- invalidate collection/list/dashboard cache

Prefer deterministic invalidation over wildcard key scans.

In this repo, the preferred pattern is:

- store a collection version key
- include that version in list/dashboard keys
- delete the version key on writes

This is already used in CRM and should be the default model for new modules.

### 4. Keep TTLs Intentional

Choose TTL by data volatility:

- detail views: longer TTL if data changes less often
- list endpoints: medium TTL
- dashboards/summaries: short TTL

Do not use one TTL for everything.

Current CRM examples:

- dashboard: 60 seconds
- list: 120 seconds
- detail: 300 seconds

Reuse those values only when the data profile is similar.

### 5. Separate Data Cache From HTML Cache

Data cache:

- handled through `CacheService`
- protects repository/database load

HTML cache:

- handled by response headers and reverse proxy behavior
- configured through `HtmlCacheInterceptor` and related decorators

Do not confuse these layers.

If a page is cacheable at the HTML level, still decide separately whether its underlying service data should be cached.

## Recommended Patterns

### Detail Endpoint

- key includes tenant + record id
- cache hit returns DTO/view directly
- update/delete must clear the detail key

### List Endpoint

- normalize query before hashing
- build a deterministic digest from normalized query
- include tenant + collection version + digest in the key

### Dashboard Endpoint

- cache aggregated response
- keep TTL shorter than detail pages
- invalidate via collection version when underlying records change

## Anti-Patterns

Avoid these in Workless:

- cache keys without tenant scope
- wildcard delete as the primary invalidation strategy
- cache logic in controllers
- caching raw request objects
- using generated HTML asset paths as cache keys
- claiming Redis behavior was verified when `REDIS_ENABLED=false`

## Verification

For cache-related changes:

1. confirm the edited service is the active wired service
2. run `npm run build`
3. inspect create/update/delete paths for invalidation
4. inspect list/detail/dashboard reads for tenant-aware keys
5. state clearly whether Redis was actually enabled during verification

## Coordination With Other Skills

Pair this skill with:

- `nestjs`
  when changing providers, module wiring, or interceptors
- `modules`
  when adding cache to a feature module
- `qa-testing`
  when validating cache confidence and runtime limits
