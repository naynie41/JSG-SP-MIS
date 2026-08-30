# DEPLOY.md — Production Deployment (GHCR + docker-compose)

Production runbook for SP-MIS on the provided VPS. The CI pipeline
(`.github/workflows/release.yml`) builds and pushes images to GHCR on a version
tag; **the VPS only ever PULLS images — it never runs `docker build`.** Companion:
[SCALE-AND-AVAILABILITY.md](SCALE-AND-AVAILABILITY.md), [SECURITY.md](SECURITY.md).

---

## 0. Topology

```
            Internet ──► :80/:443  nginx (edge: TLS termination + security headers)
                                     │  /api,/up ─fastcgi─► api (php-fpm)
                                     │  /*        ─proxy──► web (SPA, nginx :8080)
   internal network ────────────────┼──────────────────────────────────────────
     api ─┬─ postgres:16+PostGIS   worker (queue:work)   scheduler (schedule:work)
          ├─ redis:7               (all internal-only — NOT published to the host)
          └─ rabbitmq:3-management
```

Only **nginx** publishes ports (80/443). Postgres, Redis, RabbitMQ, php-fpm, the
worker, the scheduler and the SPA are reachable **only** on the internal Docker
network. Uploaded documents and the data services persist in named volumes.

---

## 1. Pre-go-live checklist — decisions and secrets SP-MIS cannot supply for itself

Work through this **before** §2. Nothing here can be defaulted safely: each item is
either a real credential, a value that depends on your domain, or a policy decision that
belongs to a named owner (CLAUDE.md §8 — never hard-code a stakeholder decision).

A tick means *someone decided*, not *someone typed something*. Several of these are
silent when wrong: the system keeps working and quietly does the wrong thing.

### 1.1 Secrets to generate or obtain

| Value | Where it comes from | Owner |
|---|---|---|
| `APP_KEY` | `php artisan key:generate --show` | DevOps |
| `BACKUP_ENCRYPTION_KEY` | `php artisan backup:keygen`, stored **apart from** `AWS_*` | DevOps |
| `DB_PASSWORD` = `POSTGRES_PASSWORD` | generated; the two MUST be identical | DevOps |
| `REDIS_PASSWORD`, `RABBITMQ_PASSWORD` | generated | DevOps |
| `MAIL_HOST` / `MAIL_USERNAME` / `MAIL_PASSWORD` | your SMTP provider | IT |
| `AWS_*`, `AWS_BUCKET` | offsite object store, a **different failure domain** than this VPS | DevOps |
| `GHCR_OWNER`, `IMAGE_TAG` | your GitHub org + the release tag to run | DevOps |

> **`APP_KEY` is generated once and never rotated with data present.** It decrypts
> NIN/BVN and TOTP secrets; changing it orphans every encrypted column and every backup
> taken before the change. Rotation is a re-encryption project, not an edit.

> The initial administrator password is **prompted** by `spmis:create-admin` (§2.6) — it
> is never an argument or an env var, so it stays out of shell history and `ps`.

### 1.2 Values that depend on your domain

Everything below ships as `spmis.example.gov.ng` and must be changed together:

`APP_URL` · `SPA_URL` · `CORS_ALLOWED_ORIGINS` · `SANCTUM_STATEFUL_DOMAINS` ·
`NGINX_SERVER_NAME` · `NGINX_SSL_CERTIFICATE` · `NGINX_SSL_CERTIFICATE_KEY` ·
`MAIL_FROM_ADDRESS` · the admin email in §2.6.

- **`APP_URL` is the API; `SPA_URL` is where a human is sent.** They are separate on
  purpose — notification emails build their links from `SPA_URL` (FR-NOT-01). In the
  default single-domain topology (§0) they are the same host; if you split the SPA onto
  its own domain, they are not.
- `SANCTUM_STATEFUL_DOMAINS` takes **no scheme**; `CORS_ALLOWED_ORIGINS` takes a full
  origin. A mismatch fails at login, loudly.

### 1.3 Authentication policy — confirm, do not inherit

| Setting | Ships as | Decide |
|---|---|---|
| Roles requiring MFA | System Administrator, Executive | **MDA Admin is NOT on this list** — yet it holds `beneficiary.export`, approves request-to-serve, and creates activities. Confirm this is intended. |
| Account lockout | 5 attempts, 1 min → ×2 → 60 min cap | Confirm with the security owner |
| Session | 30 min idle, 8 h absolute | Confirm |
| Rate limits | 10 exports/min, 12 imports/min per user | Confirm |
| `export.reveal_pii` | System Administrator only | **DPO decision** to grant to anyone else (SECURITY.md §3) |

