---
description: Create and run Workless database and module seeders safely and idempotently
---

# Database Seeding Workflow

Use this workflow for development samples, bootstrap records, demonstrations, and module fixtures.

## Two Seeder Systems

| Seeder type | Contract | Registration | Resolution |
| --- | --- | --- | --- |
| Database | `src/database/seeders/seeder.interface.ts` | `src/database/seeders/seeders.ts` | instantiated explicitly |
| Module | `src/workless/lifecycle/module-seeder.interface.ts` | `@SystemModule({ seeders })` and Nest providers | resolved through `ModuleRef` |

Database seeders receive a TypeORM `DataSource`. Module seeders receive their dependencies through Nest dependency injection.

## Actual `npm run seed` Flow

`src/database/seeder.runner.ts` performs this sequence:

1. supplies an ephemeral JWT secret only when the seeder process has no usable secret
2. starts a Nest application context with `AppModule`
3. application bootstrap synchronizes discoverable module records in `module_registry`
4. runs registered database seeders in ascending `order`
5. lists discovered modules
6. for each module, applies pending module migrations and then runs its seeders
7. closes the application context

It does not apply database-scope migrations first. Run `npm run db:migrate` before `npm run seed` on a new database.

## Database Seeder Steps

1. Add `src/database/seeders/<subject>.seeder.ts`.
2. Implement `DatabaseSeeder` with a unique `name` and optional numeric `order`.
3. Register an instance in `src/database/seeders/seeders.ts`.
4. Use a transaction for related writes.
5. Make reruns safe with unique business keys, `ON CONFLICT`, or explicit existence checks.

Database seeders are sorted by `order`. Keep order values distinct when deterministic sequencing matters.

## Module Seeder Steps

1. Add `src/modules/<module>/seeders/<subject>.seeder.ts`.
2. Implement `ModuleSeeder` with a unique `name` and optional `order`.
3. Register the class in the module's Nest `providers`.
4. Add the class to `@SystemModule({ seeders: [...] })`.
5. Use a module repository rather than application services.

Module seeders are sorted by `order`, then by `name`. `moduleLifecycle.seed()` applies pending module migrations before resolving seeders.

## Data Rules

- Never seed production or a shared database without explicit confirmation.
- Use reserved example domains such as `example.com` for sample accounts.
- Never store plaintext passwords or access tokens.
- Use the password format expected by the authentication implementation.
- Read configurable sample credentials from environment variables.
- Generate UUIDs through the intended UUID version path; current user samples generate UUIDv7 in Node because PostgreSQL 17 lacks native `uuidv7()`.
- Use explicit tenant IDs for tenant-scoped module records.
- Preserve user-edited records on rerun unless reconciliation is the stated requirement.
- Do not delete broad table contents to make a seeder idempotent.
- Validate required tables early and return an actionable error when migrations are missing.

## Commands

| Action | Command |
| --- | --- |
| Prepare database schema | `npm run db:migrate` |
| Run database and all discovered module seeders | `npm run seed` |
| Seed one module | `npm run module:seed -- <name>` |
| Seed all discovered modules only | `npm run module:seed -- --all` |

`npm run module:seed` does not run database seeders.

## Verification

1. Run `npm run build`.
2. Confirm the target database before running a seeder.
3. Run twice in a disposable environment to prove idempotency.
4. Verify expected row counts and unique keys.
5. Verify password/token storage, foreign keys, tenant scope, and audit fields.
6. Report which command and database mutation actually completed; a build alone does not verify seed behavior.

