# Workless

Workless is a modular business application built with NestJS. It provides authentication, users, organizations, roles, permissions, tenant-aware modules, server-rendered views, localization, migrations, and caching.

![Workless dashboard](https://www.zairosoft.com/assets/2026/02/crm.webp "Workless dashboard")

## Stack

NestJS, TypeScript, TypeORM, PostgreSQL, Redis, React SSR, Turbo, Tailwind CSS v4, Vite, and Passport JWT.

## Requirements

- Node.js 22 LTS or newer
- npm
- PostgreSQL
- Redis (optional)

## Installation

```bash
git clone https://github.com/zairosoft/workless.git
cd workless
npm install
cp .env.example .env
```

Set the database and JWT values in `.env`, then run:

```bash
npm run db:migrate
npm run seed
npm run dev
```

Open [http://localhost:3000](http://localhost:3000). Development assets are served by Vite at `http://localhost:5173`.

## Commands

```bash
npm run dev                         # Start NestJS and Vite in development
npm run build                       # Build CSS and compile TypeScript
npm run start                       # Start the compiled application
npm run db:migrate                  # Apply application migrations
npm run db:migrate:status           # Show migration status
npm run db:migrate:revert           # Revert the latest migration
npm run seed                        # Run application seeders
```

## Modules

Business modules are self-contained under `src/modules`:

```bash
npm run module:create -- accounting
```

Common lifecycle commands:

```bash
npm run module:list
npm run module:install -- <name>
npm run module:upgrade -- <name>
npm run module:uninstall -- <name>
npm run module:delete -- <name>
```

Each module contains application code under `app/`, migrations and seeders under `database/`, configuration in `app.config.json`, and its NestJS entry point in `module.ts`.

## Source Structure

```text
src/
├── app/        # Core application, authentication, users, and shared views
├── config/     # Environment, JWT, and database configuration
├── database/   # Application migrations and seeders
├── modules/    # Installable business modules
└── workless/   # Module runtime, tenant context, lifecycle, HTTP, and cache
```

## Security

Please review [SECURITY.md](https://github.com/zairosoft/workless/blob/main/SECURITY.md).

Never commit:

- .env
- JWT secrets
- database passwords
- Redis passwords
- mail credentials

## Contact

- Email: info@zairosoft.com
- LinkedIn: [linkedin.com/in/zairosoft](https://www.linkedin.com/in/zairosoft)
- Website: [zairosoft.com](https://www.zairosoft.com/)
- Sponsors: [github.com/sponsors/zairosoft](https://github.com/sponsors/zairosoft)

## License

See [LICENSE](https://github.com/zairosoft/workless/blob/main/LICENSE).
