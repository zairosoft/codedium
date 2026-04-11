---
description: Refactor Workless incrementally while preserving active behavior
---

# Refactor

Use this workflow to refactor Workless incrementally.

## Guardrails

- never redesign from scratch unless explicitly asked
- preserve active behavior
- confirm the active path before editing when duplicate or scaffold files exist
- keep platform, core, and module boundaries intact
- do not reintroduce removed architecture paths

## Steps

### 1. Confirm Scope

Identify:

- active files vs scaffold or legacy files
- relevant layer: `src/app`, `src/core`, or `src/modules`
- current verification path
- coupling, duplication, or stale abstractions

### 2. Analyze Risks

Look for:

- mixed responsibilities
- direct cross-module coupling
- module imports into app services
- legacy `models/` usage inside backend paths
- tenant leaks in queries or cache keys
- lifecycle or registry drift

### 3. Plan Small Changes

Common patterns:

- extract function
- move code to the right layer
- replace direct coupling with hooks or events
- remove backend `models/` usage and move shared contracts to `src/core/interfaces`, persistence shapes to `entities`, and request validation to `dto`
- normalize cache key construction
- remove stale path references

### 4. Execute Incrementally

- make one coherent change at a time
- re-check Nest wiring when providers or modules change
- keep runtime module loading resilient

### 5. Verify

Use the lightest useful check:

- `npm run build`
- inspect module wiring
- inspect lifecycle and registry flow
- inspect tenant and cache behavior when touched

Setup commands when relevant:

- `npm run db:platform`
- `npm run seed`
