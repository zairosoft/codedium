---
name: modules
description: "Workless module architecture skill for creating, refactoring, and validating self-contained plugin modules under src/modules with registry, lifecycle, hooks, tenant scope, and cache integration."
---

# Modules

Use this skill when working on plugin-oriented changes in the Workless NestJS monolith.

## Purpose

This skill focuses on the project-specific module structure and runtime conventions used in this repository:

- plugin modules live under `src/modules/<module-name>`
- modules should stay self-contained
- cross-module communication should prefer hooks or emitted events
- modules may depend on `src/core/interfaces/*`
- module state is managed through the system registry and lifecycle services
- tenant-awareness and cache invalidation are part of the design

## When to Use

Use this skill for tasks such as:

- creating a new plugin module under `src/modules`
- refactoring an existing feature into the plugin structure
- adding controllers, services, repositories, hooks, policies, seeders, or migrations to a module
- removing legacy `models/` usage from a module and replacing it with `entities`, `dto`, or `src/core/interfaces`
- wiring a module into registry and lifecycle flow
- reviewing whether a module violates isolation boundaries
- adding tenant-aware cache keys and invalidation

Do not use this skill for:

- generic NestJS issues with no module architecture impact
- platform-layer work under `src/app`
- isolated TypeScript typing problems with no module design impact

## Expected Module Shape

Typical layout:

```text
src/modules/<module-name>/
  controllers/
  dto/
  entities/
  hooks/
  lifecycle/
  policies/
  repositories/
  seeders/
  services/
  views/
  module.ts
```

Not every folder must be fully implemented, but active modules should stay consistent.

Important:

- do not introduce `models/` under `src/modules/*`
- for backend-only work, treat `views/` as out of scope unless the response contract itself must change

## Project Rules

1. Keep controllers thin.
2. Put orchestration in services.
3. Keep business behavior in services and policies.
4. Repositories own persistence concerns.
5. Use hooks and events for extension points instead of direct module-to-module calls.
6. Modules must not import app services directly.
7. Repository methods and cache keys must stay tenant-aware.
8. Invalidate cache on create, update, and delete paths.
9. Match the structure already used in `src/modules/crm`.
10. Prefer NestJS-native layering: `entities/`, `dto/`, `repositories/`, `services/`, `policies/`, `hooks/`, `lifecycle/`.

## Runtime Integration

When a module participates in runtime lifecycle management, check and update:

- `src/modules/runtime-modules.ts`
- `src/core/system/system-module.decorator.ts`
- `src/core/system/system-module.interface.ts`
- `src/core/registry/module.registry.ts`
- `src/core/lifecycle/module.lifecycle.ts`

Typical lifecycle responsibilities:

- `install(context)`
- `uninstall(context)`
- `upgrade(context, fromVersion)`

## Cache Guidance

Prefer tenant-aware keys such as:

- `<module>:<tenantId>:entity:<id>`
- `<module>:<tenantId>:list:<moduleVersion>:<collectionVersion>:<queryHash>`
- `<module>:<tenantId>:dashboard:<moduleVersion>:<collectionVersion>`

Prefer version-key invalidation over wildcard key scans.

## Review Checklist

- [ ] Module files stay under `src/modules/<module-name>`
- [ ] No direct module-to-module dependency was introduced
- [ ] No direct app service import was introduced
- [ ] DTO validation exists for public input
- [ ] Repository methods are tenant-aware
- [ ] Cache invalidation exists for write operations
- [ ] Hooks or events are used for extension points
- [ ] Lifecycle wiring is explicit when needed
- [ ] `npm run build` still makes sense as the verification baseline

## Reference Module

Use `src/modules/crm` as the primary reference for:

- directory shape
- controller and service split
- repository pattern
- hook integration
- event bus usage
- lifecycle service
- cache key patterns
- no `models/` usage in active backend paths

## Success Criteria

- The module matches repository conventions.
- The module is isolated and maintainable.
- Runtime lifecycle behavior is explicit when required.
- Tenant and cache behavior are correct.
