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
npm run seed
npm run module:list
npm run module:install -- crm
npm run module:upgrade -- crm
npm run module:uninstall -- crm
```

Notes:

- `npm run dev` starts Nest dev mode and Tailwind watch mode together
- `npm run build:css` compiles `public/assets/css/app.css` to `public/assets/css/tailwindcss.css`
- `npm run test` is currently a placeholder and does not run a real test suite yet

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
  core/
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

See [license.txt](license.txt).
