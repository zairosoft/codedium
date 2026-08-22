---
description: Change Workless PostgreSQL schemas through the repository's custom QueryRunner migration system
---

# Database Migration Workflow

Use this workflow for table, column, constraint, index, enum, or data migration work. Use `migrate.md` for framework or architecture migrations.

## System in Use

Workless uses a custom migration layer built on TypeORM `QueryRunner`:

- contract: `src/database/migration.interface.ts`
- execution and history: `src/database/migration.service.ts`
- database CLI: `src/database/migration.runner.ts`
- database migration registry: `src/database/migrations/migrations.ts`
- module lifecycle integration: `src/workless/lifecycle/module.lifecycle.ts`

Do not use TypeORM migration generation, TypeORM CLI discovery, or `synchronize` as a substitute for this system.

## Migration Scopes

| Scope | Location | Registration | History key |
| --- | --- | --- | --- |
| Database | `src/database/migrations` | `migrations.ts` | `scope = platform`, `moduleName = NULL` |
| Module | `src/modules/<module>/migrations` | `@SystemModule({ migrations })` | `scope = module`, module name |

Both scopes write to the `migrations` table. Execution is serialized with PostgreSQL advisory locks.

## Read Order

1. `src/database/migration.interface.ts`
2. `src/database/migration.service.ts`
3. the applicable migration registry or module lifecycle metadata
4. earlier migrations for the same tables
5. affected entities, repositories, services, and seeders
6. `src/database/typeorm.config.ts`

## Creating a Database Migration

Use this filename format:

```text
<YYYYMMDDHHmm>-<action>-<subject>.migration.ts
```

Example:

```text
202607120002-create-users.migration.ts
```

Implement `WorklessMigration`:

```ts
export class CreateExampleMigration implements WorklessMigration {
  readonly name = 'create-example';
  readonly timestamp = 202607120003;
  readonly checksum = 'create-example-v1';

  async up(queryRunner: QueryRunner): Promise<void> {}
  async down(queryRunner: QueryRunner): Promise<void> {}
}
```

Register the class in `src/database/migrations/migrations.ts`. The service sorts by the numeric `timestamp`; array order is not the source of truth.

## Creating a Module Migration

Place it under `src/modules/<module>/migrations`, implement the same `WorklessMigration` contract, and add it to the module lifecycle decorator:

```ts
@SystemModule({
  migrations: [FirstMigration, NextMigration],
})
```

Module migrations may use descriptive filenames already established by the module. Their numeric timestamps still determine execution order.

## Invariants

- `name` must be unique within the selected scope.
- `timestamp` must be a safe integer and unique in the intended sequence.
- `checksum` is a manual version marker. The service hashes `name:timestamp:checksum`; it does not hash migration source code.
- If an applied migration's marker changes, execution stops with a checksum mismatch.
- Never edit an applied migration to deliver a new production schema state; add a later migration.
- Editing or consolidating initial migrations is acceptable only when the target databases are disposable and the user explicitly wants history rewritten.
- Migrations run in a transaction by default. Set `readonly transaction = false` only for PostgreSQL operations that cannot run in a transaction.
- `down` must reverse only what that migration introduced. Do not drop shared or pre-existing objects.
- Keep migration schema and TypeORM metadata aligned explicitly; `synchronize` defaults to off.

## Schema Change Checklist

- inspect current table and index existence before assuming state
- preserve data during rename/type changes
- backfill before adding `NOT NULL`
- add foreign-key indexes when queries join through them
- choose deliberate `ON DELETE` and `ON UPDATE` behavior
- account for soft-deleted rows in unique constraints
- update entities, repositories, raw SQL, DTOs, services, and seeders that use changed names
- avoid destructive operations without confirmation and recovery steps

## Commands

| Action | Command |
| --- | --- |
| Apply database migrations | `npm run db:migrate` |
| Database status | `npm run db:migrate:status` |
| Revert last database migration | `npm run db:migrate:revert` |
| Apply one module | `npm run module:migrate -- <name>` |
| Apply all discovered modules | `npm run module:migrate -- --all` |
| Module status | `npm run module:migrate:status -- <name>` |
| Revert last module migration | `npm run module:migrate:revert -- <name>` |

`npm run db:platform` currently aliases `npm run db:migrate`. Module install, upgrade, and seed also apply pending migrations for that module.

## Verification

1. Run `npm run build`.
2. Run the appropriate status command.
3. Apply only to an authorized database.
4. Inspect the resulting columns, constraints, indexes, foreign keys, and history row.
5. Test `down` only against a disposable database unless rollback was explicitly requested.
6. Never claim runtime migration success from a TypeScript build alone.
