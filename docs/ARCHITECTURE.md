# ARCHITECTURE.md — SP-MIS System Design

> The mental model for the system. Read alongside `docs/Jigawa_SP-MIS_PRD.pdf` (authoritative)
> and `CLAUDE.md`. Update this file when the architecture genuinely changes.

---

## 1. System context

SP-MIS is a single shared platform used by many MDAs, partners, and executives. Each MDA keeps
ownership of the records it originates; the platform coordinates across them. The system
**records** social protection activity — it is **not** a payment engine and **not** an identity
system (it consumes NIN/BVN for matching only).

Primary actors (PRD §4): Executive Users, SP Coordination Unit, MDA Users, Development Partners,
System Administrators. Access is governed by RBAC + MDA scoping.

---

## 2. The three core ideas (must shape every design)

1. **Hybrid registry** — ingestion from SOCU, Kobo/ODK, Excel/CSV, REST API, and existing
   government systems. Every beneficiary carries provenance: `registration_source`, `owner_mda`,
   `registration_date`, `import_batch`, `original_record_id`.
2. **Ownership** — first MDA to register owns the core profile; others *request to serve*.
   Ownership transfer needs Owner-MDA approval and is logged.
3. **Duplicate verification** — configurable deterministic + fuzzy matching runs **before save**.

---

## 3. Logical architecture

```
            ┌──────────────────────────────────────────────────────┐
            │                React + TypeScript SPA                  │
            │  (dashboards, registry, referrals, admin, GIS maps)    │
            └───────────────▲──────────────────────────┬────────────┘
                            │ HTTPS / REST (JSON)       │
            ┌───────────────┴──────────────────────────▼────────────┐
            │                 Laravel 12 REST API                    │
            │  Auth (Sanctum + MFA) · RBAC · MDA scoping · Policies  │
            │  Domain services · Validation · Audit logging          │
            └───┬───────────────┬───────────────┬───────────────┬────┘
                │               │               │               │
        ┌───────▼──────┐ ┌──────▼──────┐ ┌──────▼──────┐ ┌──────▼───────┐
        │ PostgreSQL   │ │   Redis     │ │  RabbitMQ   │ │ Queue Worker │
        │ + PostGIS    │ │ cache/sess. │ │   broker    │ │  (Laravel)   │
        └──────────────┘ └─────────────┘ └─────────────┘ └──────────────┘
```

- The **API** is the only writer to the database and the security boundary.
- **Heavy/slow work runs on queue workers**, never in the request cycle: bulk imports,
  duplicate matching, report generation, notifications, and synchronisation.
- **PostGIS** backs LGA/Ward mapping now and heat maps later.

---

## 4. Container topology (local dev — docker-compose)

| Service     | Image / base            | Purpose                                   |
|-------------|-------------------------|-------------------------------------------|
| `api`       | PHP 8.3-fpm + nginx     | Laravel REST API                          |
| `web`       | Node (Vite dev server)  | React + TS frontend                       |
| `postgres`  | postgis/postgis:16      | Database with PostGIS enabled             |
| `redis`     | redis:7                 | Cache, sessions, locks                    |
| `rabbitmq`  | rabbitmq:3-management   | Message broker (+ management UI in dev)   |
| `worker`    | same as `api`           | Runs `artisan queue:work` against RabbitMQ|

`docker compose up` must bring the whole stack up from a fresh clone.

---

## 5. Backend structure (Laravel)

Organise by domain/module, not just by Laravel default folders. Suggested shape:

```
api/app/
├── Domain/
│   ├── Access/          # users, roles, permissions, MDAs, auth, MFA
│   ├── Registry/        # beneficiaries, households, import, provenance (phase 2)
│   ├── Matching/        # duplicate verification engine (phase 3)
│   ├── Programmes/      # programmes, activities, enrollment, benefits (phase 4)
│   ├── Referrals/       # referrals & linkage (phase 5)
│   ├── Grievances/      # GRM (phase 5)
│   ├── Reporting/       # dashboards, exports, GIS (phase 6)
│   └── Audit/           # audit log (phase 1, used everywhere)
├── Http/
│   ├── Controllers/Api/V1/
│   ├── Requests/        # Form Requests (validation)
│   └── Middleware/      # auth, MDA scoping, etc.
├── Policies/            # authorization policies
└── Support/             # shared helpers, response envelopes
```

