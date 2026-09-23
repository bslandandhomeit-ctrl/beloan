# Technical Architecture Document — LandHome / beloan

System Architecture · Project Structure · Modules · API · Database · Security · Events · DevOps · Technical Debt

> Reverse-engineered from the current state of the repository (`bslandandhomeit-ctrl/beloan`, `uat` branch/remote) on 2026-09-22. No formal design docs exist for this system, so this document reflects what the code and deploy tooling actually do, not an idealized target architecture.

## 1. System Architecture

LandHome (internal name "beloan") is a monolithic server-rendered **Laravel 5.0** application providing loan origination, servicing, and back-office accounting for a land/home lending business. There is no SPA/frontend framework — views are Blade templates rendered by the same PHP process that handles business logic.

```
Browser (Blade views + jQuery-era assets)
        │  HTTP(S)
        ▼
Apache + PHP-FPM/mod_php  (docker image: php:5.6-apache)
        │
        ├── app/Http/Controllers/*   (57 controllers, ~1 per business module)
        ├── app/Models/*             (136 Eloquent models)
        ├── app/Http/API/*           (small JSON API surface, incl. Traccar GPS integration)
        └── helpers/*.php            (global procedural helpers, e.g. LoanCalculate.php)
        │
        ▼
MySQL 5.7 (single database `land_home`, table prefix `tb_`)
```

Key characteristics:
- **Single deployable unit** — no service boundaries; all modules (loans, teller/cash, accounting, HR, reporting) live in one codebase and one database.
- **Session-based, server-rendered auth** — no token/JWT layer for the web app; a small `App\Http\API` namespace exists for machine-to-machine/GPS tracking (Traccar) integration.
- **Excel/CSV-heavy reporting** — `maatwebsite/excel` (v2.1) and `league/csv` generate operational and regulatory reports (see `resources/views/exports/*`, `nbc_reports`, `CBCReportController`).
- **Scheduled jobs** run via the standard Laravel `artisan schedule:run` cron entry inside the app container rather than a separate worker/queue process (`QUEUE_DRIVER=sync`).

## 2. Project Structure

The git repository root (`landhome-stack`) is broader than the Laravel app itself — it also carries the local/UAT Docker environment and deploy tooling:

```
landhome-stack/
├── html/                   Laravel application (the "app" — deployed as-is to the server)
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/    57 controllers, one routes.php (3,483 lines, no route model binding/resource routes)
│   │   │   ├── API/             App\Http\API — JSON endpoints (loan API, Traccar devices/users)
│   │   │   ├── Middleware/      Auth, CSRF, XSS-strip, locale, agent/device checks
│   │   │   ├── Requests/
│   │   │   └── ViewComposers/
│   │   ├── Models/              136 Eloquent models, partially namespaced (Cbc/, Country/, Industries/, Products/, Project/)
│   │   ├── Events/, Handlers/    Laravel 5.0 event scaffold (present but effectively unused, see §6)
│   │   ├── Providers/            App/Route/Event/Bus/Config service providers (framework defaults, lightly customized)
│   │   ├── Services/             Registrar.php (auth registration only)
│   │   ├── Console/Commands/     Artisan commands
│   │   └── Translation/          Localization support (KH/EN)
│   ├── resources/views/         ~45 module-aligned Blade view directories (teller, loans, accounting, clients, reports, …)
│   ├── config/                  Framework + app config, incl. `static_data` (permission allow-lists) and `database.php`
│   ├── public/                  Web root (Apache DocumentRoot points at `html/public`)
│   ├── dbm/                     Bundled third-party DB-admin tool (phpMyAdmin-family, own composer.json) — see §9
│   └── storage/, bootstrap/     Framework runtime dirs
├── docker/
│   ├── Dockerfile               php:5.6-apache image, custom GD/Imagick/LibXL/php_excel build
│   ├── vhost.conf, php/uploads.ini, example-crontab
│   └── sql/                     Hand-maintained schema patches (no Laravel migrations — see §5)
├── mysql/                       MySQL 5.7 data volume (docker-compose bind mount)
├── dev-tools/livereload/        Local dev convenience tooling
├── docker-compose.yml           3 services: mysql, apache (app), phpMyAdmin
├── start.bat / watch.bat        Local Windows dev-stack helpers
├── deploy-uat.cmd               Scripted push-to-UAT with pre/post backups (see §8)
└── rollback-uat.cmd, last-rollback-point.txt   Rollback tooling paired with deploy-uat.cmd
```

