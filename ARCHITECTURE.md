# Workless Architecture

## Overview

Workless is a NestJS 11 modular monolith. A single Nest process hosts application features, the shared Workless runtime, and optional business modules.

The application is not split into independently deployed services. All active modules share:

- one Nest dependency-injection graph
- one PostgreSQL connection
- one request middleware and guard pipeline
- one module registry
- one Redis or in-memory cache abstraction
- one server-rendered view system

Current stack:

- NestJS 11 with Express
- TypeORM and PostgreSQL
- Passport JWT authentication
- optional Redis through ioredis
- tenant request context through AsyncLocalStorage
- React TSX rendered to static HTML with react-dom/server
- Turbo for browser navigation enhancement
- Tailwind CSS v4 with CLI and Vite build paths
- JSON localization for application and module messages

This is not a hydrated React SPA. Do not assume Next.js, client-side React routing, or a separate frontend repository.

## Source Layout

    src/
      app.module.ts
      main.ts
      app/
        controllers/
        dto/
        entities/
        helpers/
        interfaces/
        locales/
          en/
          th/
        middleware/
        providers/
        services/
        views/
          components/
          auth/
          errors/
          home/
          permissions/
          roles/
          users/
      config/
        env.config.ts
        jwt.config.ts
        typeorm.config.ts
      database/
        database.module.ts
        migrations/
        seeders/
        migration.interface.ts
        migration.service.ts
        migration.runner.ts
        seeder.runner.ts
      workless/
        events/
        http/
        infrastructure/
          cache/
        jwt/
        lifecycle/
        module/
        registry/
        tenant/
        i18n.ts
        workless.module.ts
      modules/
        modules.ts
        agent/
        crm/
        helpdesk/
        org/
      tests/

Layer responsibilities:

- src/app owns authentication, users, memberships, permissions, notifications, application locales, and shared rendered views.
- src/workless owns reusable module runtime behavior, lifecycle, registry, hooks, events, tenant context, guards, HTTP helpers, and cache infrastructure.
- src/database owns application migrations, seeders, and operational runners.
- src/modules owns installable business capabilities.
- src/config owns environment, JWT, and TypeORM configuration.

## Bootstrap and Root Wiring

src/app.module.ts composes:

1. ConfigModule
2. EventEmitterModule
3. ThrottlerModule
4. TenantModule
5. DatabaseModule
6. CacheModule
7. the application feature module imported from src/app
8. WorklessModule
9. runtime modules returned by src/modules/modules.ts

TenantContextMiddleware is applied to all routes. Throttling, authentication, permissions, module availability, and HTML cache behavior are registered as global guards or interceptors through the owning Nest modules.

src/main.ts owns:

- Nest application bootstrap
- Helmet
- CORS
- static assets from public
- the api/v1 global prefix
- selected prefix exclusions for public HTML routes
- ValidationPipe
- the global HTTP error view filter

## Application Layer

src/app is organized by Nest responsibility rather than by a second nested feature tree.

- controllers handle HTTP transport
- dto validates public request input
- entities map application database tables
- helpers derive request actors and shared state
- interfaces define application contracts and Nest injection tokens
- locales contains application-wide messages
- providers contains guards, policies, listeners, decorators, and provider mappings
- services implements authentication, users, roles, permissions, and notifications
- views contains server-rendered React TSX and response view mapping

Controllers should remain thin. Business orchestration belongs in services, authorization decisions belong in policies, and persistence behavior belongs in repositories or TypeORM-backed services.

## Contracts and Dependency Injection

Shared application contracts currently live under src/app/interfaces:

- auth.interface.ts
- cache.interface.ts
- event-bus.interface.ts
- hook.interface.ts
- notification.interface.ts
- permission.interface.ts
- role.interface.ts
- user.interface.ts

TypeScript interfaces are compile-time contracts. Exported Symbol values are runtime Nest injection tokens.

Module-owned contracts belong under:

    src/modules/<module>/interfaces/

Create an interface when it represents a real port, domain boundary, reusable type, or exported capability. Do not create empty interfaces merely to mirror every service or entity.

## Workless Runtime

src/workless contains cross-cutting runtime behavior:

