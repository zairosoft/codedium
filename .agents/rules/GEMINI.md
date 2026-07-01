---
trigger: always_on
---

# Workless Gemini Rules

These rules keep agent behavior aligned with the actual Workless repository.

## Read Order

Before substantial work:

1. read `.agent/ARCHITECTURE.md`
2. inspect the active code under `src/`
3. load only the smallest relevant skill from `.agent/skills`

## Repository Reality

Assume the following unless the code proves otherwise:

- Workless is a NestJS modular monolith
- platform code lives in `src/app`
- core runtime, contracts, tenant, and cache infrastructure live in `src/core`
- database bootstrap and TypeORM config live in `src/database`
- plugin modules live in `src/modules`
- module loading is resilient and driven through `src/modules/runtime-modules.ts`
- generated assets are not the source of truth
- module-owned views are preferred over a shared `src/views` path

## Editing Rules

- prefer repo-specific patterns over generic framework advice
- keep controllers thin and orchestration in services
- keep persistence concerns in repositories and entities
- prefer event-driven or hook-driven extension over direct module coupling
- keep queries and cache keys tenant-aware
- do not reintroduce deprecated paths under `src/infrastructure` or `src/common`

## Verification Rules

Use the lightest verification that matches the change.

Default baseline:

- `npm run build`

Setup commands when needed:

- `npm run db:platform`
- `npm run seed`

Do not claim tests ran unless they actually ran.

## Conflict Rule

If `.agent` content conflicts with the codebase, the codebase wins.