`MFA_ENFORCE` is deliberately **absent** from `.env.prod.example`. It can only ever
*disable* enforcement outside production (`User::mfaRequired()` ignores it when
`APP_ENV=production`), so setting it in production has no effect — do not add it.

### 1.4 Stakeholder / DPO decisions still at shipped defaults

These run on defaults that were chosen to be reasonable, **not** chosen by you:

| Decision | Ships as | Owner |
|---|---|---|
| Matching review / auto-accept thresholds | `0.75` / `0.92` | SP Coordination |
| Fuzzy field weights | last_name .25, first_name .15, DOB .20, phone .20, LGA .05, ward .05, address .10 | SP Coordination |
| `REPORTING_MIN_CELL_SIZE` (small-group suppression) | `5` | DPO |
| `PRIVACY_RETENTION_ENABLED` + policy list | `false`, **empty** | DPO |
| `PRIVACY_PROCESSING_REQUIRES_CONSENT` | `false` | DPO |
| Referral SLA | 48 h accept / 168 h complete | SP Coordination |
| Grievance SLA | 72 h payment & data-correction, 120 h eligibility/quality/complaint, 168 h other | SP Coordination |

The matching values are editable in the admin console after go-live; the retention and
consent settings are env-driven and need a restart.

### 1.5 Reference data you must supply

- **Wards.** `lgas` is seeded from the committed 27-LGA enum, but `wards` ships EMPTY and
  ward names are never invented (GEO.1). Load the authoritative dataset with
  `reference:load-divisions` (§3.1). Until you do, ward validation stands down and ward
  is accepted as free text — see `KnownWard`.

---
## CI/CD: two workflows, one deliberate release

The pipeline is **VS Code → GitHub → Actions → GHCR → the VPS pulls**. The VPS never runs
`docker build`; it only ever pulls an image someone decided to release.

| Workflow | Fires on | Does | Pushes |
|---|---|---|---|
| `.github/workflows/ci.yml` | push / PR to `main` | Backend suite, Pint, PHPStan, `composer audit`; frontend suite, `tsc`, oxlint, design-token check, `npm audit`; then **builds both production images** | **Nothing** |
| `.github/workflows/release.yml` | tag `v*` (or manual dispatch) | Builds and pushes the production images | `ghcr.io/<owner>/spmis-{api,web}` |

**Why merging is not releasing.** `main` being green means it *could* ship; a tag means
someone decided it *should*. For a system holding personal data, keeping those separate
is worth the extra step — and it is what makes rollback trivial, because the previous
release is still a tag you can pull. Wanting continuous deploy later is a one-line trigger
change in `release.yml`; the default is deliberate.

**Why CI builds images it throws away.** A green suite does not prove an image builds. A
missing PHP extension or a broken multi-stage copy only appears at build time, and finding
that when you are trying to cut a release means finding it at the worst moment. The build
cache is shared with `release.yml` by scope, so tagging a CI-green commit is mostly a
cache hit.

**PHPStan runs against a baseline.** `api/phpstan-baseline.neon` records 18 pre-existing
findings so CI fails on *new* ones instead of failing on the backlog from day one. Shrink
it deliberately; never regenerate it to silence a fresh error.

**There is no `worker` image.** The worker and scheduler run the `spmis-api` image with a
different command — same code, same dependencies, one artefact to build, scan and roll
back. A third identical image would only be a third thing to keep in step.

### Cutting a release

Images build automatically when you push a version tag:

```bash
git tag v1.0.0
git push origin v1.0.0        # → builds + pushes ghcr.io/<owner>/spmis-{api,web}
```

Each run pushes, per service, three tags: the **git tag** (`v1.0.0`), a **commit
SHA** (`sha-<short>`), and `latest` (on tag builds). `workflow_dispatch` (Actions →
Release images → Run) also builds, tagging the branch + SHA. The workflow uses only
`packages: write` (least privilege) and the built-in `GITHUB_TOKEN`.

Make the packages accessible to the VPS: in GitHub → the org/user **Packages** →
`spmis-api` / `spmis-web` → Package settings, either keep them **private** (the VPS
authenticates with a read-only token, below) or set visibility to public.

