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

## 1. CI: build & push images (GitHub)

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

### 2.6 Seed roles + the initial administrator
```bash
docker compose -f docker-compose.prod.yml exec api php artisan db:seed --class=RolesAndPermissionsSeeder --force
docker compose -f docker-compose.prod.yml exec api php artisan spmis:create-admin admin@spmis.example.gov.ng --name="SP-MIS Administrator"
# Prompts for a strong password (policy-checked). MFA enrolment is forced at first login.
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