Route prefixes in `app/Http/routes.php` are the closest thing to a module map: `loans`, `teller`, `accounting`, `client`, `credits`, `bcash`, `contract`, `dealer`, `invoice`, `loan_recovery`, `nbc_report`, `product`, `project`, `promotion`, `property`, `reports`, `representative`, `sale`, `setting`, `unit*`, `user`, `hr_management`, `migration`, `audit`, `commission`, `company`, `bank`, `api`.

## 3. Modules

The application is organized as functional modules, each roughly a (Controller + Models + Blade view directory + route prefix) group:

| Module | Controller(s) | Purpose |
|---|---|---|
| **Loans** | `LoanController`, `LoanGuarantorController`, `LoanRecoveryController`, `LoanRescheduleApproveController`, `LoanVerifyAndApproveController`, `LoanTypeConfigController`, `ApiLoanController` | Loan origination, approval workflow, guarantors/co-borrowers, restructuring, disbursement |
| **Teller / Cash** | `TellerController` (3,328 lines), `PrintController` | Branch teller transactions, receipts (incl. yearly detail reports — actively being extended, see git status) |
| **BCash** | `BCashController` | Mobile-money/branchless-cash transaction channel |
| **Accounting** | `AccountingController`, `CurrencyController`, `TransactionCodeController` | GL/journal, currency exchange, chart-of-accounts-style transaction codes |
| **Clients** | `ClientController`, `CoBorrowerController`, `TransferClientController`, `CBCReportController` | Borrower CRM, credit bureau (CBC) reporting |
| **Assets / Property** | `AssetController`, `UnitController`, `UnitStatusController`, `UnitTypeController`, `ProjectController`, `ProductController` | Collateral/property inventory tied to land/home loans |
| **Sales & Promotions** | `SaleController`, `SalePersonController`, `PromotionController`, `DealerController` | Sales pipeline and dealer/agent management |
| **Reporting** | `ReportController`, `NBCReportController` (National Bank of Cambodia regulatory reports), exports in `resources/views/exports/*` | Excel/CSV report generation |
| **Contracts & Invoicing** | `ContractController`, `InvoicePayment`/`Invoice` models | Contract and invoice lifecycle |
| **Admin/Platform** | `AdminController`, `UserController`, `StaffController`, `SettingController`, `SystemDateController`, `AuditController`, `NotificationController`, `CommissionRateController`, `PaymentOptionController`, `PaymentTypeController`, `CompanyController` | Back-office/user management, audit trail, system "as-of" date, commissions |
| **Auth** | `AuthController`, `PasswordController` | Login, password reset, `FailedLogin`/`Accessible` (IP allow-list) tracking |
| **Integrations** | `app/Http/API/Controllers/Traccar/*` | GPS/fleet tracking integration (Traccar) exposed as its own controller namespace |
| **Migration/Legacy tooling** | `MigrationController` | One-off/ad-hoc data migration endpoints |

Several controllers carry date-suffixed duplicates (`LoanController03102021.php`, `RepaymentController03102021.php`, `ReportController03102021.php`) that are not wired into `routes.php` — these are inactive snapshot copies left in place instead of being handled via git history (see §9).

## 4. API

There is no formal REST/OpenAPI layer. The API surface is narrow and split across two patterns:

- **`app/Http/API/Controllers/`** — a small, explicitly namespaced JSON API:
  - `MyController.php` — general API endpoint(s)
  - `Traccar/DevicesController.php`, `Traccar/UserController.php` — proxy/bridge to the Traccar GPS platform