---

## 2. First-time VPS bring-up

Prerequisites: Docker Engine + Compose plugin; DNS `A`/`AAAA` record for your domain
→ the VPS; ports 80/443 open; a non-root deploy user in the `docker` group.

### 2.1 Get the deploy files (no build context needed)
```bash
sudo mkdir -p /opt/spmis && sudo chown "$USER" /opt/spmis && cd /opt/spmis
git clone --branch v1.0.0 --depth 1 https://github.com/<owner>/<repo>.git .
```
Only `docker-compose.prod.yml` and `docker/nginx/prod.conf.template` are used from
the checkout; images come from GHCR.

### 2.2 Authenticate the VPS to GHCR (read-only)
Create a **classic PAT with only `read:packages`** (GitHub → Settings → Developer
settings → Tokens). Then:
```bash
echo "$GHCR_READ_TOKEN" | docker login ghcr.io -u <github-username> --password-stdin
```
(Public packages need no login.)

### 2.3 Create the environment file (root-owned, gitignored)
```bash
cp .env.prod.example .env
# Generate the app key + backup key from the pulled image (no local PHP needed):
docker run --rm ghcr.io/<owner>/spmis-api:v1.0.0 php artisan key:generate --show
docker run --rm ghcr.io/<owner>/spmis-api:v1.0.0 php artisan backup:keygen
# Paste APP_KEY and BACKUP_ENCRYPTION_KEY into .env, then fill DB/Redis/RabbitMQ
# passwords, NGINX_SERVER_NAME, mail + S3 backup creds. Set GHCR_OWNER + IMAGE_TAG.
sudo chown root:root .env && sudo chmod 600 .env
```
`.env` is gitignored and never enters an image (it feeds the containers via
`env_file` / compose interpolation only).

### 2.4 TLS certificate (Let's Encrypt)
Obtain the cert into `/etc/letsencrypt` before the first `up` (nginx needs it to
serve 443):
```bash
sudo certbot certonly --standalone -d spmis.example.gov.ng   # port 80 must be free
```
Or drop a provided cert at the `NGINX_SSL_CERTIFICATE*` paths in `.env`. Create the
ACME webroot for future renewals: `mkdir -p /opt/spmis/acme`.

### 2.5 Pull and start
```bash
docker compose -f docker-compose.prod.yml pull
docker compose -f docker-compose.prod.yml up -d
```
The `api` container runs `php artisan migrate --force` automatically on boot
(`RUN_MIGRATIONS=true`); the worker/scheduler do not (no migration races). Watch it:
```bash
docker compose -f docker-compose.prod.yml ps
docker compose -f docker-compose.prod.yml logs -f api
```

### 2.6 Install the operating configuration + the initial administrator

> Run each seeder **by class**. Never `db:seed --force` on its own: `DatabaseSeeder`
> also lists the sample-data seeders, and while each of those refuses to run in
> production, naming the ones you want is what makes that a decision rather than a
> reliance on someone else's guard.

```bash
# One handle for the rest of this section (note the quotes — it is one string).
C="docker compose -f docker-compose.prod.yml exec api php artisan"

# Roles + permissions (FR-UAM-01/05).
$C db:seed --class=RolesAndPermissionsSeeder --force
# Duplicate matching cascade + thresholds (FR-DUP-02/03/08). SEE THE WARNING BELOW.
$C db:seed --class=MatchingConfigSeeder --force
# Double-dipping detection rules (FR-BEN-07).
$C db:seed --class=DoubleDippingRuleSeeder --force
# Referral + grievance SLA windows (FR-REF, FR-GRM-03).
$C db:seed --class=ReferralSlaSeeder --force
$C db:seed --class=GrievanceSlaSeeder --force
```

> **Without `MatchingConfigSeeder` there is no active matching config, and the import
> pipeline SKIPS DUPLICATE SCREENING ENTIRELY** — `MatchingConfigService::activeOrNull()`
> has no lazy default, and `ParseImportBatch` treats "no config" as "do not screen". No
> error is raised and no warning is shown; the registry simply fills with duplicates.
> Verify before the first import:
> ```bash
> $C tinker --execute="echo app(App\Domain\Matching\Services\MatchingConfigService::class)->activeOrNull()?->version ?? 'NO ACTIVE CONFIG';"
> ```
> These four are **configuration**, not sample data. The shipped values are DEFAULTS
> awaiting stakeholder sign-off — see §1.4.

