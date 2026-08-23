# Workless

Workless helps organizations reduce repetitive work through modular business capabilities.

![Workless dashboard](https://www.zairosoft.com/assets/2026/02/crm.webp "Workless dashboard")

## What It Includes

Workless runs as one NestJS application with:

- authentication and JWT-protected routes
- users, company memberships, roles, and permissions
- installable business modules
- PostgreSQL migrations and seeders
- tenant-aware repositories and cache keys
- optional Redis cache with an in-memory alternative
- server-rendered React TSX views
- English and Thai locale JSON
- Tailwind CSS v4 assets

## Technology

- NestJS 11
- TypeScript
- TypeORM
- PostgreSQL
- Passport JWT
- Redis through ioredis
- React and react-dom/server
- Turbo
- Tailwind CSS 4
- Vite

The React code is rendered to static HTML on the server. Workless is not a hydrated SPA and does not use Next.js routing.

## Requirements

- Node.js 20 or newer recommended
- npm
- PostgreSQL
- Redis optional

## Installation

Clone and install dependencies:

    git clone https://github.com/zairosoft/workless.git
    cd workless
    npm install

Create the environment file:

    cp .env.example .env

Generate a secure JWT secret:

    openssl rand -hex 32

Place the generated value in JWT_SECRET.

Configure PostgreSQL, then prepare and start the application:

    npm run db:migrate
    npm run seed
    npm run start:dev

The default HTTP port is 3000.

## Environment

Application:

- APP_NAME: application name
- PORT: HTTP port
- LOCALE: default rendered locale
- NODE_ENV: development or production
- CORS_ORIGIN: optional allowed browser origin

PostgreSQL:

- DB_HOST
- DB_PORT
- DB_USERNAME
- DB_PASSWORD
- DB_NAME
- DB_SYNC

Keep DB_SYNC=false when using migrations. Never enable schema synchronization in production.

Redis:

- REDIS_ENABLED
- REDIS_HOST
- REDIS_PORT
- REDIS_PASSWORD
- REDIS_DB

Set REDIS_ENABLED=false when Redis is not available. The application chooses in-memory cache only when Redis is disabled; it does not automatically fail over when Redis is enabled but unreachable.

JWT:

- JWT_SECRET
- JWT_EXPIRES_IN

JWT_SECRET must be a strong private random value and must not be committed.

Mail:

- MAIL_MAILER
- MAIL_HOST
- MAIL_PORT
- MAIL_USERNAME
- MAIL_PASSWORD
- MAIL_ENCRYPTION
- MAIL_FROM_ADDRESS
- MAIL_FROM_NAME

## Development Commands

Application:

    npm run start:dev
    npm run build
    npm run start
    npm run dev

npm run dev builds CSS once, then starts both the Tailwind watcher and Nest development process.

CSS:

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
    npm run module:migrate -- --all
    npm run module:migrate:status -- <name>
    npm run module:migrate:revert -- <name>
    npm run module:seed -- <name>
    npm run module:seed -- --all

npm test is currently a placeholder and does not run an automated test suite.

## HTTP Routes

Most routes use the api/v1 prefix.

Public HTML routes excluded from the prefix:

- GET /
- GET /auth/login
- GET /auth/register
- GET /language/:locale

Examples:

- POST /api/v1/auth/login
- POST /api/v1/auth/register
- GET /api/v1/modules
- POST /api/v1/modules/:name/install
- POST /api/v1/modules/:name/upgrade

Routes require JWT authentication unless marked public. Permission and module-enabled guards apply after authentication where configured.

Tenant-aware requests use:

    X-Tenant-Id: <tenant-uuid>

## Source Structure

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
        <module>/

Main responsibilities:

- src/app contains authentication, users, permissions, application locales, and shared views.
- src/workless contains the module runtime, lifecycle, registry, tenant context, hooks, events, JWT guards, HTTP helpers, and cache implementation.
- src/database contains application migrations and seeders.
- src/modules contains installable business modules.
- src/config contains environment, JWT, and TypeORM configuration.

## Database Migrations

Application migrations live in:

    src/database/migrations/

They are registered in:

    src/database/migrations/migrations.ts

Migration execution:

- orders migrations by timestamp
- records applied migration names and checksums
- prevents concurrent execution with PostgreSQL advisory locks
- uses a transaction by default
- refuses to continue if an applied migration checksum changes

Do not edit an applied migration. Add a new migration for the next schema change.

Inspect status before applying or reverting changes:

    npm run db:migrate:status

Apply pending migrations:

    npm run db:migrate

Revert the latest application migration:

    npm run db:migrate:revert

## Module Migrations

Module migrations belong to their owning module:

    src/modules/<module>/migrations/

Installing or upgrading a module applies its pending migrations before lifecycle completion.

Run migrations without changing the installed version:

    npm run module:migrate -- accounting
    npm run module:migrate -- --all

Inspect or revert one module:

    npm run module:migrate:status -- accounting
    npm run module:migrate:revert -- accounting

Uninstalling a module does not automatically delete its tables or business data.

Every migration requires:

- a unique name within its scope
- an integer timestamp
- a checksum version string
- an up method
- an optional down method

Register module migrations in the migrations array of the SystemModule metadata.

## Module Seeders

Seed application data and every registered module:

    npm run seed

Seed one module or all modules:

    npm run module:seed -- accounting
    npm run module:seed -- --all

Module seeders must be idempotent. Seeder names must be unique within a module, and ordering uses order followed by name.

The lifecycle API also exposes:

    POST /api/v1/modules/:name/seed

Seeding applies pending migrations but does not install, enable, disable, or change the version of a module.

## Creating a Module

Generate and register a module:

    npm run module:create -- accounting

The generated structure is:

    src/modules/accounting/
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

Then implement the feature and verify:

    npm run build
    npm run module:install -- accounting

Use:

- controllers for transport
- DTOs for validation
- entities for persistence
- repositories for tenant-aware data access
- services for use-case orchestration
- policies for permissions and business rules
- interfaces for real module-owned contracts
- hooks and events for extension points
- migrations for schema changes
- seeders for repeatable initial data
- locales for module translations
- views for rendered TSX or response mapping

Avoid direct service or repository imports between business modules.

Delete an unused scaffold:

    npm run module:delete -- accounting

Deleting a scaffold unregisters and removes its source directory. It does not remove database tables or registry history.

## Cache

Cache implementation lives under:

    src/workless/infrastructure/cache/

When Redis is enabled, Workless stores JSON values with TTL in Redis. When disabled, it uses process-local memory.

Use CacheService from the service layer. Keep keys tenant-aware and invalidate affected detail and collection keys after writes.

HTML response caching is separate from data caching and is handled under src/workless/http.

## Server-Rendered Views

Shared view helpers:

    src/app/views/components/

Module-owned pages:

    src/modules/<module>/views/

React components are rendered with react-dom/server to static markup. Turbo enhances navigation in the browser.

Adding client-side React state, hydration, or routing is an architecture change and should not be assumed by ordinary view work.

## Localization

Locale loader:

    src/workless/i18n.ts

Application messages:

    src/app/locales/<locale>/*.json

Module messages:

    src/modules/<module>/locales/<locale>/*.json

Current application locale roots are en and th. English is the fallback locale.

## CSS and Assets

Workless serves static files from public.

Tailwind source:

    public/assets/css/app.css

Generated CSS:

    public/assets/css/tailwindcss.css

Theme extensions:

    tailwind.config.js

Vite build configuration:

    vite.config.ts

Do not edit generated tailwindcss.css directly. Change app.css, tailwind.config.js, or TSX classes and rebuild:

    npm run build:css

## Verification

Use the smallest check that proves the change:

- Nest or TypeScript changes: npm run build
- CSS or Tailwind changes: npm run build:css
- application migration review: npm run db:migrate:status
- module lifecycle changes: inspect module registration and lifecycle metadata
- Redis behavior: verify Redis is enabled and reachable
- rendered pages: inspect TSX and browser output

Distinguish static inspection, successful compilation, and live runtime verification.

## Security

Please review [SECURITY.md](SECURITY.md).

Never commit:

- .env
- JWT secrets
- database passwords
- Redis passwords
- mail credentials

## Contact

- Email: [info@zairosoft.com](mailto:info@zairosoft.com)
- LinkedIn: [linkedin.com/in/zairosoft](https://www.linkedin.com/in/zairosoft)
- Website: [zairosoft.com](https://www.zairosoft.com)
- Sponsors: [github.com/sponsors/zairosoft](https://github.com/sponsors/zairosoft)

## License

See [LICENSE](LICENSE).
