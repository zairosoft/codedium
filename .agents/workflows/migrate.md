---
description: Migrate Workless frameworks, dependencies, architecture, or code contracts; database schema changes use db-migrate.md
---

# Technology Migration Workflow

Use this workflow for framework upgrades, dependency replacements, architecture moves, language/tooling transitions, and cross-cutting contract changes.

Do not use it for PostgreSQL schema history. Use `db-migrate.md` for database migrations and `db-schema.md` for schema design.

## Active Workless Boundaries

- application/platform code: `src/app`
- shared runtime, interfaces, tenant context, events, cache, registry, and lifecycle: `src/workless`
- plugin modules: `src/modules`
- database runtime, migrations, and seeders: `src/database`
- runtime module list: `src/modules/modules.ts`

Inspect source before trusting older architecture notes. Do not reintroduce stale `src/core`, `src/common`, or generic starter-kit paths.

## Scope Definition

Before editing, establish:

- source and target technology/version/contract
- affected runtime boundaries
- compatibility window
- persistence or external-state impact
- acceptance checks
- rollback point

Ask only when the missing choice would materially change the migration.

## Inventory

1. inspect `package.json`, TypeScript config, and application bootstrap
2. locate imports, providers, controllers, entities, and runtime registrations
3. locate shared interfaces and all consumers
4. inspect environment variables and deployment assumptions
5. identify database, cache, event, hook, and tenant effects
6. inspect the working tree and preserve unrelated edits

For dependency or framework upgrades, use official release notes and migration guides.

## Plan by Boundary

Prefer stages that leave the repository coherent:

1. introduce compatible contracts or adapters
2. migrate providers and internal consumers
3. migrate plugin modules and runtime registration
4. deliver database changes through `db-migrate.md`
5. migrate cache/external state with explicit invalidation or backfill
6. remove legacy paths after all consumers move
7. update `.agents`, commands, and user-facing documentation

Avoid mixing unrelated framework, schema, UI, and cleanup work in one change.

## Workless Invariants

- `src/app` must not depend on plugin implementations.
- Plugin modules must not import application services directly.
- Shared contracts belong under `src/workless/interfaces`.
- Controllers stay thin; services orchestrate; repositories own persistence; policies own authorization.
- Runtime module loading must continue to tolerate absent plugin directories.
- Tenant-aware queries and cache keys must retain tenant scope.
- Module lifecycle metadata, Nest providers, exports, migrations, and seeders must move together.
- Do not use compatibility defaults that weaken production secrets or authorization.

## Verification Matrix

| Change | Minimum verification |
| --- | --- |
| TypeScript/import/provider change | `npm run build` |
| Database contract | build plus authorized migration status/apply/inspection |
| Module lifecycle | relevant `module:*` command in a configured environment |
| Seeder | run twice against a disposable database |
| Cache/Redis | runtime check with configured Redis or documented limitation |
| UI/rendering | build plus browser/render check |

`npm test` is currently a placeholder and is not evidence of automated test coverage.

## Completion Criteria

- target implementation and all consumers agree on the new contract
- runtime registration and dependency injection resolve
- old and new paths do not conflict
- data/cache/external-state transitions are handled explicitly
- rollback or recovery steps exist for risky changes
- relevant verification actually ran
- `.agents` guidance reflects the active repository
