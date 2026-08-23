---
name: theme-factory
description: Build or adapt themes for Workless server-rendered React TSX views and the active Tailwind/Vite asset pipeline.
---

# Theme Factory

Use this skill when UI or theme work must fit the current Workless stack.

## Project Reality

Workless uses React TSX as a server-rendered view layer through `react-dom/server`. It is not a hydrated SPA.

Current frontend assumptions:

- Tailwind is compiled through `vite.config.ts`
- build output is written under `public/assets`
- HTML is server-rendered from Nest controllers and TSX view helpers
- backend routes and rendered pages matter more than SPA conventions

Active asset paths:

- Tailwind input: `public/assets/css/app.css`
- generated CSS: `public/assets/css/tailwindcss.css`
- shared TSX view helpers: `src/app/views/components`
- module pages: `src/modules/<module>/views`

## Use This Skill For

- adapting HTML/theme snippets into Workless-compatible pages
- shaping dashboard or admin-style views
- deciding what belongs in module `views/`, `src/app/views/components`, or generated assets
- keeping visual work aligned with Tailwind + Vite in this repo
- cleaning up template-heavy UI into reusable Workless-friendly structure

## Do Not Assume

- Next.js routing
- client-side SPA hydration
- separate frontend repo

React components are allowed for server rendering. Treat client-side React state, hydration, or routing as a new architecture requirement.

## Preferred Reading Order

Before editing UI/theme files, inspect:

1. `vite.config.ts`
2. `src/app/views/components/main.tsx`
3. `src/app/views/components/layouts/`
4. related files under `src/modules/<module>/views/`
5. `public/assets/css/app.css`
6. `tailwind.config.js`

## Workless Placement Rules

Use these destinations consistently:

- `src/modules/<module>/views/`
  Module-owned page builders or view helpers
- `src/app/views/components/`
  Shared layout fragments or page shell helpers
- `public/assets/css/app.css`
  Tailwind source entry used by the current scripts
- `public/assets/css/tailwindcss.css`
  Generated output; do not edit directly

Do not edit `public/assets/css/tailwindcss.css` unless the user explicitly asks to patch the built artifact.

## Theme Adaptation Workflow

When adapting an external theme:

1. Identify whether the source is static HTML, CSS, or component code.
2. Strip framework-specific assumptions that do not exist in Workless.
3. Preserve only the visual language, layout ideas, spacing system, and useful patterns.
4. Rebuild the output into:
   - Tailwind classes in source files, or
   - reusable server-rendered React TSX view helpers
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

1. confirm TSX source and/or `public/assets/css/app.css` were edited instead of generated CSS
2. confirm asset paths match current Vite output rules
3. confirm the result still fits server-rendered usage

If the task changes asset generation, also inspect `vite.config.ts`.