```bash
$C spmis:create-admin admin@spmis.example.gov.ng --name="SP-MIS Administrator"
# Prompts for a strong password (policy-checked, never an argument or env var, so it
# stays out of shell history). MFA enrolment is forced at first login.
```

### 2.7 Verify
```bash
curl -fsS https://spmis.example.gov.ng/api/v1/health | jq .        # "status":"ok"
curl -I  http://spmis.example.gov.ng/                              # 301 → https
docker compose -f docker-compose.prod.yml ps                       # all "healthy"
```
Then open the domain — the SPA loads and you can sign in.

---

## 3. Redeploy (new release)

> **Check §3.1 before deploying the release that introduces LGA/Ward reference data.**
> On an existing database it needs one ordered pre-step, and skipping it stops the `api`
> container from booting.

```bash
cd /opt/spmis
git fetch --tags && git checkout v1.1.0        # refresh compose/nginx config
# edit .env → IMAGE_TAG=v1.1.0
docker compose -f docker-compose.prod.yml pull
docker compose -f docker-compose.prod.yml up -d   # recreates changed services; api re-migrates
docker compose -f docker-compose.prod.yml ps
```
Take a backup first (§5). Migrations are forward-only; a redeploy applies any new
ones automatically.

### 3.1 One-time: LGA/Ward reference data before the activity-locations migration

**Fresh installs are unaffected** — skip to §3.2. This applies only when upgrading a
database that **already has activities**.

Three migrations ship together:

| Migration | What it does |
| --- | --- |
| `2026_08_15_000000_create_administrative_divisions_tables` | creates `lgas` + `wards` (empty) |
| `2026_08_15_100000_create_activity_locations_table` | creates `activity_locations` **and backfills** each activity's old single LGA/Ward into it |
| `2026_08_15_100001_drop_single_location_from_activities` | drops `activities.lga` / `.ward` |

The backfill resolves the old free-text LGA values against `lgas`. If `lgas` is **empty**
it cannot resolve anything — and the very next migration drops the columns, which would
destroy every activity's location permanently. So it **deliberately refuses to run** and
throws:

```
Cannot migrate activity locations: 14 activities have an LGA set,
but the `lgas` lookup table is empty.
```

#### Why this stops the deploy, not just the migration

The `api` container runs `php artisan migrate --force` on boot (`RUN_MIGRATIONS=true`),
and its entrypoint is `set -euo pipefail` with no guard around that command. A refused
migration therefore **exits the entrypoint non-zero and the `api` container fails to
start** — the site goes down until reference data is loaded. This is a hard ordering
dependency, not a warning you can defer.

#### The ordered upgrade

Run the first migration on its own, populate `lgas`, then let the rest proceed.

```bash
cd /opt/spmis
docker compose -f docker-compose.prod.yml exec api php artisan down        # maintenance mode
# BACK UP FIRST — migration 100001 is destructive (§5).

# 1. Create the lookup tables only.
docker compose -f docker-compose.prod.yml exec api \
  php artisan migrate --force \
  --path=database/migrations/2026_08_15_000000_create_administrative_divisions_tables.php

# 2. Populate `lgas` — EITHER (a) or (b) below.

# 3. Apply the remaining migrations (backfill + column drop).
docker compose -f docker-compose.prod.yml exec api php artisan migrate --force

docker compose -f docker-compose.prod.yml exec api php artisan up
```

**(a) With an authoritative dataset — preferred.** Place the maintainer-supplied
CSV/JSON (HDX / GRID3 / State ward register) where the container can read it. The target
directory is **not** in the image, so create it first:

```bash
docker compose -f docker-compose.prod.yml exec -u root api sh -c \
  'mkdir -p storage/app/reference && chown www-data:www-data storage/app/reference'
docker compose -f docker-compose.prod.yml cp ./jigawa-divisions.csv \
  api:/var/www/html/storage/app/reference/jigawa-administrative-divisions.csv
docker compose -f docker-compose.prod.yml exec api php artisan reference:load-divisions
```

`storage/` is a named volume, so the file survives redeploys. Point
`REFERENCE_DIVISIONS_PATH` elsewhere if you keep it somewhere else.

