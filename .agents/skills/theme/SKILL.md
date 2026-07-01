---
name: theme-factory
description: Build or adapt UI themes for the Workless repository. Use when converting borrowed templates, shaping server-rendered pages, or aligning Vite/Tailwind output with Workless without assuming React.
---

# Theme Factory

Use this skill when UI or theme work must fit the current Workless stack.

## Project Reality

Workless is not a React app.

Current frontend assumptions:

- Tailwind is compiled through `vite.config.ts`
- generated assets go to `public/assets`
- HTML may be server-rendered from Nest views
- backend routes and rendered pages matter more than SPA conventions

Current repo edge:

- `vite.config.ts` references `src/styles/app.css`
- that file is currently missing in this workspace
- verify the real asset source path before claiming a frontend build path is active

## Use This Skill For

- adapting HTML/theme snippets into Workless-compatible pages
- shaping dashboard or admin-style views
- deciding what belongs in module `views/`, `src/components/layouts/`, or generated `public/assets`
- keeping visual work aligned with Tailwind + Vite in this repo
- cleaning up template-heavy UI into reusable Workless-friendly structure

## Do Not Assume

- React components
- Next.js routing
- client-side SPA hydration
- separate frontend repo

If the user explicitly asks for React, treat that as a new requirement and confirm architecture impact first.

## Preferred Reading Order

Before editing UI/theme files, inspect:

1. `vite.config.ts`
2. `src/components/layouts/`
3. related files under `src/modules/<module>/views/`
4. `public/assets/`
5. any theme HTML source referenced by layout helpers

## Workless Placement Rules

Use these destinations consistently:

- `src/modules/<module>/views/`
  Module-owned page builders or view helpers
- `src/components/layouts/`
  Shared layout fragments or page shell helpers
- `public/assets/`
  Generated/build output only

Do not edit generated CSS in `public/assets` unless the user explicitly asks to patch the built artifact.

## Theme Adaptation Workflow

When adapting an external theme:

1. Identify whether the source is static HTML, CSS, or component code.
2. Strip framework-specific assumptions that do not exist in Workless.
3. Preserve only the visual language, layout ideas, spacing system, and useful patterns.
4. Rebuild the output into:
   - Tailwind classes in source files, or
   - reusable server-rendered view helpers
5. Keep asset paths compatible with `public/assets`.

## Visual Direction

Aim for:

- admin/dashboard-friendly composition
- strong hierarchy and readable density
- reusable shells instead of page-specific hacks
- bold but maintainable Tailwind usage

Avoid:

- copying vendor template structure blindly
- scattering CSS across generated output
- introducing front-end dependencies not already used by the repo

## Verification

At minimum:

1. confirm source files, not generated files, were edited
2. confirm asset paths match current Vite output rules
3. confirm the result still fits server-rendered usage

If the task changes asset generation, also inspect `vite.config.ts`.
