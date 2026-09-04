# Workless

Workless is a modular business application built with NestJS. It provides authentication, users, organizations, roles, permissions, tenant-aware modules, server-rendered views, localization, migrations, and caching.

![Workless dashboard](https://www.zairosoft.com/assets/2026/02/crm.webp "Workless dashboard")

## Technology

- NestJS, TypeScript, TypeORM, and PostgreSQL
- Redis with an in-memory cache option
- React server-rendered TSX and Turbo
- Tailwind CSS v4 and Vite
- Passport JWT

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

## Environment

Important variables are documented in `.env.example`:

- Application: `APP_NAME`, `PORT`, `LOCALE`, `NODE_ENV`
- Database: `DB_HOST`, `DB_PORT`, `DB_USERNAME`, `DB_PASSWORD`, `DB_NAME`
- JWT: `JWT_SECRET`, `JWT_EXPIRES_IN`
- Redis: `REDIS_ENABLED`, `REDIS_HOST`, `REDIS_PORT`, `REDIS_PASSWORD`, `REDIS_DB`
- Mail: `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`

Keep `DB_SYNC=false` when using migrations and in production.

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

Module lifecycle:

```bash
npm run module:create -- <name>
npm run module:list
npm run module:install -- <name>
npm run module:upgrade -- <name>
npm run module:uninstall -- <name>
npm run module:delete -- <name>
```

## Modules

Business modules are self-contained under `src/modules`. Create a module with:

```bash
npm run module:create -- accounting
```

Generated modules follow this structure:

```text
src/modules/<module>/
├── app/
│   ├── controllers/
│   ├── dto/
│   ├── entities/
│   ├── hooks/
│   ├── interfaces/
│   ├── lifecycle/
│   ├── locales/
│   ├── policies/
│   ├── repositories/
│   ├── services/
│   └── views/
├── database/
│   ├── migrations/
│   └── seeders/
├── app.config.json
├── module.ts
└── README.md
```

Use module migrations and seeders with:

```bash
npm run module:migrate -- <name>
npm run module:migrate:status -- <name>
npm run module:migrate:revert -- <name>
npm run module:seed -- <name>
```

## Source Structure

```text
src/
├── app/        # Core application, authentication, users, and shared views
├── config/     # Environment, JWT, and database configuration
├── database/   # Application migrations and seeders
├── modules/    # Installable business modules
└── workless/   # Module runtime, tenant context, lifecycle, HTTP, and cache
```

Tailwind source is `public/assets/css/app.css`. Do not edit the generated `public/assets/css/tailwindcss.css` directly; run `npm run build` after changing styles.

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
