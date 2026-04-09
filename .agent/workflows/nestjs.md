---
description: Work on the existing Workless NestJS monolith, not a generic starter app
---

# NestJS Workflow

Use this workflow when the task is NestJS-specific inside Workless.

## Guardrails

- do not scaffold a new app unless explicitly asked
- inspect active module wiring before editing
- preserve platform, core, and plugin boundaries
- verify tenant and cache behavior when those paths are touched

## Read Order

1. `src/app.module.ts`
2. `src/core/core.module.ts`
3. target module or platform path
4. related files under `src/core/interfaces`, `src/core/tenant`, and `src/core/infrastructure`

## Common Tasks

- add or refactor controllers
- adjust providers and DI wiring
- update TypeORM entities or repositories
- fix registry, lifecycle, hook, or event flows
- enforce tenant-aware queries and cache keys

## Verification

Default:

- `npm run build`

When setup is relevant:

- `npm run db:platform`
- `npm run seed`
