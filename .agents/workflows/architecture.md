---
description: Review or document Workless architecture from the active codebase
---

# Architecture

Use this workflow to inspect or explain Workless architecture without drifting into generic diagrams.

## Guardrails

- base everything on active code paths
- identify platform, core, and module boundaries first
- call out optional or scaffold-only paths explicitly
- prefer one focused diagram over one giant diagram

## Read Order

1. `src/app.module.ts`
2. `src/app/platform.module.ts`
3. `src/core/core.module.ts`
4. `src/modules/runtime-modules.ts`
5. target files under `src/app`, `src/core`, or `src/modules`

## What To Capture

- platform services under `src/app`
- core engine responsibilities under `src/core`
- database bootstrap and config under `src/database`
- plugin modules under `src/modules`
- tenant flow through `src/core/tenant`
- registry and lifecycle flow through `src/core/registry` and `src/core/lifecycle`
- cache flow through `src/core/infrastructure/cache` and `src/core/http`

## Diagram Guidance

Prefer Mermaid when needed.

Examples:

- high-level container diagram
- module lifecycle flow
- tenant and cache flow
- CRM request path from controller to repository

## Output Checklist

- names match active files
- deprecated paths are not shown as active
- optional runtime modules are marked optional
- module boundaries are explicit
