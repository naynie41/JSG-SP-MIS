# Jigawa State SP-MIS

State Social Protection Management Information System — a unified platform for
coordinating, registering, and tracking social protection beneficiaries and
benefits across MDAs.

- **Backend:** Laravel 12 (PHP 8.3), REST API only — under [api/](api/)
- **Frontend:** React + TypeScript (Vite) — under [web/](web/)
- **Data:** PostgreSQL 16 + PostGIS · **Cache/sessions:** Redis · **Queue:** RabbitMQ
- **Dev environment:** Docker Compose

See [docs/CLAUDE.md](docs/CLAUDE.md), [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md),
[docs/CONVENTIONS.md](docs/CONVENTIONS.md) and [docs/SECURITY.md](docs/SECURITY.md)
for how the project is built. The product spec is [docs/jigawa-SP-MIS.md](docs/jigawa-SP-MIS.md).

---

## Prerequisites

You only need **Docker** to run the full stack:

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) (or Docker
  Engine) with the Compose plugin — `docker compose version` should work.
- ~4 GB free RAM and ports `8080`, `5173`, `5432`, `6379`, `5672`, `15672` free
  (all overridable — see [Configuration](#configuration)).

PHP, Composer, and Node are **not** required on the host — they run inside the
containers. (They are only handy if you want to run tooling natively.)

---

## First-time setup

```bash
git clone https://github.com/naynie41/JSG-SP-MIS.git
cd JSG-SP-MIS
docker compose up -d --build
```

That's it. On first boot the containers automatically:

- create `api/.env` from `api/.env.example` and `web/.env` from `web/.env.example`,
- install Composer and npm dependencies,
- generate the Laravel `APP_KEY`,
- wait for PostgreSQL, then **run migrations** (which enable the PostGIS extension).

The **first** `up` takes a few minutes (image build + dependency install). Watch
progress with `docker compose logs -f api web`. Subsequent boots are fast.

> Dependencies and `APP_KEY` are generated for you. If you ever need to re-run
> these manually: `docker compose exec api composer install` and
> `docker compose exec api php artisan key:generate`.

---

## Running the stack

```bash
docker compose up -d        # start everything (detached)
docker compose ps           # check status — all services should be "healthy"
docker compose logs -f api  # follow a service's logs
docker compose down         # stop the stack (keeps data volumes)
docker compose down -v      # stop AND delete data (postgres/redis/rabbitmq, deps)
```

### Verify it's working

```bash
curl http://localhost:8080/api/v1/health
```

Expected — HTTP 200 with the standard success envelope, confirming DB
connectivity and that PostGIS is enabled:

```json
{
  "data": {
    "status": "ok",
    "service": "SP-MIS",
    "environment": "local",
    "time": "2026-06-29T12:00:00+00:00",
    "checks": {
      "database": {
        "connected": true,
        "driver": "pgsql",
        "postgis": { "enabled": true, "version": "3.4.x" }
      }
    }
  }
}
```

---

## Services & URLs

| Service        | URL / endpoint                          | Notes                                  |
| -------------- | --------------------------------------- | -------------------------------------- |
| API (via nginx)| http://localhost:8080/api/v1/health     | Laravel REST API                       |
| Web (Vite)     | http://localhost:5173                   | React + TS dev server (HMR)            |
| PostgreSQL     | `localhost:5432`                        | db `spmis`, user `spmis`               |
| Redis          | `localhost:6379`                        | cache + sessions                       |
| RabbitMQ AMQP  | `localhost:5672`                        | broker                                 |
| RabbitMQ UI    | http://localhost:15672                  | login `spmis` / `change-me-rabbitmq`   |

> The dev credentials above are placeholders from `*.env.example`. **Change them
> for any non-local environment** (see [Configuration](#configuration) and
> [docs/SECURITY.md](docs/SECURITY.md)).

---

## Database: migrations & seeders

Migrations run automatically on `api` startup. To run them manually:

```bash
docker compose exec api php artisan migrate          # apply migrations
docker compose exec api php artisan migrate --seed   # apply + run seeders
docker compose exec api php artisan migrate:fresh --seed   # rebuild from scratch
```

The first migration enables the PostGIS extension, so later spatial migrations
can use geometry/geography columns.

### Seeded data

`--seed` (run automatically on first boot) creates:

- the **seven roles** and their permissions (FR-UAM-01/05),
- **two sample MDAs** (Ministry of Health; Ministry of Women Affairs & Social Development),
- **one System Administrator** account for first login (below).

---

## First login (seeded admin + MFA)

Open the app at **http://localhost:5173**.

| Field    | Value                                    |
| -------- | ---------------------------------------- |
| Email    | `admin@spmis.local`                      |
| Password | `ChangeMe!Admin12345`                    |

> These come from `SEED_ADMIN_EMAIL` / `SEED_ADMIN_PASSWORD` in `api/.env.example`.
> **Change them for any shared/production environment** (the seeder skips production).

The System Administrator role **requires MFA**, so on first sign-in you'll see a
**"Set up MFA"** screen with a setup key and recovery codes:

1. Add the setup key to an authenticator app (Google Authenticator, Authy, 1Password…),
   **or** generate the current code from the terminal:
   ```bash
   docker compose exec -u www-data api php artisan tinker \
     --execute="echo (new PragmaRX\Google2FA\Google2FA)->getCurrentOtp('PASTE_SETUP_KEY');"
   ```
2. Enter the 6-digit code, **save the recovery codes**, and you're in.
3. On later logins you'll get the shorter **"Two-factor check"** step (same code source).

---

## Walkthrough: manage MDAs & users

Signed in as the admin:

1. **Create an MDA** — side rail → **MDAs** → **Create MDA** (name + type). It appears in the
   scoped list; use the row menu to **Edit** or **Deactivate/Activate**.
2. **Create a user** — side rail → **Users** → **Create user**. Set name/email, **assign the MDA**
   you just created and a **role** (e.g. *MDA Officer*), and a temporary password. Validation errors
   from the API render inline; success shows a toast.
3. **See scoping & RBAC in action** — sign out and sign in as the new user. They only see data for
   their MDA, and the navigation only shows sections their role permits. Actions they can't perform
   are hidden. (The server enforces this regardless of the UI.)
4. **Everything is audited** — each create/edit/status change and the auth events are written to the
   append-only `audit_log` (secrets redacted, PII masked). Inspect with:
   ```bash
   docker compose exec -u www-data api php artisan tinker \
     --execute="\App\Domain\Audit\Models\AuditLog::latest('created_at')->take(10)->get(['action','entity_type','actor_id','created_at'])->each(fn(\$a)=>print(\$a->action.' '.\$a->entity_type.PHP_EOL));"
   ```

Roles/permissions and the audit log design are documented in
[api/app/Domain/Access/README.md](api/app/Domain/Access/README.md) and
[api/app/Domain/Audit/README.md](api/app/Domain/Audit/README.md).
A Phase 1 requirement-mapping checklist is in [docs/PHASE-1-CHECKLIST.md](docs/PHASE-1-CHECKLIST.md).

---

## Running tests, lint & static analysis

```bash
# --- Backend (Laravel) ---
docker compose exec -u www-data api php artisan test          # PHPUnit suite
docker compose exec -u www-data api ./vendor/bin/pint --test  # code style (lint)
docker compose exec -u www-data api ./vendor/bin/phpstan analyse --memory-limit=512M  # static analysis (Larastan lvl 5)
# convenience: run all three
docker compose exec -u www-data api composer check

# --- Frontend (React) ---
docker compose exec web npm run test        # Vitest + Testing Library
docker compose exec web npm run lint        # oxlint
docker compose exec web npx tsc -b          # TypeScript typecheck
docker compose exec web npm run build       # production build
```

All of the above should be green on a fresh clone.

---

## The queue worker

The `worker` service runs `php artisan queue:work rabbitmq` and processes jobs
(bulk imports, duplicate matching, notifications, sync). Inspect queues in the
RabbitMQ UI at http://localhost:15672. Restart the worker after changing job
code: `docker compose restart worker`.

---

## Configuration

Configuration is via environment files (never commit real secrets — only the
`*.env.example` templates are tracked):

- **API:** [api/.env.example](api/.env.example) — DB, Redis, RabbitMQ, CORS,
  Sanctum, app settings.
- **Web:** [web/.env.example](web/.env.example) — `VITE_API_BASE_URL`, app name.

Compose ports and dev credentials can be overridden with a **root `.env`** file
(read automatically by Docker Compose), e.g.:

```dotenv
API_HTTP_PORT=8081
WEB_PORT=3000
POSTGRES_PASSWORD=use-a-strong-password
RABBITMQ_PASSWORD=use-a-strong-password
```

If you change `POSTGRES_PASSWORD` / `RABBITMQ_PASSWORD` at the compose level,
update the matching values in `api/.env` so the API can connect.

Security defaults: `APP_DEBUG=false`, strict CORS allowlist
(`CORS_ALLOWED_ORIGINS`), and baseline security headers on every API response.
Review [docs/SECURITY.md](docs/SECURITY.md) before deploying anywhere real.

---

## Troubleshooting

- **A service isn't `healthy`** — first boot installs dependencies; give it a few
  minutes and check `docker compose logs -f <service>`.
- **`/api/v1/health` returns 503** — the API can't reach PostgreSQL. Confirm the
  `postgres` service is healthy and that `DB_PASSWORD` in `api/.env` matches
  `POSTGRES_PASSWORD` used by the `postgres` container.
- **Port already in use** — override the port via the root `.env` (see above).
- **Reset everything** — `docker compose down -v && docker compose up -d --build`.
