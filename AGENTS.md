# AGENTS.md

## Project Overview

**Wichtel.me** is a Secret Santa ("Wichteln-as-a-Service") application. Organizers create groups, invite participants by email or share a self-registration link, collect wishlists, and start a secret draw. The UI and emails are primarily German; code identifiers are English.

The local application is already served at **http://wichtel.test/**. Use it for browser checks rather than starting a second PHP server. Do not submit forms that send email or modify existing groups merely to explore the application.

## Tech Stack

- **Backend:** Laravel 12, PHP `^8.3`, Composer, PHPUnit 12.
- **Frontend:** Vue 3 Options API, Tailwind CSS 4, Vite 7, Axios, Day.js; JavaScript ES modules, not TypeScript.
- **Database:** configured in `config/database.php` and `.env`; tests use in-memory SQLite. `.env.example` defaults to MySQL.
- **Node:** Vite 7 requires Node `^20.19.0 || >=22.12.0`.

Despite the framework version, this project retains the older Laravel application layout: models live directly under `app/`, and bootstrap, HTTP middleware, routes, and scheduling use explicit kernels and providers. Do not replace them with a fresh Laravel 12 skeleton as part of unrelated work.

## Commands

| Task | Command |
|------|---------|
| Install PHP dependencies | `composer install` |
| Install frontend dependencies | `npm ci` |
| Run tests | `composer test` |
| Run a targeted test | `composer test -- --filter=JoinLinkApiTest` |
| Inspect routes | `php artisan route:list --except-vendor` |
| Check PHP formatting without edits | `vendor/bin/mago fmt --check path/to/file.php` |
| Format changed PHP files | `vendor/bin/mago fmt path/to/file.php` |
| Format configured PHP sources | `composer format` (alias: `composer fmt`) |
| PHP lint / static analysis | `vendor/bin/mago lint` / `vendor/bin/mago analyze` |
| Start frontend with hot reload | `npm run dev` |
| Build frontend | `npm run build` |
| Start an alternative PHP server if needed | `php artisan serve` |

- Composer install/update hooks run Artisan optimization and package discovery; they are not side-effect-free.
- `composer format` covers the configured `app/` and `tests/` sources. Prefer explicit changed paths to avoid unrelated formatting changes.
- Vite entry points are `resources/css/app.css` and `resources/js/app.js`; Blade loads them with `@vite`. Build output is ignored under `public/build/`; `public/hot` points to a running Vite dev server. Do not edit generated assets or use the legacy Mix manifest as the current build configuration.
- There is no frontend test, lint, or typecheck npm script. Use the production build plus browser checks for frontend changes.

## Code Style & Conventions

- Use `declare(strict_types=1);` in new PHP files and add it when modifying existing PHP files; older files do not consistently have it yet.
- Use meaningful English variable and method names. Only `i` is allowed as a short variable name, for loop indexes.
- Follow Mago formatting and `mago.toml` (PHP target 8.3; Laravel and PHPUnit linter integrations).
- Use existing namespaces and patterns, such as `App\Group`, `App\User`, Eloquent relationships, controller validation, policies, and mailables. Do not introduce `app/Models/` or a new authentication framework without a task requiring it.
- Keep Vue components in their existing Options API style. Follow the German UI wording and existing Tailwind styles.
- For UI changes, use semantic controls, explicitly associated form labels, visible keyboard focus, accessible validation messages, and appropriate text alternatives. Check keyboard use and the rendered affected states, including mobile layouts when relevant.

## Architecture

### Backend entry points

- `bootstrap/app.php` binds `app/Http/Kernel.php`, `app/Console/Kernel.php`, and the exception handler.
- `app/Providers/RouteServiceProvider.php` loads web routes and prefixes API routes with `/api`; `routes/api.php` adds `/v1`. Controller routes use the provider's `App\Http\Controllers` namespace.
- `WichtelGroupController` owns group CRUD, starting draws, and generating/revoking join links. `WichtelMemberController` owns membership operations, wishlists, invitation resending, and admin status changes.
- `SelfRegistrationController` handles `/join/{joinToken}`. `TokenController` handles email approval/decline links: GET `/answer` displays confirmation, POST `/answer/confirm` performs the action. Preserve this distinction so email link scanners do not change participation.
- `FrontendController` renders the welcome page, legal pages, and token-protected group view. `RegistrationController` renders `/wichtelgroup/create`.
- `app/Policies/` contains group/user authorization and is registered in `AuthServiceProvider`. Keep authorization on the server, not just in UI visibility rules.

