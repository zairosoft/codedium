---
description: Improve code quality, extract functions, reduce duplication
---

# Refactor

Use this workflow to refactor Workless incrementally while preserving behavior.

## Guardrails
- Never change behavior, only structure
- Make small, incremental changes
- Ensure the active verification baseline passes after each change
- Preserve public APIs unless explicitly asked
- Confirm active wiring first when duplicate legacy files exist

## Steps

### 1. Understand Scope
Identify:
- active files vs legacy duplicates
- current verification path, usually `npm run build`
- coupling, duplication, or stale abstractions

### 2. Analyze Code
Identify issues:
- Code duplication
- Long functions/methods
- Deep nesting
- Unclear naming
- Mixed responsibilities

### 3. Plan Refactoring
Common patterns:
- **Extract Function**: Pull out reusable logic
- **Rename**: Improve clarity of names
- **Inline**: Remove unnecessary abstractions
- **Move**: Relocate to better location
- **Simplify Conditionals**: Reduce complexity
- **Unify Active Path**: remove divergence between active and legacy module paths

### 4. Execute Refactoring
Make changes incrementally:
- One refactoring at a time
- Run `npm run build` after each meaningful step
- Re-check module wiring if Nest providers/controllers changed

### 5. Verify
- Build still passes
- Code is more readable
- No behavior changes
- Tenant/cache/lifecycle behavior is still coherent when those paths were touched

## Principles
- Refactor in small steps
- Make the change easy, then make the easy change
- If it hurts, do it more often
