# Workless Agent Architecture

This document is the agent-facing architecture note for the `workless` repository. It replaces the generic Antigravity description that was previously copied into `.agent`.

## Purpose

Use `.agent` in this repo for three things:

1. keep project-specific operating context for AI agents
2. provide reusable skills and workflows for repeated tasks
3. reduce incorrect assumptions from generic web or frontend templates

This file should describe the current reality of Workless, not an idealized toolkit.

## Project Reality

Workless is a NestJS monolith with modular ERP-style runtime behavior.

Current stack and architecture:

- NestJS 11
- TypeORM + PostgreSQL
- Redis-backed cache support under `src/infrastructure/cache`
- tenant context via middleware + async local storage
- server-side MVC/views plus static assets in `public`
- Vite + Tailwind CSS for asset compilation
- module registry + lifecycle + hooks under `src/core`

This repo is not a React app and should not be treated like a SPA-first codebase unless the code changes in that direction later.

## Runtime Layout

Main application wiring:

- `src/app.module.ts`
- `src/main.ts`

Core runtime:

- `src/core/core.module.ts`
- `src/core/system/*`
- `src/core/registry/*`
- `src/core/lifecycle/*`
- `src/core/events/*`

Infrastructure:

- `src/infrastructure/database/*`
- `src/infrastructure/cache/*`
- `src/infrastructure/redis/*` as older/legacy support code
- `src/infrastructure/queue/*`

Tenant flow:

- `src/common/tenant/tenant-context.middleware.ts`
- `src/common/tenant/tenant-context.service.ts`

Business modules:

- `src/modules/auth`
- `src/modules/users`
- `src/modules/org`
- `src/modules/crm`
- `src/modules/helpdesk`
- `src/modules/permissions`
- `src/modules/apps`
- `src/modules/notifications`

Frontend asset flow:

- source CSS: `src/styles/app.css`
- build config: `vite.config.ts`
- generated assets: `public/assets/*`
- tests/config for Vite tooling: `vitest.config.ts`

## Important Codebase Rules

Agents working in this repo should assume:

- controllers stay thin
- services orchestrate
- repositories own persistence
- module-to-module coupling should prefer hooks/events
- tenant awareness is mandatory for entity access and cache keys
- cache invalidation must happen on create/update/delete paths
- generated files in `public/assets` are not the source of truth

If you are changing module runtime behavior, read `src/core` before editing a feature module.

## Known Repository Edges

The repo contains some mixed-age structure. Do not assume every duplicate file is active.

Examples:

- `src/modules/crm` contains the newer runtime-oriented structure
- some CRM legacy duplicates still exist beside the newer files
- `.agent/workflows/*` includes many generic templates copied from other contexts
- `.agent/scripts/*` still contain Antigravity-era wording and references to skills that are not present in this repo

Treat copied generic material as optional scaffolding, not authoritative truth.

## .agent Layout

Current `.agent` structure:

```text
.agent/
  ARCHITECTURE.md
  mcp_config.json
  rules/
  scripts/
  skills/
  workflows/
  .shared/
```

### `skills/`

These are the project-relevant skills currently present:

- `nestjs`
  NestJS skill tuned for Workless runtime layout, tenant flow, registry/lifecycle wiring, and cache integration
- `modules`
  module architecture skill for `src/modules/*`
- `theme`
  theme/view adaptation skill aligned to Vite + Tailwind + server-rendered output
- `tailwind`
  Tailwind usage guidance
- `qa-testing`
  testing and verification guidance for this repo
- `i18n-localization`
  localization-related guidance and helper script

Skills should be small, repo-aware, and practical. They should not read like generic framework encyclopedias.

### `workflows/`

The workflow directory contains reusable markdown procedures such as:

- `architecture.md`
- `api-docs.md`
- `refactor.md`
- `status.md`
- `test.md`
- `deploy.md`

Many of these were imported from a broader template set. Use them as task prompts and checklists, but verify them against the actual Workless codebase before following them literally.

### `scripts/`

Current scripts:

- `checklist.py`
- `verify_all.py`
- `auto_preview.py`
- `session_manager.py`

These scripts are not yet fully aligned with the current skill set in this repo. Some still reference missing skills or older Antigravity conventions. Before relying on them for automation, inspect the referenced paths.

### `.shared/`

`.agent/.shared/ui-ux-pro-max` is shared reference material. It is useful as inspiration or pattern data, but it is not the architecture source of truth for Workless.

## How Agents Should Use `.agent`

Recommended order for most tasks:

1. read this file
2. inspect the real code paths in `src/`
3. load the smallest relevant skill from `.agent/skills`
4. use a workflow from `.agent/workflows` only if it matches the real repo
5. verify against current scripts, package commands, and runtime structure

Do not let `.agent` override what the codebase actually does.

## Verification Baseline

For this repository, the safest baseline checks are:

- `npm run build`
- inspect relevant Nest module wiring
- inspect Vite/Tailwind paths when frontend assets are involved

Do not assume a complete automated test suite exists. At the time of writing, `npm test` is a placeholder command.

## Maintenance Rules

Update this file when any of the following changes:

- core runtime paths under `src/core`
- module inventory under `src/modules`
- asset pipeline layout
- tenant or cache architecture
- `.agent/skills` inventory
- `.agent/scripts` that become project-specific and reliable

Keep this file concise, factual, and tied to the repository as it exists now.
