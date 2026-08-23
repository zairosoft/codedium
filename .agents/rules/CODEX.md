# Workless Codex Rules

Use these rules when operating in the `workless` repository.

## Read Order

Before substantial work:

1. read the root `ARCHITECTURE.md`
2. inspect the active implementation under `src/`
3. read `src/app.module.ts`, `src/workless/workless.module.ts`, and `src/modules/modules.ts` when changing structure or wiring
4. load only the smallest relevant skill from `.agents/skills/`

The active source code wins when documentation and implementation disagree.

## Repository Structure

- application features live under `src/app`
- shared runtime behavior lives under `src/workless`
- database migrations and seeders live under `src/database`
- installable modules live under `src/modules`
- shared application contracts live under `src/app/interfaces`
- module-owned contracts live under `src/modules/<module>/interfaces`
- tenant request context lives under `src/workless/tenant`
- cache infrastructure lives under `src/workless/infrastructure/cache`

## Coding Rules

- keep controllers thin
- keep use-case orchestration in services
- keep persistence behavior in repositories and entities
- keep authorization and business rules in policies
- validate public input with DTOs
- use hooks or events instead of direct coupling between business modules
- keep repository queries and cache keys tenant-aware
- add module interfaces only for real contracts, ports, reusable types, or exported capabilities
- do not introduce a backend `models/` directory; use entities, DTOs, interfaces, repositories, and explicit view types

## Verification

Choose checks proportional to the change. The primary baseline is:

    npm run build

Useful environment-dependent commands:

    npm run db:migrate:status
    npm run db:migrate
    npm run seed

`npm test` is currently a placeholder. Do not claim compilation, tests, database behavior, or Redis behavior was verified unless the relevant check actually ran.

## Documentation

- keep `ARCHITECTURE.md` focused on stable coding structure and dependency rules
- keep `README.md` focused on setup and day-to-day usage
- update the relevant `.agents/skills/` entry when an established project pattern changes