- events implements the event bus and hook system
- http implements HTML cache metadata, interceptor behavior, and error rendering
- infrastructure/cache implements Redis and in-memory stores
- jwt implements the JWT strategy, authentication guard, and public-route metadata
- lifecycle implements module lifecycle commands, HTTP endpoints, migration coordination, and module seeding
- module implements metadata, discovery, and the enabled guard
- registry persists installed and enabled module state
- tenant owns tenant normalization and request context
- i18n.ts loads and merges application and module locale JSON

WorklessModule is global and exports the runtime services and injection tokens that consumers need.

## Runtime Modules

Runtime module specifications are declared explicitly in src/modules/modules.ts.

Current specifications:

- agent
- crm
- helpdesk
- org

The loader resolves each configured module dynamically:

- a present module with the expected export is loaded
- a missing optional module is logged and skipped
- an unexpected import failure is rethrown

CRM is the primary complete reference implementation. Agent, helpdesk, and org are currently scaffold-heavy; inspect their providers and controllers before treating them as complete features.

## Module Structure

Create a module with:

    npm run module:create -- <module-name>

The generator registers it in src/modules/modules.ts and creates:

    src/modules/<module-name>/
      controllers/
      dto/
      entities/
      hooks/
      interfaces/
      lifecycle/
      locales/
        en/
        th/
      migrations/
      policies/
      repositories/
      seeders/
      services/
      views/
      module.ts

Responsibilities:

- controllers expose HTTP endpoints
- dto validates public input
- entities define TypeORM persistence
- repositories own tenant-aware queries
- services orchestrate use cases
- policies own permissions and business rules
- hooks expose synchronous extension points
- interfaces define module-owned contracts
- lifecycle handles install, upgrade, and uninstall
- migrations own module schema changes
- seeders own repeatable module seed data
- locales owns module translation JSON
- views owns rendered TSX or response mapping

Do not introduce a models directory. Use entities, DTOs, interfaces, repositories, and explicit view types.

## Module Boundaries

Keep these boundaries:

- src/app does not import business module implementations
- modules do not import services or repositories from another module
- modules do not import application service implementations
- modules may consume stable injected contracts
- synchronous extension points use hooks or explicit ports
- asynchronous cross-domain communication uses events
- each module owns its entities, migrations, seeders, translations, and cache keys

When sharing a capability, expose the smallest stable contract instead of another module's internal service.

## Registry and Lifecycle

Module discovery and lifecycle live under:

- src/workless/module/module.decorator.ts
- src/workless/module/module.interface.ts
- src/workless/module/module.explorer.ts
- src/workless/registry/module-registry.entity.ts
- src/workless/registry/module.registry.ts
- src/workless/lifecycle/module.lifecycle.ts
- src/workless/lifecycle/module-lifecycle.controller.ts
- src/workless/lifecycle/module-lifecycle.runner.ts

Registry state tracks module identity, version, status, enabled state, dependencies, descriptions, and lifecycle timestamps.

Lifecycle capabilities include:

- list
- install
- uninstall
- upgrade
- migrate
- migration status
- migration revert
- seed

Expected module lifecycle methods:

- install(context)
- uninstall(context)
- upgrade(context, fromVersion)

Module-enabled checks are centralized through ModuleEnabledGuard and apply only when module metadata is present on the route.

## Database and Migrations

DatabaseModule registers TypeORM asynchronously from src/config/typeorm.config.ts.

Application migrations:

- live in src/database/migrations
- are registered in src/database/migrations/migrations.ts
- execute through src/database/migration.runner.ts
- record execution history through MigrationService
- use PostgreSQL advisory locks
- run transactionally unless a migration opts out

Current application migrations create:

- the module registry
- users and user memberships

Module migrations remain inside each module and execute through lifecycle commands.

TypeORM synchronize is available only outside production and only when DB_SYNC=true. Prefer explicit migrations for schema evolution.

## Company and Tenant Scope

The current user schema includes company_id on:

- users
- user_memberships

TypeScript entity properties use companyId and map to the PostgreSQL company_id column.

Business modules use tenantId for request isolation:

- TenantContextMiddleware reads x-tenant-id
- TenantContextService stores normalized context in AsyncLocalStorage
- tenant-aware repositories filter every business query
- TenantScopedEntity provides common tenant and timestamp fields
- cache keys include tenant scope