This loads LGAs **and wards**. It refuses a file that is not credibly Jigawa's (unknown
LGAs, or fewer than all 27) — see `api/app/Domain/Reference/README.md`.

**(b) Without a dataset yet — LGAs only.**

```bash
docker compose -f docker-compose.prod.yml exec api php artisan reference:seed-lgas
```

Writes the 27 LGAs from the committed `Lga` enum — the same list FR-REG-04/05 already
validates `beneficiaries.lga` against, so it copies a fact the repo asserts rather than
inventing one. It creates **no wards**; ward names are never generated. Run
`reference:load-divisions` later to add them — it matches on the same `code` and updates
these rows in place.

#### What the backfill does to existing values

- LGA resolves → one `activity_locations` row for that LGA.
- LGA resolves, ward does **not** (e.g. free text like `Ward 8`, or option (b) where no
  wards exist) → kept as a **whole-LGA** row. The activity demonstrably operates in that
  LGA, and that stays true even when the ward string does not resolve.
- LGA does not resolve → **no row**, and the activity's location is not represented.

Every unresolved value is recorded with its raw text in the audit log, so nothing is lost
when the columns drop:

```bash
docker compose -f docker-compose.prod.yml exec api php artisan tinker --execute='
$a = DB::table("audit_log")->where("action","activity.locations.migrated")
     ->latest("created_at")->first();
echo $a->after, "\n";'
```

Expect `{"migrated_rows":N,"unresolved_count":M,"unresolved":[{...,"reason":"unknown_ward_kept_whole_lga"}]}`.
A `reason` of `unknown_lga` means that activity got **no** location row — review those by hand.

#### Verify

```bash
docker compose -f docker-compose.prod.yml exec api php artisan migrate:status | grep -c Pending   # 0
docker compose -f docker-compose.prod.yml exec api php artisan tinker --execute='
echo "lgas=", DB::table("lgas")->count(),
   " wards=", DB::table("wards")->count(),
   " activity_locations=", DB::table("activity_locations")->count(),
   " activities=", DB::table("activities")->count(), "\n";'
curl -fsS https://spmis.example.gov.ng/api/v1/health | jq .
```

`lgas` must be **27**. `activity_locations` should be ≥ the number of activities that had
an LGA, minus any `unknown_lga` above.

#### If it already failed and `api` is down

The refusal happens **before any write**, so the database is untouched and the fix is just
ordering. Run steps 1–3 above against the stopped stack:

```bash
docker compose -f docker-compose.prod.yml up -d postgres redis rabbitmq
docker compose -f docker-compose.prod.yml run --rm --entrypoint sh api -c \
  'php artisan migrate --force --path=database/migrations/2026_08_15_000000_create_administrative_divisions_tables.php \
   && php artisan reference:seed-lgas && php artisan migrate --force'
docker compose -f docker-compose.prod.yml up -d
```

#### Rollback

`2026_08_15_100001`'s `down()` re-creates `activities.lga` / `.ward` and repopulates each
activity from **one** of its locations. A set cannot round-trip into a single field, so
this is **lossy by design**: the first location wins and the rest remain only in
`activity_locations`. If you must return to the previous release with its schema, restore
the pre-deploy backup instead (§5).

### 3.2 Post-deploy: confirm the queue worker is consuming

Do this on **every** deploy. `queue:work` is started with `--max-time=3600`, so it exits 0
every hour **on purpose** (recycling the process avoids memory bloat) and relies entirely
on its `restart` policy to come back. `docker-compose.prod.yml` sets
`restart: *restart` (`unless-stopped`) on both `worker` and `scheduler` — do not remove it.

Check anyway, because the failure is invisible: imports, matching and notifications are all
queued, so a dead worker leaves the UI showing "Processing…" indefinitely with **no error
in any log**. `docker compose ps` also hides exited containers by default, so the stack
looks healthy. (This is exactly how it failed in local dev, where the worker had no
restart policy.)

```bash
docker compose -f docker-compose.prod.yml ps --all | grep -E "worker|scheduler"   # Up, not Exited
docker compose -f docker-compose.prod.yml exec rabbitmq \
  rabbitmqctl list_queues name messages_ready consumers
```

`consumers` must be **≥ 1**. A queue with `messages_ready > 0` and `consumers = 0` is a
dead worker, whatever the container status says.

## 4. Rollback (previous tag)

