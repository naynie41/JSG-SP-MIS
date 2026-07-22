# PERFORMANCE.md — Performance & Scale Hardening (NFR-PERF-01, NFR-SCAL-01)

How SP-MIS meets its latency targets, how to measure them, and the standing evidence
that the hot paths stay efficient as data grows. Companion to
[SCALE-AND-AVAILABILITY.md](SCALE-AND-AVAILABILITY.md).

## 1. Targets (NFR-PERF-01)

| Path | Target | Notes |
|------|--------|-------|
| Duplicate check (pre-save) | **< 5 s** | deterministic + fuzzy cascade, under normal load |
| Standard pages (list/detail/dashboard) | **< 3 s** | 95th percentile under normal load |
| Write-heavy ops (bulk import, sync) | queued | run off the request path (RabbitMQ) — never block a page |

## 2. How to measure

Two complementary tools, both committed:

- **Query/engine micro-benchmark** — `php artisan perf:benchmark [--seed=N]`. Times the
  duplicate check, beneficiary list, benefit aggregate, and dashboard read directly
  against the current DB (no network), printing each vs its target. `--seed=N` bulk-
  inserts synthetic beneficiaries first (staging only) for representative volume.
- **HTTP load test** — `docs/perf/load-test.js` (k6). Drives the real endpoints
  concurrently and asserts the targets as thresholds (`p(95)<5000` / `p(95)<3000`).
  Run on staging with synthetic/de-identified data:
  ```
  BASE_URL=https://staging/api/v1 TOKEN=<token> k6 run --vus 50 --duration 3m docs/perf/load-test.js
  ```

### Results (record each staging run here)

| Date | Dataset size | VUs | Duplicate p95 | Page p95 | Pass? | Notes |
|------|--------------|-----|---------------|----------|-------|-------|
| _pending staging run_ | | | | | | Capture with the k6 harness above. |

> The load-test numbers must be captured on staging (a running Postgres/Redis/RabbitMQ
> stack) — they cannot be produced from the unit-test sqlite harness. The row above is
> the template; fill it per release. Any variance from the targets is recorded in the
> Notes column with the remediation.

## 3. Why the hot paths meet the targets (design evidence)

- **Duplicate check is bounded, not a scan.** The deterministic finder queries only the
  keyed NIN/BVN hash columns (partial-unique indexes); the fuzzy path gathers candidates
  by blocking key (NIN/BVN hash, phone, `phonetic(last_name)|dob_year`) and **hard-caps
  the candidate set at `CandidateGatherer::MAX_CANDIDATES = 200`**, so scoring cost is
  constant regardless of registry size. No full-table scan is ever issued (the query is
  `null` when no blocking key exists).
- **Dashboards read a snapshot, never the raw ledger.** `DashboardSnapshotService`
  computes per-scope metrics off the request path (scheduler, every 15 min) into one
  indexed `dashboard_snapshots` row; the request reads a single row. Raw
  registry/ledger aggregation never happens synchronously in a page load.
- **Lists are index-backed and projection-only.** `BeneficiaryResource` / `BenefitResource`
  emit scalar columns plus `whenLoaded` relations — no relation is dereferenced per row,
  so a page is O(1) queries, not O(rows). See §4.
- **Write-heavy work is queued.** Bulk import, matching, sync, notifications and reports
  run on RabbitMQ workers; the request returns immediately with a run id.

## 4. No N+1s — enforced by a regression test

`tests/Feature/Performance/QueryEfficiencyTest.php` asserts the beneficiary list and the
benefit ledger each issue a **bounded** number of queries for a 25-row page (≤ 15, i.e.
no per-row query). A future eager-load regression fails this test. Audit finding: the
hot resources are scalar/`whenLoaded` only; the two direct-relation resources
(`UserResource`, `BeneficiaryRevealResource`) are eager-loaded by their controllers —
**no critical N+1s remain**.

## 5. Indexes

Point-lookup indexes were added per table in Phases 2–4. The Phase 7 hardening pass adds
the composite indexes the request path's ORDER BY + multi-column filters need
(`2026_07_22_110000_add_performance_indexes`):

| Table | Index | Serves |
|-------|-------|--------|
| beneficiaries | `(owner_mda_id, registration_date)` | owner-scoped list, newest first |
| benefits | `(mda_id, status)`, `(mda_id, delivery_date)` | scoped ledger filter/sort |
| enrollments | `(beneficiary_id, status)` | graduation/exit + benefit-recording lookups |
| beneficiary_consents | `(beneficiary_id, purpose, created_at)` | consent-gate latest lookup |

Their presence is asserted by `QueryEfficiencyTest::test_hot_path_indexes_exist`.

## 6. Caching & tuning knobs

- Cache + sessions in **Redis** (shared, so the app stays stateless — NFR-SCAL-01).
- Dashboard snapshot cadence: `RefreshDashboardSnapshots` (15 min) — tune per freshness need.
- Rate limits protect the heaviest egress/ingest paths (exports, imports) — see SECURITY.md.
- In production run `php artisan config:cache route:cache event:cache` on deploy.