Company and tenant concepts coexist:

- companyId associates users and memberships with a company
- tenantId scopes requests, module records, repositories, and cache keys

Do not silently treat companyId and tenantId as interchangeable. Follow the active entity and request context for the feature being changed.

## Request Security

Global request protections include:

- ThrottlerGuard
- JwtAuthGuard
- PermissionGuard
- ModuleEnabledGuard
- TenantContextMiddleware
- Helmet
- ValidationPipe

Endpoints require authentication unless marked public. Permission metadata is enforced by PermissionGuard, and feature policies perform additional business checks.

Company or tenant access must not rely on a client-supplied identifier alone. Authentication, membership, request context, and repository scope must agree.

## Hooks and Events

Use hooks when a caller needs a transformed payload before continuing:

    request -> hook -> transformed payload -> write

Use events after a state change when listeners should react without controlling the original operation:

    write -> event -> independent listeners

Hooks and events are provided by src/workless/events and exposed through injection tokens.

Avoid direct service coupling between business modules.

## Cache

Cache implementation:

- src/workless/infrastructure/cache/cache.module.ts
- src/workless/infrastructure/cache/cache.service.ts
- src/workless/infrastructure/cache/cache.store.ts
- src/workless/infrastructure/cache/redis.provider.ts

Behavior:

- REDIS_ENABLED=true selects RedisCacheStore
- REDIS_ENABLED=false selects InMemoryCacheStore
- Redis unavailability while enabled does not automatically switch to memory

Cache orchestration belongs in services. Business keys must include tenant scope. Create, update, and delete flows must invalidate detail and collection or version keys.

HTML response cache behavior is separate:

- src/workless/http/html-cache.decorator.ts
- src/workless/http/html-cache.interceptor.ts

## Server-Rendered Views

Shared HTML and React helpers:

- src/app/views/components/main.tsx
- src/app/views/components/layouts

Module views:

- src/modules/<module>/views

React components are rendered with react-dom/server to static markup. They are server view helpers, not hydrated browser components.

The shared HTML document loads Turbo for navigation enhancement. Introducing client-side React state, hydration, or routing is an architecture change.

## Localization

Locale loading is implemented in src/workless/i18n.ts.

Messages live under:

- src/app/locales/<locale>/*.json
- src/modules/<module>/locales/<locale>/*.json

English is the fallback locale. Current application locale directories include en and th. Module views request their module messages when creating a translator.

## CSS and Assets

Active asset paths:

- public/assets/css/app.css is Tailwind source
- public/assets/css/tailwindcss.css is generated output
- tailwind.config.js contains project theme extensions
- vite.config.ts builds assets into public/assets

Do not hand-edit generated tailwindcss.css. Change app.css, tailwind.config.js, or TSX classes and rebuild.

## Commands

Development:

    npm run build
    npm run start
    npm run start:dev
    npm run dev
    npm run build:css
    npm run dev:css

Database:

    npm run db:migrate
    npm run db:migrate:status
    npm run db:migrate:revert
    npm run seed

Modules:

    npm run module:create -- <name>
    npm run module:delete -- <name>
    npm run module:list
    npm run module:install -- <name>
    npm run module:upgrade -- <name>
    npm run module:uninstall -- <name>
    npm run module:migrate -- <name>
    npm run module:migrate:status -- <name>
    npm run module:migrate:revert -- <name>
    npm run module:seed -- <name>

npm test is currently a placeholder and is not evidence of application correctness.

## Verification

Choose checks proportional to the change:

- Nest or TypeScript changes: npm run build
- CSS or Tailwind changes: npm run build:css
- application migration review: npm run db:migrate:status before mutation
- module lifecycle changes: inspect registration, lifecycle metadata, and active providers
- Redis behavior: verify Redis is enabled and reachable
- rendered pages: inspect TSX and rendered browser output

Always distinguish static review, successful compilation, and live runtime verification.

## Maintenance

Update this document when any of these change:

- source directories
- root Nest wiring
- module scaffold or lifecycle commands
- company or tenant scope
- database migrations
- cache behavior
- authentication or permission boundaries
- locale loading
- server-rendering structure
- CSS source or generated output paths
- verification commands
