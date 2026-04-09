---
description: Create a new feature area in the existing Workless repository
---

# Create

Use this workflow when the user wants something new added to this repo.

## Guardrails

- create within the existing Workless architecture
- do not assume greenfield app creation
- decide first whether the work belongs in platform, core, or modules

## Decision Order

1. platform work goes under `src/app`
2. runtime engine work goes under `src/core`
3. plugin feature work goes under `src/modules`

## Typical Create Tasks

- new platform service
- new plugin module
- new controller or repository
- new lifecycle integration
- new tenant-aware cache flow

## Before Building

Clarify only what is necessary:

- target layer
- business goal
- affected domain
- verification expectations

## Verification

- `npm run build`
- `npm run db:platform` when schema setup changes
- `npm run seed` when lifecycle or seed data changes