Within a domain prefer: **Controller → Form Request → Service → Model**, with Policies for
authorization. Keep controllers thin.

---

## 6. Frontend structure (React + TS)

```
web/src/
├── app/            # routing, providers, layout shell
├── features/       # one folder per module (access, registry, referrals, ...)
│   └── <feature>/  # components, hooks, api calls, types for that feature
├── components/     # shared UI primitives (design system)
├── lib/            # api client, auth, query setup, utils
└── types/          # shared TypeScript types mirroring API payloads
```

State/data: use a typed API client + a server-state library (e.g. TanStack Query). Keep API
response **types** in sync with the backend payloads.

---

## 7. Data model (PRD §9 — conceptual)

Core entities and key relationships (detailed schema is designed per phase):

| Entity      | Key attributes                                                                 | Relationships |
|-------------|--------------------------------------------------------------------------------|---------------|
| Beneficiary | id, NIN, BVN, name, DOB, gender, phone, address, LGA/Ward, owner_mda, source, registration_date, status | belongs to Household (opt), Owner MDA; has many Benefits, Referrals |
| Household   | id, head, members, address, LGA/Ward                                           | has many Beneficiaries |
| MDA / implementing agency | id, name, **type (ministry\|department\|agency\|partner)**, **funder_user_id (nullable)**, contact | owns Activities, Beneficiaries, Users. `partner` = a development partner that implements; it owns records through the same column, so scoping/dedup/ledger are unchanged. `funder_user_id` links it to the read-only funder account it funds through (reporting only) — see §12.6 |
| Programme   | id, name, objective, type (HH/individual), benefit_category, standard_eligibility, **owner_mda_id (null = central catalog)**, **approval status** | Either a central catalog entry (System-Admin-created, globally readable, MDA-owned by nobody) **or** an MDA-proposed programme that stays MDA-scoped and needs approval before use; has many Activities |
| Activity    | id, programme_id, owner_mda_id, funding_partner_id (nullable), involves_beneficiaries, target_beneficiaries, target, location (LGA/Ward), schedule, budget, funding_source, period, eligibility | MDA-owned; belongs to a catalog Programme; funding_partner for reporting attribution only (not data access); has many Benefits |
| Benefit     | id, beneficiary_id, programme_id, activity_id, mda_id, type, quantity, value, funding_source, delivery_date, status, verification | the benefit ledger |
| Referral    | id, beneficiary_id, from_mda, to_mda, need, status, outcome, timestamps        | across MDAs (outbound) |
| Service Request | id, beneficiary_id, requesting_mda_id, owner_mda_id, activity_id, status (pending/accepted/declined), decided_by, decision_reason, timestamps | request-to-serve (inbound); state machine |
| Grievance   | id, beneficiary_id, category, channel, status, resolution, timestamps          | |
| User        | id, name, mda_id, role, permissions, status                                    | belongs to MDA |
| Audit Log   | id, user_id, action, entity, before/after, timestamp                           | immutable |

**Conventions:** UUID primary keys for externally-referenced entities; snake_case columns;
timestamps + soft deletes where appropriate (except the audit log, which is append-only).
Foreign keys and indexes on every relationship and on matching fields (NIN, BVN, phone).

---

## 8. API design (summary — full rules in CONVENTIONS.md)

- Versioned under `/api/v1`. RESTful, resource-oriented.
- JSON request/response in **snake_case**; standard success and error envelopes.
- Pagination, filtering, and sorting are standardised and consistent across endpoints.
- Auth via Sanctum bearer tokens; every endpoint declares its required permission.

---

## 9. Asynchronous processing

Use the queue (RabbitMQ + Laravel workers) for anything slow or bursty:

- Bulk Excel/CSV and Kobo/ODK imports (with batch tracking + row-level errors).
- Duplicate matching at scale.
- Report generation and scheduled reports.
- Notifications (in-app, email; SMS/WhatsApp later).
- Synchronisation with SOCU and external systems.

Jobs must be idempotent and safe to retry; surface progress/results to the user.

---

## 10. Environments & scaling

