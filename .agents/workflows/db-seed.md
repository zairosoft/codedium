---
description: Seed Workless platform and module data using the active runners
---

# Database Seeding

Use this workflow when preparing local data for Workless.

## Guardrails

- never seed production without explicit confirmation
- inspect existing seed patterns before adding new ones
- keep seeds idempotent where possible
- preserve tenant-safe defaults

## Active Paths

Read these first:

1. `src/database/seeder.runner.ts`
2. `src/database/platform-user-schema.migration.ts`
3. module seeders such as `src/modules/crm/seeders/*`
4. target lifecycle services under `src/modules/*/lifecycle/*`

## Current Reality

- `npm run db:platform` prepares platform IAM schema
- `npm run seed` prepares platform schema and installs discovered modules
- CRM ships with the active seeder example
- helpdesk and org are lifecycle placeholders right now

## Checklist

- seed order matches module dependencies
- tenant defaults are explicit
- seed logic is idempotent
- lifecycle install still remains safe to rerun
- final instructions mention the correct command to run
