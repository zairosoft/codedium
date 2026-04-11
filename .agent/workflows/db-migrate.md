---
description: Update Workless database schema safely using the active TypeORM-based setup
---

# Database Migrations

Use this workflow when schema work touches Workless persistence.

## Guardrails

- do not run destructive schema changes without confirmation
- inspect the active TypeORM setup first
- keep tenant columns and indexes intact
- prefer idempotent migration-style code for existing tables

## Active Paths

Read these first:

1. `src/database/typeorm.config.ts`
2. `src/database/platform-user-schema.migration.ts`
3. module-local migrations such as `src/modules/crm/migrations/*`
4. affected entities under `src/app/**/entities` or `src/modules/**/entities`

## Current Reality

- Workless uses TypeORM
- platform IAM schema preparation exists at `npm run db:platform`
- module installation and seeding run through `npm run seed`
- CRM currently uses explicit migration-style classes during lifecycle install and upgrade

## Checklist

- entity shape matches intended table shape
- tenant-aware indexes are preserved
- schema setup remains idempotent
- affected module lifecycle code still makes sense
- any required setup command is documented in the final response
