---
name: cache-redis
description: Maintain Redis and in-memory data caching in Workless, including company-scoped keys, shared platform keys, TTLs, and invalidation.
---

# Workless Redis Cache

Use this skill when changing Redis configuration, cache keys, TTLs, invalidation,
or cache usage in a Workless service.

## Current Architecture

The active cache implementation is under:

- `src/workless/infrastructure/cache/cache.module.ts`
- `src/workless/infrastructure/cache/cache.interface.ts`
- `src/workless/infrastructure/cache/cache.service.ts`
- `src/workless/infrastructure/cache/cache.store.ts`
- `src/workless/infrastructure/cache/redis.provider.ts`
- `src/workless/infrastructure/cache/company-cache.service.ts`

`CacheModule` is global and is imported by `src/app.module.ts`.

`CompanyContextModule` is also global. `RequestCompanyContextService` stores the
authenticated user's `companyId` in `AsyncLocalStorage`; it is activated by
`CompanyContextGuard` after JWT authentication.

Redis configuration comes from these environment variables:

```text
REDIS_ENABLED=true|false
REDIS_HOST=localhost
REDIS_PORT=6379
REDIS_PASSWORD=
REDIS_DB=0
```

When `REDIS_ENABLED=false`, `CacheService` uses its in-memory store as the
primary store. When Redis is enabled and a normal `CacheService` operation
fails, it logs once per operation and uses a process-local in-memory fallback.
That fallback is not shared between application processes.

`CompanyCacheService` deliberately uses strict cache operations. If Redis is
enabled and unavailable, its company cache operation fails instead of falling
back, because local fallback cannot preserve cross-process company invalidation.

## Cache Types

### Shared platform data

Use `CachePort` / `CacheService` for data intentionally shared by the system,
such as the platform company directory. The caller owns the logical key.

```ts
await this.cache.remember(
  'platform:companies:list:1:20:',
  60,
  () => this.loadCompanies(),
);
```

`CacheService` always adds the physical Redis prefix:

```text
workless:data:<logical-key>
```

Do not add `workless:data:` yourself.

### Company-owned data

Use `CompanyCacheService` for data owned by one company. Do not construct a
company ID in a key manually and do not use a request header as its source.

```ts
await this.companyCache.remember(
  'users',
  { action: 'list', page, limit, search },
  120,
  () => this.usersRepository.findAndCount(/* company-scoped query */),
);
```

The service reads the current `companyId`, validates it as a UUID, canonicalizes
the JSON query, and creates a key shaped like:

```text
workless:data:company:<companyId>:table:<table>:v:<generation-hash>:<query-hash>
```

`table` is a logical cache namespace, not an SQL query. It must match:

```text
^[a-z][a-z0-9_]{0,62}$
```

Use names such as `users`, `orders`, `invoices`, or `dashboard`.

If no authenticated company context exists, company cache operations fail. They
must never quietly become shared cache operations.

## Service Pattern

Put cache orchestration in a service. Repositories remain responsible for
database queries and must still filter by `companyId`.

```ts
const companyId = this.companyContext.requireCompanyId();

return this.companyCache.remember(
  'users',
  { action: 'detail', id },
  300,
  () => this.usersRepository.findOne({ where: { id, companyId } }),
);
```

For values containing `Date`, convert dates to strings before caching and restore
them after reading. Cache values must be JSON serializable. `CacheService`
rejects HTML documents and HTML nested in an object.

## Invalidation

For company-owned data, invalidate logical tables after a successful database
write:

```ts
await this.companyCache.invalidateTable('users');
```

For a value derived from several tables, declare dependencies when reading:

```ts
await this.companyCache.remember(
  'dashboard',
  { period: 'today' },
  60,
  () => this.loadDashboard(),
  ['users', 'orders'],
);
```

Then invalidate the changed table or tables:

```ts
await this.companyCache.invalidateTables('users', 'orders');
```

Invalidation rotates a persistent namespace version in Redis rather than scanning
and deleting every matching key. Old entries remain until their TTL expires but
cannot be read through the new generation key.

Use `del` and `delByPrefix` only for shared platform keys where the exact logical
namespace is known. `delByPrefix` scans Redis with `SCAN` and removes matches with
`UNLINK`; do not use it as the normal company-data invalidation strategy.

## TTL Guidance

Choose TTL by volatility and always make it explicit for service reads:

| Data | Typical TTL |
| --- | ---: |
| Company directory list | 60 seconds |
| Company-owned list | 120 seconds |
| Company-owned detail | 300 seconds |
| Dashboard or summary | 60 seconds |

The raw `CacheService` default is 300 seconds. Do not rely on it when adding a
new service cache.

## Current Consumers

- `src/app/services/companies.service.ts` uses shared platform cache keys for
  company list and company detail.
- `src/app/services/users.service.ts` uses `CompanyCacheService` for users list,
  lookup by ID, and lookup by email, then invalidates `users` on create/update.

Use these services as the active examples. Do not refer to removed CRM or tenant
cache paths.

## HTML Cache

`src/app/providers/html-cache.interceptor.ts` does not put HTML into Redis. It
only sends `Cache-Control`, `Vary`, and `Surrogate-Key` response headers. Its
surrogate key includes the company context when one exists.

Do not mix HTML response caching with the Redis JSON data cache.

## Constraints

- Do not cache raw request or response objects.
- Do not omit company scope from company-owned data.
- Do not bypass `CompanyCacheService` for company-owned data.
- Do not assume `remember()` coalesces a cache miss across multiple application
  processes; it only coalesces concurrent misses in one process.
- Do not add test fixtures, sample company IDs, or test files unless the user
  explicitly asks for tests.
- Do not run a build command that rewrites generated Tailwind CSS unless the user
  explicitly authorizes that output change.

## Review Checklist

1. Confirm whether the data is shared platform data or belongs to one company.
2. For company data, confirm repository queries include `companyId`.
3. Confirm query parameters that change the result are included in the cache
   query object: page, limit, search, sort, filters, and relevant flags.
4. Confirm every successful write invalidates its table and any dependent table
   caches for the current company.
5. Confirm cache values are JSON-safe and date fields are converted deliberately.
6. Check TypeScript with `npx tsc -p tsconfig.build.json --noEmit` when the
   environment is available. State whether an actual Redis server was used.
