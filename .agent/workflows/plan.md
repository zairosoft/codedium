---
description: Create a repository-specific implementation plan for Workless without writing code
---

# Plan

Use this workflow when the user wants planning only.

## Guardrails

- do not write code during planning-only work
- base the plan on the active Workless architecture
- identify whether the work belongs in `src/app`, `src/core`, or `src/modules`
- call out verification and setup commands explicitly

## Planning Checklist

1. restate the goal
2. identify affected layer and files
3. note architecture constraints
4. break work into incremental steps
5. include verification commands
6. include open risks or assumptions

## Workless-Specific Checks

- is it platform code under `src/app`?
- is it runtime engine code under `src/core`?
- is it a plugin feature under `src/modules`?
- does it affect tenant scoping?
- does it affect cache keys or invalidation?
- does it affect lifecycle or registry behavior?

## Useful Commands To Mention

- `npm run build`
- `npm run db:platform`
- `npm run seed`
- `npm run module:list`

## Output Shape

Prefer a short plan with:

- scope
- files
- steps
- verification
- risks