- **`ApiLoanController`** (in the main `Controllers` namespace) — loan-related endpoints exposed outside the session-authenticated web flow, grouped under the `api` route prefix.
- **AJAX endpoints** — the bulk of "API-like" traffic is actually same-origin AJAX calls into ordinary web controllers (`Route::group(['prefix' => ...])` blocks), authenticated via the session and gated by the permission system in §6, not a separate API auth scheme. An explicit `ajax_permission` allow-list exists in `config('static_data')` for AJAX actions that should bypass fine-grained permission checks.

There is no API versioning, no rate limiting, and no centralized request/response contract (e.g., FormRequest validation is used only in `app/Http/Requests` selectively, not uniformly).

## 5. Database

- **Engine**: MySQL 5.7 (UAT/local via Docker), `utf8mb4` at the container level but Laravel's `config/database.php` still declares `utf8`/`utf8_unicode_ci` — a latent mismatch worth checking against actual table charset if you hit encoding issues.
- **Database name**: `land_home`; **table prefix**: `tb_`.
- **No Laravel migrations** — there is no `database/migrations` directory. Schema is managed entirely by hand-applied SQL scripts under `docker/sql/` (e.g. `2026_09_14_schema_sync.sql`, `payment_types.sql`, plus the two new files for the current teller yearly-report work: `teller_receipt_detail_yearly_count.sql`, `teller_receipt_detail_yearly_report.sql`). Schema changes are deployed by running these SQL files against UAT/prod manually (or via `deploy-uat.cmd`'s workflow) rather than `artisan migrate`.
- **Models**: 136 Eloquent models under `app/Models`, mostly flat with a few sub-namespaces (`Cbc/`, `Country/`, `Industries/`, `Products/`, `Project/`). Naming is inconsistent (`asset.php`, `assetCode.php` lower-camel vs. `AssetDepreRecord.php` PascalCase), and some models are explicitly marked as unused (`Cbc/client_cbc_info_not_use.php`).
- **DB tooling in-repo**: `html/dbm/` is a bundled third-party database-admin web tool (its own `composer.json`/`ChangeLog`, phpMyAdmin-family feature set — table designer, query-by-example, import/export) checked directly into the app tree and presumably served alongside the app. This is a live SQL-execution tool with no visible access restriction beyond whatever Apache/App auth is configured — treat as a high-priority item to review (see §9).
- **Backups**: `witty/laravel-db-backup` package is a dependency; `deploy-uat.cmd` also takes an ad-hoc `mysqldump --no-data` schema snapshot before every deploy as a safety net, in addition to a code snapshot commit on the server.

## 6. Security

- **Authentication**: standard Laravel session auth (`App\Models\User` implements `Authenticatable`), guarded by `App\Http\Middleware\Authenticate`.
- **Authorization**: custom, non-framework RBAC layered on top of `auth` middleware:
  - `User::role()` → `Role` (single role per user via `role_id`), plus a many-to-many `permission()` relation (`user_permission` pivot) checked per-route via `User::checkPermission($action)`, keyed by route name/action.
  - Permission checks are bypassed for a hardcoded `$default` allow-list of routes (home, login, profile, etc.) merged with `config('static_data')['ajax_permission']`.
  - Roles `1` and `2` (super-admin tiers, by convention/comment) skip the extra **IP allow-list check**; all other roles are checked against `App\Models\Accessible` (`ip_start_range`, via `INET_ATON`) keyed off `User::login_ip` — effectively an IP-range allow-list for non-admin users. Note: `Authenticate::accessible()` contains a leftover `dd($acc . ' > unaccessable')` debug statement in the denial path (see §9).
  - Resolved permissions are cached into the session (`ROLE_PERMISSION`) rather than re-queried per request.
- **CSRF**: standard Laravel `VerifyCsrfToken` middleware, enabled globally (no custom exclusions found).
- **XSS mitigation**: a custom `XSSProtection` middleware (`strip_tags` over all PUT/POST/AJAX input) exists and is registered as the `xss` route middleware alias, but it is **not attached** to the global middleware stack or to any route group in `routes.php` — it is currently dead code unless applied ad hoc elsewhere.
- **Other middleware**: `Locale` (i18n), `CheckAgent` (device/user-agent detection via `jenssegers/agent`), `RedirectIfInsecure` / `HttpProtocal` (HTTPS enforcement — `HttpProtocal` is commented out of the active Kernel stack).
- **Secrets**: `.env` holds `APP_KEY`, DB credentials, mail credentials. `docker-compose.yml` also hardcodes the MySQL root password (`BSLH@2020`) directly in source control, and `deploy-uat.cmd` embeds the same password in plaintext SSH commands for `mysqldump` — both are committed secrets and should be rotated/externalized.
- **Login hardening**: `FailedLogin` model suggests basic failed-attempt tracking exists, but no explicit lockout/throttle policy was found wired into `AuthController`.
- **Audit trail**: `AuditController` + `Audit` model provide some level of action logging, but coverage across 57 controllers has not been verified module-by-module.

## 7. Events

Laravel's event system is present but essentially a framework skeleton, not an active integration pattern:
- `app/Events/Event.php` — the abstract base event class only.
- `app/Handlers/Events/` — **empty**, no listeners implemented.
- `app/Providers/EventServiceProvider.php` — still contains the Laravel boilerplate placeholder mapping (`'event.name' => ['EventListener']`), never replaced with real bindings.
- In practice, cross-cutting concerns (audit logging, notifications, commission calculation, loan status transitions) appear to be handled with direct procedural calls inside controllers/models rather than dispatched events. Anyone planning to decouple business logic should expect to introduce real events from scratch rather than extend existing ones.

## 8. DevOps

- **Local/UAT environment**: `docker-compose.yml` defines three services — `landhome-uat-mysql` (MySQL 5.7, host port 3331), `landhome-uat-apache` (custom image `landhome-uat-docker-app` built from `docker/Dockerfile`, host port 8081, bind-mounts `./html`), and `landhome-uat-pma` (phpMyAdmin, host port 7771). `start.bat`/`watch.bat` wrap `docker compose up`/log-tailing for Windows devs.
- **Application image**: `docker/Dockerfile` builds `php:5.6-apache` with a large custom extension chain (GD, Imagick, mcrypt, intl, calendar) plus two source-built dependencies: `LibXL` (commercial Excel library) and a `php_excel` PECL-style extension compiled from a GitHub fork — both fetched via unauthenticated `curl` at build time, which is a build-fragility and supply-chain risk (see §9). A cron job (`docker/example-crontab`) runs `artisan schedule:run` every minute inside the container.
- **Note on PHP version drift**: the Docker image targets PHP 5.6, but the CLI available in the current shell reports PHP 7.1.33 — confirm which runtime actually serves UAT/production before assuming Docker is the source of truth for behavior parity.
- **Deployment**: `deploy-uat.cmd` is a hand-rolled, interactive Windows batch script that: shows a pre-deploy diff/status, requires manual confirmation, pushes to the `uat` remote branch, builds a `git archive` of `html/`, SSHes into the UAT host to back up server code (`tar`) and DB schema (`mysqldump --no-data`) into `/tmp`, snapshots server-side state as a rollback commit (recorded in `last-rollback-point.txt`), and proceeds through further steps (truncated in this review at step 5/8) to actually ship the archive and restart the container. `rollback-uat.cmd` pairs with it to revert to the last recorded rollback point.
- **Source control / environments**: `origin` → `github.com/bslandandhomeit-ctrl/beloan` (deployment source of truth); a second remote `uat` points directly at the UAT server's working copy (`uat@10.3.0.99:/home/uat/SYS/landhome_uat/html`), meaning the UAT server itself is a git working tree that receives pushes and self-commits pre-deploy snapshots. There's no visible CI pipeline (no `.github/workflows`, no CI config) — `deploy-uat.cmd` *is* the pipeline.
- **No automated test execution in the deploy path** — see §9.

## 9. Technical Debt

Ranked roughly by risk/impact:

1. **End-of-life framework and runtime.** Laravel 5.0 (released 2015) and a Docker base image of PHP 5.6 are both long past EOL and unsupported for security patches. This is the single largest structural risk to the system.
2. **Bundled live DB-admin tool (`html/dbm/`) shipped inside the app.** A full-featured SQL/table-design tool committed into the deployable web root, whose access control depends entirely on incidental Apache/app-level auth rather than being isolated (e.g., behind a separate internal-only service like the existing phpMyAdmin container). Worth an explicit review of whether it's still needed and, if so, whether it should be removed from the public web root.
3. **Debug statement in production auth path.** `App\Http\Middleware\Authenticate::accessible()` contains a live `dd(...)` call in the "not accessible" branch — this will halt the request with a debug dump instead of failing gracefully for any non-admin user hitting the IP-restriction branch.
4. **Hardcoded secrets in source control.** MySQL root password in `docker-compose.yml` and duplicated in `deploy-uat.cmd`'s SSH command; should move to an untracked `.env`/secrets file or deploy-time secret injection.
5. **No schema migration system.** Schema changes are one-way hand-applied SQL scripts (`docker/sql/*.sql`) with no tracked "current schema version," no rollback mechanism beyond the deploy script's ad hoc `mysqldump --no-data` snapshot, and no way to reproduce a fresh environment from migrations alone.
6. **No automated tests exercised.** `phpunit/phpunit` and `phpspec/phpspec` are dev dependencies, but no `tests/` directory or `*Test.php` files exist in the app; correctness currently relies entirely on manual QA against UAT.
7. **Monolithic, ungrouped routing and controllers.** `app/Http/routes.php` is 3,483 lines with inconsistent use of `Route::group`/prefixes/middleware (no `Route::resource`, mixed closures and controller actions, permission checks done at request-time inside middleware rather than declaratively). `TellerController.php` alone is 3,328 lines — a strong signal that several modules would benefit from decomposition (e.g., splitting teller transaction types, receipts, and reporting into separate controllers/services).
8. **Dead/duplicate code left in place instead of relying on git history.** Date-suffixed controller copies (`LoanController03102021.php`, `RepaymentController03102021.php`, `ReportController03102021.php`) and models marked `_not_use` are unreferenced by `routes.php` but still ship in every deploy, increasing surface area for confusion and accidental use.
9. **`XSSProtection` middleware defined but never applied.** Registered as the `xss` route-middleware alias but not attached to any route or the global stack — currently provides no protection despite existing in the codebase (may create false confidence during review).
10. **Unused event system.** `EventServiceProvider` still has the Laravel scaffold placeholder; `app/Handlers/Events/` is empty. Business events (loan approved, payment received, teller transaction posted) are handled inline in controllers, making side effects (notifications, audit logs, commission triggers) harder to trace and extend consistently.
11. **Build-time dependency on unauthenticated external downloads.** `docker/Dockerfile` `curl`s a commercial library (`libxl.com`) and a third-party GitHub fork (`php_excel`) at image-build time with no checksum verification — a build reliability and supply-chain integrity gap.
12. **Deploy process is a manual, interactive Windows batch script** rather than a CI/CD pipeline — no automated gating (tests, linting) before code reaches UAT, and the "pipeline" only exists on whichever machine has `deploy-uat.cmd` and the SSH key.
13. **Inconsistent model/file naming** (`asset.php`, `assetCode.php` vs. `AssetDepreRecord.php`) and a `Http/API` namespace living alongside a same-purpose `ApiLoanController` in the main `Controllers` namespace — no single convention for where "API" code belongs.

---
*This document describes system state as observed in the repository; it should be revisited whenever major modules (routing, DB migration strategy, or the deploy pipeline) change.*
