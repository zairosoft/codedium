---
trigger: always_on
---

# Workless Gemini Rules

These rules exist to keep agent behavior aligned with the actual Workless repository.

## Read Order

Before substantial work:

1. read `.agent/ARCHITECTURE.md`
2. inspect the real code under `src/`
3. load only the smallest relevant skill from `.agent/skills`

Do not treat copied workflow templates or legacy scripts as source of truth.

## Repository Reality

Assume the following unless the code proves otherwise:

- Workless is a NestJS monolith
- it is not a React app
- tenant handling matters
- module lifecycle, registry, hooks, and cache live under `src/core` and `src/infrastructure`
- Vite + Tailwind are used for asset compilation
- generated assets in `public/assets` are not the primary source files
- module-owned views are preferred over a shared `src/views` path
- legacy Redis scaffolding should not be used for new cache work by default

## Editing Rules

- prefer repo-specific patterns over generic framework advice
- keep controllers thin and business orchestration in services/models/repositories
- prefer hook/event-driven extension over direct module-to-module coupling
- keep cache keys and data access tenant-aware
- invalidate cache on create/update/delete paths
- do not edit build output unless the task explicitly targets generated files

## Verification Rules

Use the lightest verification that matches the change.

Default baseline for this repo:

- `npm run build`

Do not claim tests were run unless they were actually run. Do not assume `.agent/scripts/*` are valid end-to-end without checking their referenced paths first.

## Documentation Rules

- keep `.agent` content concise and repository-specific
- remove or call out generic template language when found
- prefer factual guidance over aspirational process language

## Conflict Rule

If `.agent` content conflicts with the codebase, the codebase wins.
