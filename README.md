# Workless

Workless is an AI SaaS platform that helps reduce workloads, making work easier, faster, and more efficient for every organization.

![Screen](https://www.zairosoft.com/assets/2026/02/crm.webp "Dashboards")

## Overview

Workless is organized around four top-level runtime areas:

- `src/app`: platform-level auth, users, roles, notifications, home page, and HTML views
- `src/workless`: module registry, lifecycle, hook/event bus, tenant context, and HTML cache support
- `src/database`: TypeORM bootstrap, schema runner, and seeder runner
- `src/modules`: runtime business modules such as `crm`, `helpdesk`, and `org`

The application is a single NestJS process. Business modules are discovered from `src/modules/runtime-modules.ts` and then mounted into `AppModule`.

## Stack

- NestJS 11
- TypeORM
- PostgreSQL
- Redis
- BullMQ
- KITA JSX/TSX server-rendered views
- Tailwind CSS 4

## Runtime Entry Points

- `src/main.ts`: Nest bootstrap, CORS, Helmet, static assets, global prefix
- `src/app.module.ts`: root module composition
- `src/app/platform.module.ts`: platform controllers, auth, users, permissions, notifications
- `src/core/core.module.ts`: module lifecycle, module enablement guard, hook/event bus, HTML cache interceptor
- `src/database/database.module.ts`: TypeORM integration
- `src/modules/runtime-modules.ts`: list of business modules to load at runtime

## URL Structure

Most HTTP endpoints are prefixed with:

```text
/api/v1
```

Current public routes excluded from the prefix:

- `GET /`
- `GET /auth/login`

Examples:

- `GET /` -> landing page
- `GET /auth/login` -> login page
- `POST /api/v1/auth/login` -> JSON login endpoint
- `GET /api/v1/modules` -> module registry endpoint

## Requirements

- Node.js 20+
- npm 10+
- PostgreSQL 14+ recommended
- Redis 6+ optional

## Installation

1. Clone the repository

```bash
git clone https://github.com/zairosoft/workless.git
cd workless
```

2. Install dependencies

```bash
npm install
```

3. Create environment file

```bash
cp .env.example .env
```

4. Configure `.env`

Required:

- `PORT`
- `DB_HOST`
- `DB_PORT`
- `DB_USERNAME`
- `DB_PASSWORD`
- `DB_NAME`
- `JWT_SECRET`

Useful defaults from `.env.example`:

- `DB_SYNC=true` for local development only
- `REDIS_ENABLED=false` if Redis is not running
- `JWT_EXPIRES_IN=1h`

5. Start the app

```bash
npm run db:migrate
npm run start:dev
```

## Available Scripts

```bash
npm run start:dev
npm run build
npm run start
npm run dev
npm run build:css
npm run db:platform
npm run db:migrate
npm run db:migrate:status
npm run db:migrate:revert
npm run seed
npm run module:create -- agent
npm run module:list
npm run module:install -- crm
npm run module:upgrade -- crm
npm run module:uninstall -- crm
npm run module:migrate -- crm
npm run module:migrate -- --all
npm run module:migrate:status -- crm
npm run module:migrate:revert -- crm
npm run module:seed -- crm
npm run module:seed -- --all
```

Notes:

- `npm run dev` starts Nest dev mode and Tailwind watch mode together
- `npm run build:css` compiles `public/assets/css/app.css` to `public/assets/css/tailwindcss.css`
- `npm run test` is currently a placeholder and does not run a real test suite yet

## Database Migrations

Workless records platform and module migrations in `system_migrations`. Migrations are
ordered by timestamp, protected by a PostgreSQL advisory lock, and run once inside a
transaction by default. Keep `DB_SYNC=false`; schema changes should be delivered as
migrations.

Run pending platform migrations before starting a new deployment:

```bash
npm run db:migrate
```

Inspect migration state or roll back the latest platform migration:

```bash
npm run db:migrate:status
npm run db:migrate:revert
```

`npm run db:platform` remains as an alias for `npm run db:migrate`.

### Module migrations

Installing or upgrading a module automatically runs its pending migrations before its
lifecycle hook:

```bash
npm run module:install -- crm
npm run module:upgrade -- crm
```

Migrations can also be managed without changing the installed module version:

```bash
# Run one module
npm run module:migrate -- crm

# Run every discovered module
npm run module:migrate -- --all

# Inspect or revert one module
npm run module:migrate:status -- crm
npm run module:migrate:revert -- crm
```

Uninstalling a module does not remove its tables or data. Use migration rollback only
when the schema change itself must be reverted.

### Adding a module migration

Create a migration class under the module's `migrations` directory:

```ts
import { QueryRunner } from 'typeorm';
import { WorklessMigration } from '../../../database/migration.interface';

export class AddCrmContactSourceMigration implements WorklessMigration {
  readonly name = 'add-crm-contact-source';
  readonly timestamp = 202607120200;
  readonly checksum = 'add-crm-contact-source-v1';

  async up(queryRunner: QueryRunner): Promise<void> {
    await queryRunner.query(
      'ALTER TABLE "crm_contacts" ADD COLUMN IF NOT EXISTS "source" varchar(80)',
    );
  }

  async down(queryRunner: QueryRunner): Promise<void> {
    await queryRunner.query(
      'ALTER TABLE "crm_contacts" DROP COLUMN IF EXISTS "source"',
    );
  }
}
```

Then register the class in the module metadata. The lifecycle service does not need to
loop over migrations itself:

```ts
@SystemModule({
  name: 'crm',
  version: '1.1.0',
  migrations: [
    CrmContactSchemaMigration,
    CrmContactIndexMigration,
    AddCrmContactSourceMigration,
  ],
})
```

Migration names must be unique within their scope. Never edit an applied migration;
create a new migration instead, because Workless verifies applied migration checksums.

## Module Seeders

Module seeders run independently from module installation. Each seeder must be
idempotent so it is safe to execute more than once.

Seed one module or every discovered module:

```bash
npm run module:seed -- crm
npm run module:seed -- --all
```

The existing `seed` command uses the same module seeding engine and seeds all modules:

```bash
npm run seed
```

Platform administrators can also trigger a module seeder through the lifecycle API:

```text
POST /api/v1/modules/crm/seed
```

Before running seeders, Workless applies pending migrations for the target module. The
command does not install, enable, disable, or change the version of a module.

Create a Nest provider implementing `ModuleSeeder`:

```ts
import { Injectable } from '@nestjs/common';
import { ModuleSeeder } from '../../../workless/lifecycle/module-seeder.interface';

@Injectable()
export class CrmPipelineSeeder implements ModuleSeeder {
  readonly name = 'crm-default-pipeline';
  readonly order = 200;

  async seed(): Promise<void> {
    // Check for existing data, then insert or update the required defaults.
  }
}
```

Register the seeder as a provider in the Nest module, then add it to the system module
metadata:

```ts
@Module({
  providers: [CrmPipelineSeeder, CrmModuleLifecycleService],
})
export class CrmModule {}

@SystemModule({
  name: 'crm',
  version: '1.1.0',
  migrations: [CrmContactSchemaMigration],
  seeders: [CrmContactSeeder, CrmPipelineSeeder],
})
```

Seeders are ordered by `order` and then by name. Seeder names must be unique within a
module. A seeder may use constructor injection because it is resolved from the Nest
dependency injection container.

## Project Structure

```text
src/
  app.module.ts
  main.ts
  app/
    auth/
    controllers/
    dto/
    entities/
    helpers/
    providers/
    services/
    views/
  workless/
    events/
    http/
    infrastructure/
      cache/
      database/
    interfaces/
    lifecycle/
    module/
    registry/
    tenant/
  database/
    migrations/
    seeders/
  modules/
    apps/
    crm/
    helpdesk/
    org/
    runtime-modules.ts
public/
  assets/
    css/
```

## Module Status

Runtime-loaded modules from `src/modules/runtime-modules.ts`:

- `crm`
- `helpdesk`
- `org`

Current state:

- `crm` is the most complete reference module
- `helpdesk` and `org` have module/lifecycle scaffolding in place
- `apps` is a reserved scaffold and is not currently loaded at runtime

## Creating a Module

Create and register a new module with the module generator:

```bash
npm run module:create -- inventory
```

Module names must use lowercase kebab-case. The generator creates the standard
directories, Nest module entry point, lifecycle provider, locale directories, and
the entry in `src/modules/runtime-modules.ts`. It will not overwrite an existing
module. Use `src/modules/crm` as the reference when implementing module behavior.

For example, the generated `inventory` module starts with this structure:

```text
src/modules/inventory/
  controllers/
  dto/
  entities/
  hooks/
  lifecycle/
  migrations/
  policies/
  repositories/
  seeders/
  services/
  views/
  module.ts
```

Not every directory is required immediately. Keep controllers thin, place business
orchestration in services, and keep persistence logic in repositories. Do not create a
`models/` directory or import services directly from another business module.

Create the Nest module entry point:

```ts
import { Module } from '@nestjs/common';
import { InventoryModuleLifecycleService } from './lifecycle/inventory-module.lifecycle';

@Module({
  providers: [InventoryModuleLifecycleService],
})
export class InventoryModule {}
```

Add a lifecycle provider so Workless can discover and manage the module:

```ts
import { Injectable } from '@nestjs/common';
import { SystemModule } from '../../../workless/module/module.decorator';
import {
  ModuleLifecycleContext,
  SystemModuleLifecycle,
} from '../../../workless/module/module.interface';

@Injectable()
@SystemModule({
  name: 'inventory',
  version: '1.0.0',
})
export class InventoryModuleLifecycleService implements SystemModuleLifecycle {
  async install(_context: ModuleLifecycleContext): Promise<void> {}

  async uninstall(_context: ModuleLifecycleContext): Promise<void> {}

  async upgrade(
    _context: ModuleLifecycleContext,
    _fromVersion?: string,
  ): Promise<void> {}
}
```

Register the module in `src/modules/runtime-modules.ts`:

```ts
const RUNTIME_MODULE_SPECS: RuntimeModuleSpec[] = [
  // Existing modules...
  {
    name: 'inventory',
    exportName: 'InventoryModule',
    requirePath: './inventory/module',
  },
];
```

Then verify and install it:

```bash
npm run build
npm run module:list
npm run module:install -- inventory
```

The lifecycle commands require a working PostgreSQL connection. If the module owns
database schema or initial data, register its migrations and seeders in the
`@SystemModule` metadata as described above.

## Frontend Asset Pipeline

Workless serves static files from `public/`.

Current CSS pipeline:

- source: `public/assets/css/app.css`
- output: `public/assets/css/tailwindcss.css`
- Tailwind config: `tailwind.config.js`

Optional tooling present in the repo:

- `vite.config.ts`
- `vitest.config.ts`

These exist for frontend tooling and local build workflows, but the primary runtime remains the Nest app serving static assets from `public/`.

## Get in Touch

- 📧 Email: [info@zairosoft.com](mailto:info@zairosoft.com)
- 💼 LinkedIn: [linkedin.com/in/zairosoft](https://www.linkedin.com/in/zairosoft)
- 🌐 Website: [zairosoft.com](https://www.zairosoft.com)

## Support Me

- [Sponsors](https://github.com/sponsors/zairosoft)

## Security

Please review [SECURITY.md](SECURITY.md).

## License

See [LICENSE](LICENSE).
