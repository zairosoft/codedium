# Workless Agent Architecture

This is the agent-facing architecture reference for the Workless repository.

## Source of Truth

Use .agents for repository-specific guidance, but follow the active source code whenever documentation and implementation disagree.

Before structural work, inspect:

1. src/app.module.ts
2. src/workless/workless.module.ts
3. src/modules/modules.ts
4. the target feature or module
5. package.json for current commands

## Current System

Workless is a NestJS 11 modular monolith. One Nest process hosts application features, the shared Workless runtime, and optional business modules.

Current stack:

- NestJS 11 with Express
- TypeORM and PostgreSQL
- Passport JWT authentication
- optional Redis cache through ioredis
- tenant request context through middleware and AsyncLocalStorage
- React TSX rendered to static HTML on the server
- Turbo for browser navigation enhancement
- Tailwind CSS v4 with CLI and Vite build paths
- application and module locale JSON loaded by src/workless/i18n.ts

This is not a client-hydrated React SPA. Do not assume Next.js, client-side React routing, or a separate frontend repository.

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

- src/app contains application-level authentication, users, memberships, permissions, notifications, locales, and shared views.
- src/workless contains the reusable runtime for module discovery, lifecycle, registry, events, hooks, tenant context, JWT guards, HTTP behavior, and cache infrastructure.
- src/database contains application schema migrations, seeders, and standalone runners.
- src/modules contains installable business modules.
- src/config contains environment, JWT, and TypeORM configuration.

## Root Wiring

src/app.module.ts composes:

- ConfigModule
- EventEmitterModule
- ThrottlerModule
- TenantModule
- DatabaseModule
- CacheModule
- the application feature module imported from src/app
- WorklessModule
- runtime modules returned by src/modules/modules.ts

TenantContextMiddleware is applied to all routes. Global guards and interceptors are registered through the application and Workless modules.

src/main.ts owns:

- Nest application bootstrap
- Helmet
- CORS
- static assets
- the api/v1 global prefix
- ValidationPipe
- the global HTTP error view filter

## Application Contracts

Shared application contracts and Nest injection tokens currently live under:

- src/app/interfaces/auth.interface.ts
- src/app/interfaces/cache.interface.ts
- src/app/interfaces/event-bus.interface.ts
- src/app/interfaces/hook.interface.ts
- src/app/interfaces/notification.interface.ts
- src/app/interfaces/permission.interface.ts
- src/app/interfaces/role.interface.ts
- src/app/interfaces/user.interface.ts

Interfaces describe compile-time contracts. Exported Symbol values are runtime Nest injection tokens.

Module-owned contracts belong under:

    src/modules/<module>/interfaces/

Do not create empty interfaces merely to mirror every service or entity. Add a module interface when it defines a real boundary, port, reusable domain type, or external capability.

## Module Structure

New modules are created with:

    npm run module:create -- <module-name>

The generator registers the module in src/modules/modules.ts and creates:

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

Directory responsibilities:

- controllers expose HTTP endpoints and remain thin
- dto validates public input
- entities define TypeORM persistence models
- repositories own tenant-aware persistence queries
- services orchestrate use cases
- policies own permissions and business rules
- hooks expose extension points
- interfaces define module-owned contracts
- lifecycle implements install, upgrade, and uninstall behavior
- migrations own module schema changes
- seeders own repeatable module seed data
- locales owns module translation JSON
- views owns module-rendered TSX or response view mapping

Do not introduce a models directory. Use entities, DTOs, interfaces, and explicit view types.

CRM is the primary complete reference module. Agent, helpdesk, and org are currently scaffold-heavy; inspect their active providers before making behavior claims.

## Module Boundaries

Keep these dependency rules:

- src/app does not import business module implementations
- modules do not import services or repositories from another module
- modules do not import application service implementations
- modules may consume stable injected contracts
- synchronous extension points use hooks or explicit ports
- asynchronous cross-domain communication uses emitted events
- each module owns its entities, migrations, seeders, cache keys, and translations

