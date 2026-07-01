---
description: Summarize current Workless project status from the real repository state
---

# Status

Use this workflow to summarize current repository status without inventing product metadata.

## Include

1. project path and current branch if checked
2. active architecture summary
3. changed files or worktree status if relevant
4. verification status
5. blocked items such as missing dependencies or services

## Workless Reality

When reporting status, prefer facts such as:

- platform layer under `src/app`
- core engine under `src/core`
- runtime plugin modules under `src/modules`
- current verification command: `npm run build`
- current setup helpers: `npm run db:platform`, `npm run seed`

## Avoid

- generic ecommerce or SaaS boilerplate
- invented feature lists
- claiming preview or tests work unless they were actually checked