```bash
# edit .env → IMAGE_TAG=v1.0.0 (the last known-good tag)
docker compose -f docker-compose.prod.yml pull
docker compose -f docker-compose.prod.yml up -d
```
Rollback restores the previous **code**. If the newer release ran a destructive
migration, restore the database from the pre-deploy backup as well (§5 / restore
runbook in SCALE-AND-AVAILABILITY.md). This is why §3 says back up before deploying.

---

## 5. Backups & where they run

The **scheduler** service runs `php artisan schedule:work`, which triggers:
- **daily encrypted, offsite backup** (`backup:run`, 01:00) → the S3 disk configured
  by `BACKUP_DISK`/`AWS_*`, encrypted with `BACKUP_ENCRYPTION_KEY` (DB + documents);
- **weekly restore drill** (`backup:drill`) verifying recoverability within the RTO;
- data-retention, SLA sweeps, dashboard snapshots, and source sync.

Run one on demand / verify:
```bash
docker compose -f docker-compose.prod.yml exec scheduler php artisan backup:run
docker compose -f docker-compose.prod.yml exec scheduler php artisan backup:drill
```
RPO ≤ 24h, RTO ≤ 4h (configurable). Full backup/restore procedure, offsite storage
and RPO/RTO are in [SCALE-AND-AVAILABILITY.md](SCALE-AND-AVAILABILITY.md).

---

## 6. TLS renewal & operations

- **Renew** (webroot; nginx serves `/.well-known/acme-challenge` from `./acme`):
  ```bash
  sudo certbot renew --webroot -w /opt/spmis/acme \
    --deploy-hook "docker compose -f /opt/spmis/docker-compose.prod.yml exec nginx nginx -s reload"
  ```
  Add it to cron/systemd-timer (twice daily is standard).
- **Logs** (structured JSON to stderr → your aggregator): `docker compose -f docker-compose.prod.yml logs -f api worker scheduler`.
- **RabbitMQ UI / psql**: never published — reach them via an SSH tunnel to the VPS
  (`ssh -L 15672:localhost:15672 …` after temporarily exposing, or `docker compose exec`).
- **Metrics**: `GET /api/v1/health/metrics` (auth’d) — backup age vs RPO, snapshot
  freshness, volumes. Point monitoring at `/api/v1/health` (readiness) and alert on
  `status != ok`, backup age > RPO, and critical logs (e.g. `Backup FAILED`).

---

## 7. Security invariants (do not weaken)
- Only nginx is public (80/443); all data services + php-fpm are internal-only.
- No secrets in the repo, images, or logs — everything is in the root-owned `.env`
  (chmod 600) and injected at runtime. Images carry no `.env` and run as non-root.
- `APP_DEBUG=false`, `APP_ENV=production`; `APP_KEY`/`BACKUP_ENCRYPTION_KEY` are
  stable managed secrets (rotating `APP_KEY` orphans encrypted data/backups —
  re-encrypt first, see SECURITY-FINDINGS.md).
- TLS enforced (HTTP→HTTPS 301, HSTS) with the full security-header set at the edge.

---

## 8. Monitoring & alerting

Monitoring lives **outside the application**. SP-MIS deliberately shows no system-health
widgets in its console (CLAUDE.md §8): an operator watching the thing that is down learns
nothing, and a dashboard that renders "all healthy" from inside a broken stack is worse
than no dashboard. These run alongside the app on the VPS, or off it.

Three essentials. Everything past them is optional and can wait.

### 8.1 Uptime & container liveness

**Uptime Kuma** (self-hosted, ~100 MB) or any external checker. Monitor:

| Check | Target | Expect |
|---|---|---|
| Public health | `https://<domain>/healthz` | 200, < 2 s |
| API readiness | `https://<domain>/api/v1/health` | 200 with dependency states |
| TLS expiry | the domain | alert at 21 days |

```bash
# Alongside the stack, on its own port, not published through nginx.
docker run -d --restart unless-stopped \
  -p 127.0.0.1:3001:3001 \
  -v uptime-kuma:/app/data \
  --name uptime-kuma louislam/uptime-kuma:1
```

Bind it to `127.0.0.1` and reach it over an SSH tunnel
(`ssh -L 3001:127.0.0.1:3001 <vps>`). Publishing a monitoring UI on the public interface
adds an unauthenticated-by-default surface next to a system holding personal data.