### Data and domain rules

- Models are `app/Group.php`, `app/User.php`, and `app/Statistic.php`. There is **no separate Wishlist model**.
- The `group_user` pivot stores per-group `status`, `buddy_id`, `wishlist`, `is_admin`, and approval `token`. Do not move these onto the global user record.
- Group statuses are `created` and `started`; membership statuses include `invited`, `approved`, and `declined`. A group admin is a member with `is_admin`, not the site administrator.
- The same email address can have separate user records in different groups. Self-registration rejects duplicates within the target group; do not impose global email uniqueness without changing the intended behavior and its tests.
- `app/Jobs/WichtelJob.php` shuffles approved participants and assigns recipients in a cycle. At least three approved participants are needed; a smaller group is reset to `created` and the organizer is notified. Preserve recipient secrecy and participation filtering.

### Authentication and tokens

- `config/auth.php` uses the session-based `web` guard and Laravel's token-based `api` guard, not Sanctum or Passport. Protected API controllers use `auth:api`; Vue sends `Authorization: Bearer <api_token>`.
- `/wichtelgroup/{group}?token=...` resolves a user's `api_token`, logs them into the web guard, and checks group access. Vue reads this token from the URL for API calls.
- Keep the three token purposes separate: user `api_token` grants access, pivot `token` confirms participation, group `join_token` enables public self-registration. Treat them as secrets in logs and review artifacts.
- `/admin` is a separate site dashboard protected by `AdminAuth`, a session flag, throttling, and `ADMIN_PASSWORD` via `config/app.php`; an unset password disables access.

### Frontend and mail

- Blade owns pages and `resources/views/layouts/app.blade.php`; Vue mounts into `#app`, not a standalone SPA router.
- `resources/js/app.js` registers `CreateWichtelGroupForm.vue`, `WichtelgroupView.vue`, and `MemberAddForm.vue`. The group view receives Blade props and performs API requests.
- Mailables live in `app/Mail/`, templates in `resources/views/emails/`. Both queued and synchronous mail calls exist; invitation mail rendering also creates/persists the approval token, so rendering is not always read-only.
- Queue configuration reads **`QUEUE_DRIVER`**, defaulting to `sync`; mail configuration reads **`MAIL_MAILER`** (not the older `MAIL_DRIVER` in `.env.example`). Use a safe local mail transport such as `log` when testing mail manually. Verify effective configuration before running jobs.

## Testing and Verification

- Run `composer test` for backend changes. Tests extend `Tests\TestCase`; database tests use `DatabaseMigrations` and `User::factory()` / `Group::factory()`.
- `phpunit.xml` forces the testing environment and in-memory SQLite; `tests/CreatesApplication.php` additionally overrides the database connection. Never run destructive database commands against the normal local environment to prepare tests.
- Use `Mail::fake()` / `Queue::fake()` where a test should not send messages or execute a draw. Test observable results and authorization boundaries.
- `tests/Feature/` covers group/member APIs, join links, and self-registration; `tests/Unit/` contains Laravel-backed model tests, not isolated pure unit tests.
- For frontend changes, run `npm run build` and inspect the affected pages at `http://wichtel.test/`. Exercise representative success/error and admin/member states as relevant, using disposable data and safe mail settings.
- `.github/workflows/php.yml` runs Composer validation and PHPUnit on PHP 8.3, 8.4, and 8.5. Do not assume CI currently checks frontend builds or Mago.

## GitHub Workflow and Accompanying Project Management