When a capability must be shared, expose the smallest stable contract rather than another module's internal service.

## Runtime Module System

Runtime module specifications live in:

- src/modules/modules.ts

Discovery and lifecycle implementation lives in:

- src/workless/module/module.decorator.ts
- src/workless/module/module.interface.ts
- src/workless/module/module.explorer.ts
- src/workless/registry/module.registry.ts
- src/workless/lifecycle/module.lifecycle.ts
- src/workless/lifecycle/module-lifecycle.runner.ts

Expected lifecycle methods:

- install(context)
- uninstall(context)
- upgrade(context, fromVersion)

Module loading is intentionally tolerant of a missing optional module directory. Registry reads should represent discoverable modules rather than stale filesystem assumptions.

## Database

Application migrations live under src/database/migrations and are registered through src/database/migrations/migrations.ts.

Module migrations remain inside each module and execute through module lifecycle commands.

## Cache

Cache implementation lives under:

- src/workless/infrastructure/cache/cache.module.ts
- src/workless/infrastructure/cache/cache.service.ts
- src/workless/infrastructure/cache/cache.store.ts
- src/workless/infrastructure/cache/redis.provider.ts

Behavior:

- REDIS_ENABLED=true selects RedisCacheStore
- REDIS_ENABLED=false selects InMemoryCacheStore
- Redis unavailability while enabled does not automatically switch to memory

Cache orchestration belongs in services. Keys for business data must include tenant scope. Writes must invalidate detail and collection or version keys.

HTML response cache behavior is separate and lives in:

- src/workless/http/html-cache.interceptor.ts
- src/workless/http/html-cache.decorator.ts

## Authentication and Permissions

JWT implementation lives under src/workless/jwt and uses application user entities and contracts.

Application authorization components live under src/app/providers:

- JwtAuthGuard protects non-public routes
- PermissionGuard enforces permission metadata
- policies perform feature-specific checks

Do not trust request identifiers alone for company or tenant access. Authentication, membership, request context, and repository scope must agree.

## Server-Rendered Views

Shared server-rendering helpers live under:

- src/app/views/components/main.tsx
- src/app/views/components/layouts/

Module views live under:

- src/modules/<module>/views/

React components are rendered with react-dom/server to static markup. They are server view helpers, not hydrated client components.

Turbo is loaded by the shared HTML document for navigation enhancement. Do not introduce client-side React state or routing without treating it as an architecture change.

## Locales

Locale loading is implemented in src/workless/i18n.ts.

Locale locations:

- src/app/locales/<locale>/*.json for application messages
- src/modules/<module>/locales/<locale>/*.json for module messages

English is the fallback. Current application locale directories include en and th. Module views must request their module namespace when creating a translator.

## CSS and Assets

Active paths:

- public/assets/css/app.css is the Tailwind source entry
- public/assets/css/tailwindcss.css is generated output
- tailwind.config.js provides project theme extensions
- vite.config.ts builds CSS into public/assets

Do not hand-edit generated tailwindcss.css. Change app.css, tailwind.config.js, or TSX classes and rebuild.

## Commands

Primary commands:

    npm run build
    npm run start
    npm run start:dev
    npm run dev
    npm run build:css
    npm run dev:css
    npm run db:migrate
    npm run db:migrate:status
    npm run db:migrate:revert
    npm run seed
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

- TypeScript or Nest changes: npm run build
- CSS or Tailwind changes: npm run build:css
- application migrations: npm run db:migrate:status before mutation
- module lifecycle: inspect registration, lifecycle metadata, and target module wiring
- Redis behavior: verify Redis is enabled and reachable before claiming runtime coverage
- rendered pages: inspect the TSX source and rendered HTML or browser output

Always distinguish static inspection, successful compilation, and live runtime verification.

## Maintenance

Update this file and the relevant .agents/skills entry when any of these change:

- directories under src
- root Nest module wiring
- module scaffold or lifecycle commands
- cache behavior
- authentication or permission boundaries
- locale loading
- server-rendering structure
- CSS source or generated asset paths
- verification commands
