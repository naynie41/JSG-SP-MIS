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
| MDA         | id, name, type, contact                                                        | owns Activities, Beneficiaries, Users |
| Programme   | id, name, objective, type (HH/individual), benefit_category, standard_eligibility | GLOBAL catalog (System-Admin-created, not MDA-owned); has many Activities |
| Activity    | id, programme_id, owner_mda_id, target, location (LGA/Ward), schedule, budget, funding_source, period, eligibility | MDA-owned; belongs to a catalog Programme; has many Benefits |
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

### 12.4 Programme catalog & activity ownership (revises FR-PRG-01/02)

- **Programme = global catalog**, created only by the System Administrator (optionally SP Coordination).
  Not MDA-owned; globally readable; holds type-level attributes (name, objective, type, benefit
  category, standard eligibility). MDAs cannot create/edit programmes.
- **Activity = MDA-owned** (`owner_mda_id`, `ScopedToMda`), created by MDA officers/admins by selecting
  a catalog programme, then supplying location/schedule/budget/funding/period/targets. Budget + funding
  live on the Activity.
- One programme → many MDAs → separate activities. Interventions reference (beneficiary, programme,
  activity, delivering MDA). Beneficiary ownership is unchanged.