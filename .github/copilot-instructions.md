<!-- Project-specific Copilot instructions for ProyectoIngenieriaSoftwareII -->
# Copilot / AI agent quick-start (ProyectoIngenieriaSoftwareII)

This repository is a Laravel 12 web application (PHP 8.2) built for a school administration system. Below are concise, actionable notes that will help an AI coding agent be productive immediately.

- **Project layout (important files)**: `app/Models/` (Eloquent models, many in Spanish), `app/Http/Controllers/` (role-based controllers like `DocenteController`, `RectorController`), `routes/web.php` (primary route map organized by role prefixes), `resources/views/` (Blade views), `database/migrations/` (schema), `composer.json` and `phpunit.xml`.

- **High-level architecture & why it’s organized this way**:
  - Controllers are organized by user role: many controllers expose dashboards and API endpoints under prefixed routes (e.g., `/docente/*`, `/estudiante/*`, `/tesoreria/*`). When adding features, follow the same role-prefixed grouping.
  - Business logic tends to live inside role controllers and model methods. Look for AJAX-style endpoints under `/api/*` used by JavaScript modals and async forms.
  - Authentication + authorization: project uses a custom `App\\Models\\RolesModel` (see `app/Models/RolesModel.php`) and stores a `roles_id` on `users`. Although `spatie/laravel-permission` is installed, the codebase primarily checks `auth()->user()->rol` and `RolesModel`. Prefer codebase conventions over the package defaults unless an explicit refactor is made.

- **Conventions & patterns to follow**:
  - Models are singular Spanish nouns (e.g., `Estudiante`, `Curso`, `Pago`). Controllers are role or feature oriented (e.g., `CoordinadorAcademicoController`). Keep new controllers consistent with this naming style.
  - Routes: group by `Route::prefix()` and `->name()` for role-based namespaces. Many routes use closures for small admin helpers — do not convert to resource routes without checking where they are referenced in views/JS.
  - Permissions: `RolesModel` stores `permisos` as JSON cast to array. Use `RolesModel::tienePermiso(...)` or check `optional(auth()->user()->rol)->nombre` for role checks as seen in `routes/web.php`.

- **Common developer workflows (Windows / PowerShell)**:
  - Install PHP deps, node deps, and set up env (PowerShell):

```powershell
composer install; php -r "file_exists('.env') || copy('.env.example', '.env');"; Copy-Item .env.example .env -ErrorAction SilentlyContinue
npm install
```

  - Common artisan commands (PowerShell):

```powershell
php artisan key:generate
php artisan migrate --seed
php artisan serve
php artisan test       # runs PHPUnit via Laravel wrapper
```

  - composer scripts in `composer.json` run migrations and create `.env` automatically during `post-create-project-cmd`. If working manually, prefer the `php artisan` commands above.

- **Testing**:
  - Tests live in `tests/Unit` and `tests/Feature`. Use `php artisan test` or `./vendor/bin/phpunit`.
  - `phpunit.xml` sets test environment variables (many DB-related settings are commented out). If tests require DB, ensure `.env.testing` or CI has a proper DB connection (SQLite memory is commented but can be enabled for fast CI runs).

- **Migrations & data schema notes**:
  - Important migrations: `database/migrations/2025_10_09_012256_create_roles_table.php` (custom roles JSON column), `2025_10_09_013015_add_role_id_to_users_table.php` (adds `roles_id` FK). There is also an empty-looking `roles_models` table migration — confirm intent before changing or removing it.
  - When changing models or migrations, update corresponding controllers and seeders. Run `php artisan migrate:fresh --seed` locally when making schema changes.

- **Frontend**:
  - Uses Vite; `vite.config.js` and `resources/js/` are the entry points. Use `npm run dev` for hot reloading and `npm run build` for production assets.

- **Integration points / external dependencies**:
  - Database: MySQL commonly used via XAMPP in this workspace. `.env` controls DB connection (update before running migrations).
  - Packages: `spatie/laravel-permission` is installed but project uses a custom roles model — inspect usage before switching to Spatie models.
  - Background jobs: `php artisan queue:listen` appears in `composer.json` dev tasks; queue connection defaults to `sync` in tests.

- **How to make safe changes (recommended checklist for an AI agent)**:
  1. Locate the role scope: check `routes/web.php` to see where route should live (role prefix).
 2. Update or add controller in `app/Http/Controllers/` following existing naming and responsibilities.
 3. If new DB fields are needed, add migration under `database/migrations/` and update model `$fillable` and `$casts` accordingly.
 4. Run `php artisan migrate --seed` locally (or `migrate:fresh --seed` in a dev DB) and run `php artisan test`.
 5. Search for references in `resources/views/` and `resources/js/` — many UI flows depend on AJAX endpoints under `/api/*`.

- **Files to check first when investigating behavior**:
  - `routes/web.php` — canonical route map and where role checks are implemented.
  - `app/Models/RolesModel.php` — role/permission representation.
  - `app/Http/Controllers/*Controller.php` — controller conventions and role responsibilities.
  - `database/migrations/*roles*.php` and `*add_role_id_to_users_table.php` — schema for roles.

If anything above is unclear or you want more examples (e.g., copy of a representative controller or migration), tell me which area to expand and I will update this file accordingly.