`/api/v1/health` already reports per-dependency state (database, cache, queue), so a
degraded-but-up stack is distinguishable from a dead one. Alert to whatever the team
actually reads — email, Telegram and Slack are all built in.

### 8.2 Application errors

**Sentry**, or any equivalent. Two DSNs, because the two runtimes fail differently:

```dotenv
# api/.env  (Laravel)
SENTRY_LARAVEL_DSN=https://…
SENTRY_TRACES_SAMPLE_RATE=0.1

# web build-time (Vite)
VITE_SENTRY_DSN=https://…
```

> **Scrub before you ship.** An exception payload can carry request bodies, and a request
> body here can carry a NIN. Enable Sentry's data-scrubbing, set
> `send_default_pii=false`, and confirm on staging that a deliberately-failed import
> reports a stack trace and **no beneficiary data**. This is the same rule the audit log
> already follows (SECURITY.md §3) — an error tracker is not exempt from it.

### 8.3 Backup success — a dead man's switch

The one that matters most, because **a backup that stops running is silent**. Nothing
fails, nothing alerts, and you discover it when you need a restore.

Invert it: the backup pings a service on success, and *that service* alerts when the ping
does not arrive.

```bash
# Healthchecks.io (hosted) or self-hosted. Create a check on a daily schedule with a
# grace period longer than the backup takes, then:
BACKUP_HEARTBEAT_URL=https://hc-ping.com/<uuid>
```

Wire it to the scheduled backup so the ping only fires on success:

```bash
docker compose -f docker-compose.prod.yml exec -T api php artisan backup:run \
  && curl -fsS --retry 3 "$BACKUP_HEARTBEAT_URL" \
  || curl -fsS --retry 3 "$BACKUP_HEARTBEAT_URL/fail"
```

A restore drill is proven in the suite (`BackupDrillTest`), but a green test proves the
CODE restores. Only the heartbeat proves it **ran last night**.

### 8.4 Logs

Every service caps its logs in `docker-compose.prod.yml` (`json-file`, 10 MB × 5). This is
not housekeeping: Docker's default is unbounded, and the first symptom of a full disk is
Postgres refusing writes — a data-loss-shaped failure caused by logging.

```bash
docker compose -f docker-compose.prod.yml logs -f --tail=100 api
docker compose -f docker-compose.prod.yml logs --since 1h nginx
docker compose -f docker-compose.prod.yml exec api tail -f storage/logs/laravel.log
docker system df   # disk used by images, containers, volumes
```

> Laravel's own log is a separate file inside the container and is **not** covered by the
> Docker rotation. Set `LOG_CHANNEL=daily` in production; a single un-rotated channel
> reached 77 MB in development.

### 8.5 What is deliberately not here

Prometheus, Grafana and Loki are the right answer at multi-node scale and the wrong one
for a single VPS: they cost more RAM than the app tier and add a stack to maintain
alongside the one you are trying to keep up. Add them when there is a second node to
compare, not before.

---

## 9. Go-live readiness — NFR → evidence

Every row names the evidence, not an opinion. **Machine** rows are proven by something
that runs; **human** rows cannot be closed by code and are the real gate.

> **Do not load real beneficiary data until every human-owned row is signed off.** Once
> production PII exists, several of these stop being reversible — an unencrypted volume
> cannot be retrospectively encrypted for data already written to it, and a pen test
> against live personal data is a different risk conversation entirely.

### Machine-verifiable

