---
name: modules
description: "Workless module architecture skill for creating, refactoring, and validating self-contained modules under src/modules with system registry, lifecycle, hooks, tenant scope, and cache integration."
category: architecture
risk: medium
source: project
date_added: "2026-04-03"
---

# Modules

Use this skill when working on module-oriented changes in the Workless NestJS monolith.

## Purpose

This skill focuses on the project-specific module structure and runtime conventions used in this repository:

- feature modules live under `src/modules/<module-name>`
- modules should stay self-contained
- cross-module communication should prefer hooks/events over direct coupling
- module state is managed through the system registry and lifecycle services
- tenant-awareness and cache invalidation are part of the design, not an afterthought

## When to Use

Use this skill for tasks such as:

- creating a new module under `src/modules`
- refactoring an existing feature into the module structure
- adding controllers, services, repositories, hooks, policies, seeders, or migrations to a module
- wiring a module into the system registry/lifecycle flow
- reviewing whether a module violates isolation boundaries
- adding tenant-aware cache keys and invalidation to module read/write flows

Do not use this skill for:

- generic NestJS framework issues that are not module-architecture specific
- frontend styling work
- isolated TypeScript typing problems with no module design impact

## Expected Module Shape

Typical layout:

```text
src/modules/<module-name>/
  controllers/
  dto/
  hooks/
  migrations/
  models/
  policies/
  repositories/
  seeders/
  services/
  views/
  module.ts
```

Not every folder must exist for every module, but the structure should remain consistent.

## Project Rules

1. Keep controllers thin.
2. Put orchestration in services.
3. Keep business behavior close to entities/models where practical.
4. Repositories own persistence concerns.
5. Use hooks/events for extension points instead of direct module-to-module calls.
6. Respect tenant scoping on entities, queries, and cache keys.
7. Invalidate cache on create/update/delete paths.
8. Match the repository naming and file layout already used in `src/modules/crm`.

## Runtime Integration

When a module participates in runtime lifecycle management, check and update:

- `src/core/system/system-module.decorator.ts`
- `src/core/system/system-module.interface.ts`
- `src/core/registry/module.registry.ts`
- `src/core/lifecycle/module.lifecycle.ts`

Typical lifecycle responsibilities:

- `install(context)`
- `uninstall(context)`
- `upgrade(context, fromVersion)`

## Implementation Pattern

When creating or refactoring a module:

1. Inspect an existing module first, especially `src/modules/crm`.
2. Create the directory structure under `src/modules/<module-name>`.
3. Add `module.ts` and register controllers/providers clearly.
4. Add DTO validation with `class-validator`.
5. Add repositories for TypeORM access.
6. Add service-level cache read/write behavior where needed.
7. Add hooks for extensibility before/after critical operations.
8. Add lifecycle wiring only if the module needs install/upgrade/uninstall behavior.
9. Verify imports do not create unnecessary direct dependencies across modules.
10. Run a build after changes.

## Cache Guidance

Prefer tenant-aware keys such as:

- `<module>:<tenantId>:entity:<id>`
- `<module>:<tenantId>:list:<version>:<queryHash>`
- `<module>:<tenantId>:dashboard:<version>`

For invalidation, prefer deleting namespace-version keys over scanning Redis keys.

## Review Checklist

- [ ] Module files stay under `src/modules/<module-name>`
- [ ] No direct cross-module dependency was introduced without a strong reason
- [ ] DTO validation exists for public input
- [ ] Repository methods are tenant-aware
- [ ] Cache invalidation exists for write operations
- [ ] Hooks/events are used for extension points
- [ ] Lifecycle wiring is added only when needed
- [ ] `npm run build` passes

## Reference Module

Use `src/modules/crm` as the primary reference for:

- directory shape
- controller/service split
- repository pattern
- hook integration
- module lifecycle service
- cache key patterns

## Success Criteria

- The module matches the existing repository conventions.
- The module is isolated and maintainable.
- Runtime lifecycle behavior is explicit when required.
- Tenant and cache behavior are correct.
- The application still builds cleanly.
