# AGENTS.md

## Project Overview

**Wichtel.me** - A "Wichteln-as-a-Service" (Secret Santa) application. Users can create groups, add participants, fill out wishlists, and perform Secret Santa draws via REST API with a Vue frontend.

## Tech Stack

- **Backend:** Laravel 12, PHP 8.3
- **Frontend:** Vue 3, Tailwind CSS 4, Vite
- **Database:** SQLite (testing), configurable via `.env`

## Commands

| Task | Command |
|------|---------|
| Run tests | `composer test` |
| Format code | `composer format` |
| Start dev server | `php artisan serve` |
| Start frontend | `npm run dev` |
| Build frontend | `npm run build` |
| Deploy | `dep deploy` |

## Code Style & Conventions

- **Always use `declare(strict_types=1);`** in all PHP files
- **English variable/method names** only
- Follow **Mago formatter** rules (see `mago.toml`)
- Laravel integrations enabled for linting
- PSR-4 autoloading

## Architecture

### Domain Models

- **User** - Participants in Secret Santa groups
- **Group** - Wichtel groups containing users
- **Statistic** - Usage statistics
- **Wishlists** - User gift preferences within groups

### Structure

- `app/` - Application code (Models, Controllers, Jobs, Mail, Policies)
- `resources/views/` - Blade templates
- `resources/js/` - Vue components
- `routes/` - API and web routes
- `tests/` - PHPUnit tests (runs with in-memory SQLite)

## Testing

Tests run with PHPUnit using in-memory SQLite:

```bash
composer test
```

## Important Notes

- REST API is the primary interface; Vue frontend consumes it
- Check `mago.toml` for detailed linter/analyzer settings