| NFR | Requirement | Evidence | State |
|---|---|---|---|
| NFR-SEC-01 | Encryption at rest for NIN/BVN/TOTP | `SecurityHardeningTest` — ciphertext + keyed-hash lookup | ✅ |
| NFR-SEC-02 | Central MDA scoping, no ad-hoc bypass | `ScopeBypassSurfaceTest` — file allow-list, new bypass fails CI | ✅ |
| NFR-SEC-02 | Deny-by-default RBAC | `SecurityHardeningTest` — role-less user denied everywhere | ✅ |
| NFR-AUD-01 | Tamper-evident audit | `audit:verify-chain` — 2,858 entries verified; forged row detected | ✅ |
| NFR-PRV-01 | Cross-MDA sharing governed | `DataSharingGovernanceTest` — one guard, four bases, denial audited | ✅ |
| NFR-PRV-01 | Anonymisation is complete | `AnonymizationStagingResidueTest` — sweep fails if any table retains the erased NIN | ✅ |
| NFR-PERF-01 | No N+1 on hot paths | `QueryEfficiencyTest`, `HotPathEfficiencyTest` — growth, not ceiling | ✅ |
| NFR-PERF-01 | Duplicate < 5 s, pages < 3 s | `perf:benchmark` — **dev stack only, 832 records** | ⚠️ unproven at volume |
| NFR-AVAIL-01 | Encrypted offsite backup + restore | `BackupDrillTest` — 6 tests incl. tamper detection, restore within RTO | ✅ |
| NFR-AVAIL-01 | Health endpoints | `HealthTest` — readiness reports dependencies; metrics gated, no PII | ✅ |
| NFR-USE-01 | WCAG 2.1 AA | axe-core, 26 page/viewport runs — 0 violations | ✅ |
| NFR-USE-01 | Keyboard + mobile | tab-walk 9 flows; 11 routes × 3 viewports, no horizontal scroll | ✅ |
| NFR-MAINT-01 | `main` is always deployable | `ci.yml` — suite, lint, analysis, image build, pushes nothing | ✅ |
| NFR-MAINT-01 | Release is deliberate | `release.yml` — tag-triggered, GHCR push, rollback by previous tag | ✅ |
| NFR-SCAL-01 | Stateless app tier | sessions/cache in Redis; queue in RabbitMQ; workers scale horizontally | ✅ |

### Deployment gates — verify on the VPS, not in CI

| Check | How to prove it | Owner |
|---|---|---|
| GHCR pull works | `docker compose -f docker-compose.prod.yml pull` after `docker login ghcr.io` (§2.2) | DevOps |
| Only 80/443 public | `ss -tlnp` on the host shows nothing else bound externally | DevOps |
| TLS valid + HSTS | `curl -sI https://<domain> \| grep -i strict-transport` | DevOps |
| HTTP → HTTPS | `curl -sI http://<domain>` returns 301 | DevOps |
| Migrations applied | `php artisan migrate --force` then `migrate:status` | DevOps |
| Queue consuming | §3.2 | DevOps |
| Uptime monitor live | a deliberate `docker stop nginx` raises an alert | DevOps |
| Backup heartbeat live | skip one scheduled run; the dead-man's switch alerts (§8.3) | DevOps |
| Restore rehearsed | restore last night's artefact into a scratch database and diff row counts | DevOps |

### Human-owned — the real gate

| Gate | Owner | Blocks |
|---|---|---|
| **PT-01** external penetration test on staging | Security | Real PII |
| **PT-02** DPO sign-off: consent model, retention schedule, anonymisation lists, export matrix | DPO | Real PII |
| **OPS-01** TLS 1.2+ and certificate management | DevOps | Go-live |
| **OPS-02** encrypted DB volume + encrypted offsite backups | DevOps | Real PII |
| **OPS-03** egress allow-list for sync workers | DevOps | Live connectors |
| **OPS-04** `audit:verify-chain` scheduled + alerting on failure | DevOps | Go-live |
| **OPS-05** per-environment `APP_KEY`; rotation runbook incl. NIN/BVN re-encryption | DevOps | Go-live |
| **OPS-06** `VITE_MAP_TILE_URL` set to an approved origin | DevOps | Working maps |
| **OPS-07** scheduled dependency audit, high/critical release-blocking | DevOps | Ongoing |
| **OPS-08** retention period for RAW IMPORT FILES — no mechanism exists until supplied | DPO | Real PII |
| Retention policies configured | DPO | Real PII |
| Matching thresholds + SLAs confirmed | SP Coordination | Go-live |
| Ward dataset completed (162 of ~287; 6 LGAs have none) | SP Coordination | Ward validation |
| Load test at volume on staging | DevOps | Perf sign-off |

### Known state at the time of writing

- **Boundaries:** 27 LGA outlines ship and load. **Ward boundaries do not** — the GIS ward
  layer falls back to a ranked table, which is a degradation, not a failure.
- **Wards:** 162 of roughly 287. Ward validation is per-LGA, so an LGA with no list accepts
  free text rather than rejecting every ward in it — the gap degrades quietly.
- **Sync:** runs on `MockSyncSource` until real SOCU/government endpoints are supplied.
- **Retention:** disabled with an empty policy list. Nothing is aged out until the DPO acts.

---