- **Repository:** `obstschale/wichteln`. GitHub issues are the durable source of truth for scope, decisions, acceptance criteria, and blockers; Amp threads are the workspace for discussion and implementation.
- **Wishlist planning:** read the current [concept and parent issue #72](https://github.com/obstschale/wichteln/issues/72) and the existing [Wishlists project](https://github.com/users/obstschale/projects/1), especially its [development plan](https://github.com/users/obstschale/projects/1/views/7). Prefer its current fields, phases, milestones, and labels over creating a parallel plan.
- For an assigned issue, the agent is authorized to maintain relevant issue bodies/comments, checklists, project fields, labels, milestones, and dependency links as part of the work, without asking for each routine update. This is accompanying project management during active work, not unattended monitoring or permission to start other work packages automatically.
- Prefer one Amp thread per implementation work package. Product discussions may stay in an existing thread, but record their agreed outcomes in GitHub so future threads do not depend on chat history.

### Before and During Work

1. Read the live issue, parent concept, comments, acceptance criteria, project entry, and native `blocked by` dependencies before starting. Inspect the relevant code and distinguish already implemented behavior from proposed work.
2. Resolve questions when the affected package starts, not all questions for the entire project upfront. Discuss consequential product choices with the user in Amp; do not turn a recommendation or estimate into an approved decision.
3. Keep the current agreed decision in the issue body and add a concise comment for significant decisions, their reasons, verification results, or blockers. Re-read live content before editing and preserve others' changes. Do not copy entire chat transcripts or add a comment for every tool call.
4. Update affected follow-up issues and native dependency links when an agreed decision changes their scope. Keep textual dependency references consistent with GitHub's actual relationships. Do not remove a blocker merely to make a package appear ready.
5. Keep priority, area labels, milestone/phase, and effort estimates aligned with the actual work. Explain material estimate or roadmap changes; dates are planning scenarios until explicitly agreed, not silent commitments or claims that development has started. Large scope/priority changes and new product directions require user agreement.

### Status and Completion Rules

- **Backlog:** not yet selected or prerequisites unresolved. **Ready:** acceptance criteria are actionable and all blocking prerequisites are satisfied. **In progress:** the assigned package is actively being worked on. **In review:** ready for the applicable review/acceptance, with verification and delivery state documented. **Done:** the closure rules below are met.
- If work becomes blocked, leave the issue open and document the blocker and needed decision; do not mark it Ready or Done. Do not change an issue being actively owned by someone else without coordination.
- Mark checklist items complete only when their stated result is actually achieved. Tests, legal reviews, user decisions, merges, and deployments are different deliverables; never imply one proves another.
- For implementation issues, close the issue and set Done only after acceptance criteria and relevant checks are satisfied, the user has accepted the result, and the changes have been delivered to the agreed shared target branch (merged PR or authorized direct push). Local edits, local commits, or a draft PR alone are not completion. Deployment is required only when the issue's acceptance criteria require it, and needs its own authorization.
- For product/planning/legal issues without code, close only after the required decisions or review outcomes are documented and accepted by the user. A checklist of future legal checks is not a completed legal review.
- After completion, inspect downstream dependencies and move only genuinely unblocked, actionable packages to Ready. Keep the parent issue and project overview consistent; report what is complete, what remains blocked, and the next startable package. Wishlist's parent includes later enhancements, so total sub-issue progress is not the MVP completion measure; use the documented MVP phases and launch criteria.

### Authorization and Access Boundaries

- Routine GitHub maintenance does not authorize code commits/pushes, deployments, production migrations/writes, account registrations, tax filings, partner-contract acceptance, paid services, or irreversible/shared-system actions beyond the assigned issue updates. Obtain the specific authorization when needed.
- Do not independently decide unresolved product, legal, or business questions, weaken release gates, or mark external approvals complete. Record uncertainty and who must resolve it.
- Use `gh` or another available authenticated GitHub integration. If permissions prevent updates, report the exact remaining action and request the minimum necessary access through the normal authorization flow; never claim GitHub was updated when it was not.
- Never include real participant data, access tokens, credentials, private legal/financial documents, or other sensitive information in public issues, comments, or review artifacts.

## Operations and Reference Material

- `app/Console/Kernel.php` schedules account statistics and orphan-user cleanup daily, and deletion notifications/group cleanup hourly. Commands under `app/Console/Commands/` can delete data or send mail; do not run them just to inspect the project.
- `deploy.php` uses Deployer with a production host. Deployment installs dependencies, builds frontend assets, and restarts PHP. **Only deploy with explicit authorization**; it is not a local verification step.
- `reference/wichtel-api.v1.yaml` is the OpenAPI reference. For API changes, compare it with actual routes/controllers and update affected documentation; do not assume it already covers every recent endpoint.
- `changelogs/unreleased/` holds YAML changelog entries; `CHANGELOG.md` contains released history.
- `README.md` mainly contains historical project background. Use code, configuration, and tests as the source of truth for current behavior.
- Never commit `.env`, credentials, real participant data, access tokens, or generated build/cache files. Keep unrelated changes out of the task.
