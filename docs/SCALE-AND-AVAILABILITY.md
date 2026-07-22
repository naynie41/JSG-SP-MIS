# SCALE-AND-AVAILABILITY.md — Scale-readiness & Availability (NFR-SCAL-01, NFR-AVAIL-01)

How SP-MIS scales horizontally and how it survives failure: statelessness, the
single-VPS → multi-node growth path, encrypted offsite backups, and a tested restore
drill with defined RPO/RTO. Companion to [PERFORMANCE.md](PERFORMANCE.md).

---

## 1. Scale-readiness (NFR-SCAL-01)

### Stateless application tier
The API holds **no per-node state**, so any node can serve any request and nodes can be
added/removed freely behind the load balancer:

- **Auth** is token-based (Sanctum) — no server session affinity.
- **Sessions & cache** live in **Redis** (`SESSION_DRIVER=redis`, `CACHE_STORE=redis`) —
  shared across nodes.
- **Queue** is **RabbitMQ** (`QUEUE_CONNECTION=rabbitmq`) — a durable broker, not an
  in-process queue.
- **Uploaded documents** must be on a **shared object store** in multi-node deployments
  (point the `local`/documents disk at S3-compatible storage) so every node reads the
  same files. This is the one piece of local state to externalise before scaling out.

The readiness probe (`GET /api/v1/health`) reports this posture in `runtime.stateless`
so an orchestrator can refuse to scale a mis-configured node.

### Horizontal scaling
- **Web tier**: run N identical API containers behind the load balancer; scale on CPU/RPS.
- **Workers**: `php artisan queue:work rabbitmq` scales **horizontally and independently**
  — add worker containers to drain import/matching/sync/report/notification queues faster.
  Scheduled jobs are `withoutOverlapping` / `ShouldBeUnique`, so multiple schedulers/workers
  never double-process (backup, retention, SLA sweeps, sync).

### Growth path: single VPS → split DB → multi-node
1. **Single VPS (pilot).** All services via `docker compose` on one host. Vertical scale first.
2. **Split the database & broker off.** Move Postgres (+ PostGIS), Redis and RabbitMQ to
   their own managed/dedicated hosts. App + workers stay on the VPS. Only env endpoints change.
3. **Multi-node app tier.** Put ≥2 API nodes behind a load balancer; move documents to the
   shared object store (above); run a dedicated worker pool. Redis/RabbitMQ/DB are already external.
4. **Scale the data tier.** Postgres read replicas for reporting/read-heavy scopes; connection
   pooling (PgBouncer); partition the append-only `audit_log` and `benefits` ledger by time as
   they grow. Dashboards already read snapshots, insulating the request path from ledger size.

No code changes are required for steps 1–3 — they are configuration (env + infra). Step 4
is incremental and localised (reporting reads, migrations).

---

## 2. Availability — backups & disaster recovery (NFR-AVAIL-01)

### What runs
- **Daily encrypted backup** (`backup:run`, scheduled 01:00): packages the database
  (pg_dump in prod; the DB file in dev/CI) **and** the beneficiary-documents disk into one
  ZIP, encrypts it (AES-256-CBC + HMAC-SHA256, encrypt-then-MAC), and ships it to the
  **offsite** disk (`BACKUP_DISK`, an S3-compatible store in a different failure domain).
  Artifacts past `BACKUP_RETENTION_DAYS` are pruned. Every run is audited (`backup.created`);
  a failure logs at **critical** (the alerting hook) and exits non-zero.
- **Weekly restore drill** (`backup:drill`, scheduled Sun 03:00): backs up, restores into a
  throwaway database, verifies row counts, and checks the cycle finished within the RTO.
  A failing drill exits non-zero for the monitor.

A backup is **never written unencrypted** — the service refuses to run without
`BACKUP_ENCRYPTION_KEY`. Generate one with `php artisan backup:keygen` and store it in the
secret manager, **separate** from the backup destination's credentials.

### RPO / RTO
| Objective | Target | Basis |
|-----------|--------|-------|
| **RPO** (max data loss) | **≤ 24 h** (`BACKUP_RPO_MINUTES=1440`) | daily backup cadence |
| **RTO** (max time to restore) | **≤ 4 h** (`BACKUP_RTO_MINUTES=240`) | restore-drill budget |

Tighten RPO by scheduling `backup:run` more often (e.g. hourly) and/or enabling Postgres
WAL archiving / PITR for near-zero RPO. The drill asserts the RTO on every run.

### Restore runbook
1. Identify the artifact: `Storage::disk(config('backup.disk'))` under `BACKUP_PATH`
   (newest `spmis-backup-*.zip.enc`).
2. Ensure `BACKUP_ENCRYPTION_KEY` (the key in force when the artifact was written) is set.
3. Dry-run confidence: `php artisan backup:drill` (restores into a scratch DB, verifies).
4. Real restore (destructive): `php artisan backup:restore <path> --connection=pgsql --documents-disk=local`.
   Prompts unless `--force`.
5. Post-restore: run `php artisan migrate` (if the artifact predates the current schema),
   re-verify with `GET /api/v1/health`, then `GET /api/v1/health/metrics` for volumes.

### Restore drill — proven, not assumed
`tests/Feature/Ops/BackupDrillTest.php` runs the full cycle in CI: it asserts the artifact
is **ciphertext** (a known plaintext marker is absent), that a **tampered/wrongly-keyed**
artifact fails the integrity check on restore, that documents are included and restorable,
and that the drill restores + verifies row counts **within the RTO**.

---

## 3. Health, logging & monitoring (NFR-AVAIL-01)

- **Liveness**: `GET /up` (framework) — process is up.
- **Readiness**: `GET /api/v1/health` — 200 only when the DB (+ PostGIS in prod) and cache
  are usable; reports the statelessness posture. Point the load balancer / k8s readiness
  probe here.
- **Metrics**: `GET /api/v1/health/metrics` (permission-gated, non-PII) — last successful
  backup + age vs RPO, dashboard-snapshot freshness, coarse table volumes. Scrape into the
  monitoring system and alert on: backup age > RPO, snapshot staleness, health != ok.
- **Structured logging**: the `json` log channel (config/logging.php) emits one JSON object
  per line enriched with the **correlation id** + actor (`CorrelationIdProcessor`), for a
  log aggregator. Add `json` to `LOG_STACK` in non-local envs. **Critical**-level records
  (e.g. `Backup FAILED`) are the alerting signal — route them to Slack/email/PagerDuty via
  the `slack` channel or the aggregator.
- **Alerting hooks**: backup/drill failures (critical log + non-zero exit + `backup.failed`
  audit), the existing SLA/escalation sweeps, and `audit:verify-chain` (schedule it and
  alert on failure — the audit-integrity signal from the security hardening pass).

---

## 4. Operational checklist before go-live
- [ ] `BACKUP_ENCRYPTION_KEY` set (secret manager) and `BACKUP_DISK` pointed at real offsite storage.
- [ ] First `backup:run` succeeds; first `backup:drill` passes within RTO.
- [ ] Documents disk externalised to shared object storage if running >1 app node.
- [ ] `SESSION_DRIVER=redis`, `CACHE_STORE=redis`, `QUEUE_CONNECTION=rabbitmq` in every env.
- [ ] Load-balancer readiness probe → `/api/v1/health`; monitoring scraping `/health/metrics`.
- [ ] `json` log channel shipping to the aggregator; critical logs routed to on-call.
- [ ] Scheduler (`php artisan schedule:work`/cron) and ≥1 `queue:work` running and supervised.
- [ ] Staging load test recorded in PERFORMANCE.md §Results and meets (or documents variance from) targets.