- **MVP / pilot:** single VPS running all containers (see the VPS spec I provided).
- **Scale path (toward NFR-SCAL-01 / NFR-AVAIL-01):** split PostgreSQL onto its own host
  (or managed Postgres), run 2+ API/worker nodes behind a load balancer, move document storage
  to S3-compatible object storage. A single VPS cannot meet 99.5% availability — plan for this.

### Deployment pipeline (CI/CD — GHCR-based; built in Phase 7.8)

```
VS Code → GitHub → GitHub Actions → GHCR → VPS (pull) → monitoring + backup
```
- **On push/PR to `main` (ci.yml):** run tests + lint + static analysis + a Docker build (build only) —
  pushes nothing; keeps main always deployable.
- **On a version tag `v*` (release.yml):** build slim, non-root, multi-stage images (api/web/worker),
  tag by git-tag + SHA, push to `ghcr.io/<owner>/spmis-<service>`. The tag is the release artifact.
  Deliberate release model (not deploy-on-every-merge) → gives a clean rollback (pull previous tag).
- **VPS pulls only** — `docker-compose.prod.yml` has no `build:` contexts; the VPS runs
  `docker login ghcr.io` once (read-only token) then `docker compose pull && up -d`. Only nginx
  publishes 80/443; data services are internal.
- **Monitoring lives at the ops layer, outside the app** (uptime/health + app-error + backup-success
  alerting) — the application never renders system-health widgets (that boundary is DevOps, not the app).
  Backups are automated, encrypted, offsite, with a tested restore drill (7.6).

---

## 11. Non-functional targets (PRD §11 — keep visible)

Encryption in transit + at rest · MFA for privileged roles · NDPA/NDPR compliance · page loads
< 3s and duplicate check < 5s under normal load (proposed) · millions of records / ≥500 concurrent
users via horizontal scaling (proposed) · 99.5% availability with backups + RPO/RTO (proposed) ·
responsive & accessible UI · tamper-evident audit · API-first · containerised.

---

## 12. PRD v1.2 additions

### 12.1 Service Request (Request-to-Serve) — new domain concept

- New entity `service_requests`: beneficiary_id, requesting_mda_id, owner_mda_id, activity_id,
  status (pending/accepted/declined), decided_by, decision_reason, timestamps. Models an auditable
  state machine (pending → accepted | declined).
- The read-access grant on acceptance is represented explicitly (e.g. a `beneficiary_service_grants`
  row or equivalent policy record) so authorization is queryable and revocable, not implicit.
- Runs within the existing topology: approvals are synchronous state transitions on the `api`
  service; notifications on accept/decline go through the existing queue/worker path.
- Distinct from Referral (outbound, FR-REF). Do not overload the referral flow.

### 12.2 Duplicate-matching cascade

- The default matching config encodes the ordered cascade (exact NIN → exact BVN → fuzzy
  name/phone) with stop-on-first-exact and skip/fall-through when an identifier is absent. It stays
  config/DB-driven and admin-editable; the engine and worker execution from Phase 3 are unchanged —
  only the seeded default and the stop/fall-through semantics.

### 12.3 Activity-first coupling

- The import pipeline now takes an `activity_id` as required context. An import batch references the
  activity; resolved rows produce interventions (benefit ledger) attributed to that activity and the
  delivering MDA. Beneficiary ownership is still set by first import (owner_mda_id).

### 12.4 Programme ownership & activity ownership (revises FR-PRG-01/02/06)

- **Central catalog** — created only by the System Administrator (optionally SP Coordination). Owned by
  nobody, globally readable, holds type-level attributes (name, objective, type, benefit category,
  standard eligibility). An MDA can never create or edit a catalog entry.
- **MDA-proposed programme** (2026-09-17, PRD v1.9 / FR-PRG-09) — an MDA may propose its own programme.
  It is **MDA-scoped** (`owner_mda_id`, `ScopedToMda`) and unusable until the System Administrator
  approves it. **Approval clears it for use; it does not promote it into the catalog** and does not
  widen its visibility. An MDA never creates a *live* programme directly and never sees another MDA's.
- **Activity = MDA-owned** (`owner_mda_id`, `ScopedToMda`): select a programme, then supply
  location/schedule/budget/funding/period/targets. **Budget and funding live on the Activity**, never
  the programme — that did not change in v1.9.
