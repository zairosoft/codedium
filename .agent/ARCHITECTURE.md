# Workless Agent Architecture

This file is the agent-facing architecture note for the `workless` repository.

## Purpose

Use `.agent` in this repo to:

1. keep project-specific context for AI agents
2. document the active architecture and commands
3. reduce mistakes from generic templates copied from other projects

If `.agent` and `src/` disagree, follow the codebase.

## Current Repository Reality

Workless is a NestJS modular monolith with three active layers:

1. platform layer under `src/app`
2. core engine under `src/core`
3. plugin modules under `src/modules`

Current stack:

- NestJS 11
- TypeORM + PostgreSQL
- optional Redis via `ioredis`
- tenant context via middleware + async local storage
- server-rendered HTML views for module pages
- Vite + Tailwind for frontend assets

This repo is not a React SPA and should not be treated like one.

## Runtime Layout

Main application wiring:

- `src/app.module.ts`
- `src/main.ts`

Platform layer:

- `src/app/platform.module.ts`
- `src/app/auth/*`
- `src/app/users/*`
- `src/app/roles/*`
- `src/app/permissions/*`
- `src/app/notifications/*`

Core engine:

- `src/core/core.module.ts`
- `src/core/system/*`
- `src/core/registry/*`
- `src/core/lifecycle/*`
- `src/core/events/*`
- `src/core/interfaces/*`
- `src/core/tenant/*`
- `src/core/http/*`
- `src/core/infrastructure/*`

Plugin modules:

- `src/modules/runtime-modules.ts`
- `src/modules/crm/*`
- `src/modules/helpdesk/*`
- `src/modules/org/*`

`src/modules/apps` is scaffold-only right now and is not part of runtime loading.

## Dependency Rules

Assume these boundaries are intentional:

- `src/app` must not depend on `src/modules`
- modules must not import other modules directly
- modules must not import app services directly
- modules may depend on `src/core/interfaces/*`
- cross-domain communication should use hooks or emitted events

## Module Backend Shape

For backend work inside `src/modules/*`, assume the strict module shape is:

```text
src/modules/<module>/
  controllers/
  services/
  entities/
  repositories/
  dto/
  policies/
  hooks/
  lifecycle/
  seeders/
  views/
  module.ts
```

Important:

- backend logic should not rely on `models/` under modules
- services own use-case and domain orchestration
- policies own permission and rule checks
- repositories own data access
- `views/` exists, but backend-only refactors should usually ignore it unless the response contract itself must change

## Active Infrastructure Paths

Database and cache infrastructure now live under:

- `src/core/infrastructure/database/*`
- `src/core/infrastructure/cache/*`

Tenant logic now lives only under:

- `src/core/tenant/*`

System contracts now live only under:

- `src/core/interfaces/*`

Do not reintroduce older paths such as:

- `src/infrastructure/*`
- `src/common/tenant/*`
- `src/app/interfaces/*`

## Runtime Module Behavior

Runtime modules are loaded through:

- `src/modules/runtime-modules.ts`

This is intentionally resilient. If a plugin module is missing from disk, the app should skip it instead of crashing on a static import.

Lifecycle and registry behavior:

- lifecycle service: `src/core/lifecycle/module.lifecycle.ts`
- lifecycle CLI: `src/core/lifecycle/module-lifecycle.runner.ts`
- registry service: `src/core/registry/module.registry.ts`

Registry should only track discoverable plugin modules.

## Tenant and Cache Reality

Tenant flow:

1. `TenantContextMiddleware` reads `x-tenant-id`
2. `TenantContextService` stores normalized tenant state in async local storage
3. repositories must query by `tenantId`
4. cache keys must include tenant scope

Current cache conventions:

- cache abstraction: `src/core/infrastructure/cache/cache.service.ts`
- HTML cache headers: `src/core/http/html-cache.interceptor.ts`
- module cache keys should include module name and tenant ID
- CRM currently uses both tenant-level collection versions and a module-level cache version

## Views and UI

Server-rendered layout and shared UI live under:

- `src/app/components/layouts/*`

Module-owned pages and mappers live under:

- `src/modules/<module>/views/*`

Do not invent a shared `src/views` layer unless the codebase moves in that direction later.

## Commands That Matter

Current useful commands from `package.json`:

- `npm run build`
- `npm run db:platform`
- `npm run seed`
- `npm run module:list`
- `npm run module:install -- <name>`
- `npm run module:upgrade -- <name>`
- `npm run module:uninstall -- <name>`

Notes:

- `npm test` is still a placeholder
- `db:platform` prepares platform IAM schema
- `seed` prepares platform schema and installs discovered modules

## Agent Guidance

Recommended order for most tasks:

1. read this file
2. inspect active code paths in `src/`
3. load the smallest relevant skill from `.agent/skills`
4. use workflows only when they match the real repo
5. verify with the lightest command that proves the change

## Maintenance Rules

Update `.agent` whenever any of these change:

- active paths under `src/core`
- platform/module boundaries
- runtime module loading behavior
- tenant or cache architecture
- package scripts used for verification or data setup
- skill or workflow guidance that points at moved files
