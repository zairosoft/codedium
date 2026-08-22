---
description: Design Workless PostgreSQL tables, relationships, constraints, and indexes before implementing custom migrations
---

# Database Schema Workflow

Use this workflow to design or review persistence structures. Deliver schema changes through `db-migrate.md`.

## Repository Context

Workless is a NestJS modular monolith using PostgreSQL and TypeORM. Persistence is split between:

- application/database-owned tables under `src/database` and `src/app`
- plugin-owned tables and entities under `src/modules/<module>`
- shared lifecycle and tenant infrastructure under `src/workless`

Do not introduce another ORM or rely on TypeORM `synchronize` unless changing the persistence stack is the explicit task.

## Read Order

1. active migrations for the affected tables
2. matching TypeORM entities
3. repositories and raw SQL
4. services and DTOs that define read/write behavior
5. seeders and lifecycle metadata
6. tenant context and cache behavior when applicable

The migration is authoritative for deployed schema state, but entity metadata must describe the same final table.

## Ownership Decision

Choose ownership before naming the table:

| Concern | Database/application owned | Module owned |
| --- | --- | --- |
| Migration | `src/database/migrations` | `src/modules/<module>/migrations` |
| Entity | usually `src/app/entities` | `src/modules/<module>/entities` |
| Registration | `migrations.ts` | module lifecycle metadata |
| Data access | application service/repository | module repository |

Modules must not reach into application services to persist their own data.

## Naming Reality

- Table and index names are snake_case, for example `users`, `module_registry`, and `uq_users_email`.
- Column naming is not globally uniform yet. New database migrations may use snake_case, while existing lifecycle and module tables contain camelCase columns.
- Follow the convention of the table being changed. Do not silently rename existing columns for style alone.
- When entity property names and database column names differ, map them explicitly with `@Column({ name: '...' })` and equivalent date/delete decorators.
- Keep constraint and index names explicit and stable.

## Column Design

For each column decide:

- PostgreSQL type and length
- nullability
- default value and where it is generated
- validation or check constraint
- whether it contains secrets or personal data
- whether it participates in audit, tenant, or soft-delete behavior

Preferred type guidance:

| Data | PostgreSQL type |
| --- | --- |
| identifiers | `uuid` |
| dates with timezone | `timestamptz` |
| flags | `boolean` |
| bounded text | `varchar(n)` |
| unbounded text | `text` |
| structured data | `jsonb` |

Use UUIDv7 when ordered UUID generation is required and the generation path supports it. PostgreSQL 17 in the current environment does not provide native `uuidv7()`; do not claim that `gen_random_uuid()` produces v7.

## Keys and Relationships

- Every table needs a deliberate primary key.
- Enforce business identity with a unique constraint or unique index.
- Add foreign keys in migrations, not only relation decorators.
- Index foreign-key columns used for joins or filtering.
- Select `CASCADE`, `RESTRICT`, `SET NULL`, or no action based on domain ownership.
- Use a junction entity/table when a many-to-many relationship has attributes.
- Consider whether soft-deleted rows should continue blocking unique values.

## Index Design

Add indexes from actual access patterns:

- equality lookup: indexed key or unique key
- ordered lists: index matching filter prefix and sort column
- active/non-deleted lists: consider a PostgreSQL partial index
- tenant data: tenant key normally leads composite indexes
- low-cardinality fields such as booleans: avoid standalone indexes without evidence

Every index increases write and storage cost. Do not index every field.

## Cross-Layer Checklist

- migration table and column names match entity metadata
- repository filters include required tenant and soft-delete scope
- DTO validation matches database nullability and length
- password/token fields are never returned accidentally
- cache keys and invalidation reflect ownership changes
- seed data satisfies all constraints
- raw SQL uses the new names

## Verification

1. Review schema against expected queries and write paths.
2. Run `npm run build` after implementation.
3. Apply the migration only when database mutation is authorized.
4. Inspect the real PostgreSQL schema after applying.
5. Verify tenant isolation, uniqueness, foreign keys, soft deletion, and index usage as applicable.