- One programme → many MDAs → separate activities. Interventions reference (beneficiary, programme,
  activity, delivering agency). Beneficiary ownership is unchanged.
- **Archive, never hard-delete** (FR-PRG-10) for anything carrying history; archiving a programme with
  active activities is blocked.

### 12.5 Reporting suites & admin console (read/compose layers)

These are read/composition layers over the existing data — they add no new write path or ownership:

- **Phase 6E — Executive Reporting Suite** and **Phase 6P — Funding Partner Reporting Suite** are
  read-only, aggregate-only views over the Phase 6 aggregation layer, scoped by role (Executive =
  statewide; Partner = funded programmes only). Headline = net unique beneficiaries, never gross
  registrations; coverage is absolute counts (no denominator loaded). See their prompt files.
- **Partner funding attribution:** `activity.funding_partner_id` resolves an activity to a Development
  Partner for 6P scoping. It attaches to the activity (never the programme) and grants reporting
  visibility only — never beneficiary-data access.
- **Two agency counts, not one** (FR-RPT-11). Reporting distinguishes agencies that **implement** (own
  activities in scope) from those that **deliver** (have actually paid benefits out under the caller's
  scope). The second is a subset of the first; they are named separately because conflating them
  overstates delivery. Every agency row carries a `kind` so a partner is never reported as government.
- **No sync health on the partner view.** Connectors belong to the implementing agencies and are
  operated by them, so the partner coordination tab neither shows nor computes them. The MDA and
  state-wide coordination views keep their own data-sharing panel — there it is the reader's own
  plumbing.
- **Phase Admin — System Administrator Console** is a governance/config/oversight surface that
  **composes existing modules** (users/audit Ph1, registry Ph2, matching Ph3, catalog Ph4, reports Ph6,
  sync Ph7) into a System-Administrator-scoped console. It reimplements nothing, holds no second data
  path, and excludes infrastructure/system-health monitoring (DevOps) and programme-delivery operations
  (MDAs / SP Coordination). Integrations composes the Phase 7 sync engine and degrades to a pending state
  until Phase 7 exists.
- Dashboard UI for all three follows `DESIGN-SYSTEM.md` §5.11–§5.12 (executive-grade craft; tokens win
  over the skill).

### 12.6 Partner organisations that implement (2026-09-20, PRD v2.0 / §6.6)

A development partner may run its own programmes as well as fund someone else's. The design goal was
to add **no second delivery mechanism** — and the way that is achieved is worth stating, because it is
why so little code changed:

- **The delivery organisation is an `mdas` row of type `partner`.** It owns programmes, activities and
  beneficiaries through the same `owner_mda_id`, so `MdaScope`, the duplicate cascade, request-to-serve,
  the import pipeline and the benefit ledger all apply with **no special case**. A partner's
  beneficiaries join state-wide duplicate screening; its data is visible to government oversight.
- **Its staff hold the ordinary MDA Admin role.** No new role, no new permission.
- **The funder account is a different account.** Read-only, funded-scope, no PII — the account
  `activities.funding_partner_id` points at. `mdas.funder_user_id` links the two for reporting.
  **They are never merged**: the funder role's no-PII guarantee depends on the separation, so a single
  login that both owns registry records and reports as a funder would silently void it.
- **The one behavioural rule:** government does not fund a partner organisation's own activity
  (FR-PRG-11) — `funding_type` may not be `government`, nor may the government co-funding flag be set.
- **Naming.** `MdaType::isGovernment()` is the single predicate; "MDA" still means government and
  **implementing agency** is the umbrella. On the frontend `workspaceIdentity()` derives the workspace
  name, role label and the noun for "your …" from the signed-in user's organisation type — display
  only, with the role key and permissions untouched.

> **Scoping trap worth remembering.** `Mda` is itself `ScopedToMda`. A partner user holds no `mda_id`,
> so any agency-name lookup inside a partner request must use `withoutGlobalScope(MdaScope::class)` or
> it silently returns nothing. This hid for a long time because dashboard snapshots are built from the
> console, where no user is authenticated and the scope no-ops — only a live in-request compute showed
> the blanks. Tests for it must both authenticate **and** drop the snapshot, or they pass regardless.