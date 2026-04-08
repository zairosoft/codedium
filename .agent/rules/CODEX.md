# Workless Codex Rules

Use these rules when operating in the `workless` repository.

## First Principles

- read `.agent/ARCHITECTURE.md` before major edits
- inspect the real implementation in `src/` before trusting `.agent` templates
- use the smallest relevant skill from `.agent/skills`
- keep responses and edits grounded in the current repo, not in copied starter kits

## Project Assumptions

- backend is NestJS, not Laravel and not a React SPA
- modules live under `src/modules`
- runtime module behavior lives under `src/core`
- cache lives under `src/infrastructure/cache`
- tenant context is part of normal request handling
- Vite and Tailwind compile source assets into `public/assets`
- server-rendered pages should usually live under `src/modules/<module>/views`
- `src/infrastructure/redis/*` is legacy unless the task explicitly targets it

## Editing Expectations

- keep controllers thin
- keep orchestration in services
- keep persistence concerns in repositories
- prefer hooks/events over direct cross-module coupling
- make cache behavior explicit
- make tenant scope explicit
- avoid editing generated assets unless the user asks for the built file specifically

## Verification Expectations

- use `npm run build` as the default verification baseline
- inspect wiring when changing Nest modules, lifecycle, cache, or tenant flow
- do not state that tests passed unless you actually ran them

## Hygiene

- if `.agent/workflows` or `.agent/scripts` are generic, say so and verify before relying on them
- if `.agent` and source code disagree, follow the source code
