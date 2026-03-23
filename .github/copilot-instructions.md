# Copilot Instructions for desa-digital-api

## Project Overview
This is a Laravel-based RESTful API for a digital village management system. The codebase follows Laravel conventions but includes custom patterns for repository usage, UUID handling, and modular resource organization.

## Architecture & Key Components
- **app/Models/**: Eloquent models for domain entities (e.g., `User`, `Profile`, `HeadOfFamily`, etc.).
- **app/Repositories/** & **Interfaces/**: Implements the Repository pattern. Interfaces are in `Interfaces/`, concrete classes in `Repositories/`. Example: `UserRepositoryInterface` & `UserRepository`.
- **app/Traits/UUID.php**: Provides UUID generation for models. Most tables use UUIDs as primary keys.
- **app/Helpers/ResponseHelper.php**: Centralizes API response formatting.
- **app/Http/Controllers/**: Route handlers, typically using repositories for data access.
- **database/migrations/**: Migration files use UUIDs and soft deletes. Foreign keys often use `foreignUuid`.
- **routes/api.php**: Main entry for API routes.

## Developer Workflows
- **Run the server**: `php artisan serve`
- **Run migrations**: `php artisan migrate`
- **Run tests**: `php artisan test`
- **Seed database**: `php artisan db:seed`
- **Debugging**: Use Laravel's built-in logging (`storage/logs/`) and exception handling.

## Project-Specific Patterns
- **UUIDs**: All major tables use UUIDs as primary keys. Use the `UUID` trait for model generation.
- **Soft Deletes**: Most models and migrations use `$table->softDeletes()`.
- **Repository Pattern**: All data access should go through repositories, not directly via models in controllers.
- **API Responses**: Use `ResponseHelper` for consistent response formatting.
- **Resource Organization**: Related resources (e.g., applicants, participants) are grouped by domain in `Models/` and `Controllers/Resources/`.

## Integration Points
- **External dependencies**: Managed via Composer (`composer.json`).
- **Frontend**: Not included in this repo; API is designed for consumption by a separate frontend.

## Examples
- To add a new entity:
  1. Create a model in `app/Models/`
  2. Create a migration with UUID and soft deletes
  3. Add repository interface & implementation
  4. Register repository in `RepositoryServiceProvider`
  5. Add controller methods using the repository

## References
- Key files: `app/Models/User.php`, `app/Repositories/UserRepository.php`, `app/Interfaces/UserRepositoryInterface.php`, `app/Traits/UUID.php`, `app/Helpers/ResponseHelper.php`, `routes/api.php`
- For Laravel conventions, see [Laravel Docs](https://laravel.com/docs)

---
_If any section is unclear or missing, please provide feedback to improve these instructions._
