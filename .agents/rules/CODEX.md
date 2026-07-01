# Workless Codex Rules

Use these rules when operating in the `workless` repository.

## First Principles

- read `.agent/ARCHITECTURE.md` before major edits
- inspect the active implementation in `src/` before trusting `.agent` templates
- use the smallest relevant skill from `.agent/skills`
- keep guidance grounded in the current repo, not in copied starter kits

## Project Assumptions

- backend is NestJS
- platform code lives under `src/app`
- core engine lives under `src/core`
- plugin modules live under `src/modules`
- contracts live under `src/core/interfaces`
- tenant context lives under `src/core/tenant`
- cache and database infrastructure live under `src/core/infrastructure`
- backend module work should target `controllers/services/entities/repositories/dto/policies/hooks/lifecycle/seeders`
- do not introduce `models/` under backend paths; prefer `entities`, `dto`, and `src/core/interfaces`

## Architecture Rules

- `src/app` must not depend on `src/modules`
- modules must not import other modules directly
- modules must not import app services directly
- cross-domain communication should use hooks or emitted events
- controllers stay thin
- services orchestrate
- repositories own persistence
- entities stay persistence-focused
- policies own authorization and rule checks
- cache behavior and tenant scope must be explicit

## Verification Expectations

Default verification baseline:

- `npm run build`

Environment-specific helpers:

- `npm run db:platform`
- `npm run seed`

Do not claim tests passed unless you actually ran them.

## Hygiene

- if `.agent/workflows` or `.agent/scripts` are generic, say so and verify first
- if `.agent` and source code disagree, follow the source code
- do not reintroduce removed paths like `src/infrastructure/*` or `src/common/tenant/*`
