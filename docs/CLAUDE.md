# CLAUDE.md — Operating Manual for SP-MIS

> This file is read at the start of every Claude Code session. It is the source of
> truth for **how** we work on this project. Read it fully before doing anything.
> The source of truth for **what** we are building is `docs/Jigawa_SP-MIS_PRD.pdf`.

---

## 1. What this project is

The **State Social Protection Management Information System (SP-MIS)** is a centralized
platform for Jigawa State that lets multiple government agencies (MDAs) coordinate,
register, and track social protection beneficiaries and the benefits they receive —
without duplicating registrations and without any agency losing ownership of its records.

Three concepts sit at the heart of the system and must be respected in every design decision:

1. **Hybrid beneficiary registry** — data enters from many sources (SOCU, Kobo Collect,
   ODK, Excel/CSV, REST API, existing government systems); every record carries its provenance.
2. **Beneficiary ownership model** — the first MDA to register a beneficiary owns the core
   profile; other MDAs can *request to serve* without taking ownership.
3. **Duplicate verification** — a configurable match check runs **before** any new
   beneficiary is saved.

This is a **government system holding citizens' personal data (PII)**. Security and data
protection are not optional features — see `SECURITY.md`.

---

## 2. Golden rules (read these every time)

1. **Read the PRD first.** `docs/Jigawa_SP-MIS_PRD.pdf` is authoritative. If code and PRD
   conflict, the PRD wins unless I explicitly say otherwise.
2. **Stay in the current phase.** We build in 7 phases (Section 5). Do **not** build features
   that belong to a later phase, even if convenient. If a later-phase concern affects a
   design decision now, leave a clear `// TODO(phase-N):` note and move on.
3. **Plan before you build.** For any non-trivial task, propose a short plan (files, approach,
   trade-offs) and wait for my approval before writing feature code.
4. **Trace to requirements.** Reference PRD requirement IDs (e.g. `FR-UAM-01`, `NFR-SEC-01`)
   in commit messages and PR descriptions so every line of code maps back to a requirement.
5. **Security by default.** Follow `SECURITY.md`. Never weaken auth, validation, or audit
   logging to make something easier. Never log PII or secrets.
6. **Conventions are mandatory.** Follow `docs/CONVENTIONS.md` exactly (API shape, naming,
   error format, tests, commits).
7. **Tests are part of "done."** No feature is complete without tests and a green test run.
8. **Never invent secrets or credentials.** Use `.env`; if a real credential or external
   access is needed (e.g. NIN/NIMC, live SOCU), stop and tell me — I will provide it.
9. **Ask, don't assume.** If a requirement is ambiguous, ask one clear question rather than
   guessing. Surface decisions that belong to stakeholders (matching thresholds, SLAs,
   eligibility, consent) instead of hard-coding a guess.
10. **Small, reviewable steps.** Work in logical commits, explain each briefly, and keep
    each change easy to review and run.
11. **UI follows the design system.** Every screen derives its colors, type, spacing, and
    components from `docs/DESIGN-SYSTEM.md`, and you load the `frontend-design` skill before
    building UI. Never hand-roll a component that already exists there — extend the shared one.

---

## 3. Tech stack (non-negotiable — from PRD §12)

| Layer            | Technology               | Notes                                            |
|------------------|--------------------------|--------------------------------------------------|
| Backend / API    | Laravel 12 (PHP 8.3+)    | REST API only. No Blade UI for app screens.      |
| Frontend         | React + TypeScript       | Single-page app. Vite.                            |
| Database         | PostgreSQL 16 + PostGIS  | PostGIS extension enabled from day one.           |
| Cache / sessions | Redis                    |                                                  |
| Queue / workers  | RabbitMQ                 | Bulk import, matching, notifications, sync.       |
| Deployment       | Docker + docker-compose  | `docker compose up` must work from a fresh clone. |

Do not introduce additional frameworks, languages, or heavy dependencies without asking.
Prefer the framework's built-in way of doing things over third-party packages.

---

## 4. Repository layout

```
/
├── CLAUDE.md                  # this file
├── SECURITY.md                # security & data-protection requirements
├── README.md                  # how to set up and run (you generate this)
├── docker-compose.yml         # full local dev stack
├── .env.example               # documented, no real secrets
├── api/                       # Laravel 12 backend
├── web/                       # React + TypeScript frontend
└── docs/
    ├── Jigawa_SP-MIS_PRD.pdf   # the spec (authoritative)
    ├── ARCHITECTURE.md         # system design & data model
    ├── CONVENTIONS.md          # coding standards & API conventions
    ├── DESIGN-SYSTEM.md         # UI reference: tokens & components (all UI follows this)
    └── PHASE-1-BUILD-PROMPTS.md # the Phase 1 task prompts
```

---

## 5. Build phases

We build from scratch in **7 phases**. Each phase ends in something runnable and testable.
**The current phase is whichever phase prompt I give you.** Never build ahead of it; do not start a
later phase until I confirm the current one is done. (Phase 1 = Foundation; Phase 2 = Registry;
Phase 3 = Duplicate Verification; and so on per the list below.)

1. **Foundation & Access Control** — repo, Docker dev env, DB+PostGIS, auth, MFA, RBAC,
   MDA scoping, audit-log foundation, user/MDA management, frontend shell. *(current)*
2. **Beneficiary Registry & Ownership** — beneficiary/household model, manual + Excel/CSV
   import, provenance, ownership rules.
3. **Duplicate Verification** — deterministic + fuzzy matching engine, pre-save check,
   match UI, request-to-serve flow.
4. **Programmes, Activities & Benefit Ledger** — programme/activity config, enrollment,
   benefit ledger, double-dipping flags.
5. **Referrals, Grievances & Notifications** — referral lifecycle, GRM, in-app + email.
6. **Dashboards, Reporting & GIS** — executive/MDA/partner dashboards, exports, PostGIS maps.
7. **Sync, Data Sharing, Graduation & Hardening** — sync jobs, data-sharing governance,
   graduation, security/NFR hardening, deployment prep.

When a phase is done, update the checklist in the relevant phase doc and wait for the next phase.

> **Active remediation (PRD v1.2):** Phase 3 (duplicate verification) and Phase 2 (registry
> validation + ownership serve seam) must be brought up to the v1.2 rules in §9 below before Phase 6
> work begins. See `docs/PHASE-2-BUILD-PROMPTS.md` (Remediation V1.2 — R2.x) and
> `docs/PHASE-3-BUILD-PROMPTS.md` (Remediation V1.2 — R3.x).

---

## 6. How to run (fill in as you build)

```bash
# Bring up the whole stack
docker compose up -d

# Backend
docker compose exec api php artisan migrate --seed
docker compose exec api php artisan test

# Frontend
docker compose exec web npm run dev
docker compose exec web npm run test
```

Keep `README.md` updated so these commands always work from a fresh clone.

---

## 7. Definition of Done (every task)

A task is done only when **all** of these are true:

- [ ] It satisfies the relevant PRD requirement(s), referenced by ID.
- [ ] It follows `docs/CONVENTIONS.md` and `SECURITY.md`.
- [ ] Input is validated; errors use the standard error format.
- [ ] Security-relevant and data-changing actions are written to the audit log.
- [ ] Automated tests cover the happy path and key failure/permission cases, and pass.
- [ ] No secrets, credentials, or PII are committed or logged.
- [ ] `README.md` / docs updated if setup or behaviour changed.
- [ ] Changes are in small, clearly-described commits referencing requirement IDs.

---

## 8. Things to never do

- Never build ahead of the current phase.
- Never store secrets in code or commit a real `.env`.
- Never log PII (NIN, BVN, phone, names) or secrets.
- Never disable, weaken, or bypass auth, RBAC, validation, or audit logging.
- Never let one MDA read or edit another MDA's data unless cross-MDA access is explicitly granted.
- Never hard-code stakeholder decisions (matching thresholds, eligibility, SLAs, consent rules)
  — make them configurable and ask me for the values.
- Never fabricate integration with external systems (NIN/NIMC, SOCU) — mock against a clear
  interface and tell me what real access is required.
- Never introduce off-palette colors, ad-hoc fonts/spacing, or a duplicate of a component that
  already exists in `docs/DESIGN-SYSTEM.md`.
- Never add a manual single-record create path for beneficiaries or households — in the API or the
  UI. Ingestion is **bulk/source-only** (Excel/CSV, Kobo, ODK, REST API, SOCU, existing government
  systems), matching PRD §8.1. Editing/correcting an existing imported record (owner-only) is a
  separate, allowed action; creating a new one by hand is not.

---

## 9. Locked decisions — Duplicate verification, ownership & upload (PRD v1.2)

- **Matching cascade is the default *config*, not code.** The shipped default evaluates in order:
  exact NIN → exact BVN → fuzzy name/phone, and STOPS at the first exact (deterministic) match.
  Absent NIN/BVN → skip that stage and fall through. Fields, order, weights and thresholds stay
  admin-configurable (FR-DUP-02/03/08). Never hard-code the cascade or its numbers.
- **Adjudication happens only at the probable band.** The officer decides "same person / not" only
  for fuzzy matches (FR-DUP-09). Exact NIN/BVN matches are definitive and not adjudicated. The
  discard-or-serve choice still applies at every band. Never silently auto-merge.
- **Identity-field validation is row-level reject.** If a row's name, phone, NIN, or BVN is PRESENT
  but malformed, reject the whole row to the error report; do not save it (FR-REG-05). Absent
  optional NIN/BVN is valid. Non-identity fields that fail validation are dropped/flagged for that
  row and the row still saves (FR-REG-09). Never partial-save an identity field.
- **Request-to-serve requires Owner-MDA approval.** A non-owner MDA that matches a duplicate must
  raise a Service Request; the Owner MDA accepts/declines (FR-OWN-06). Interventions may be recorded
  ONLY after acceptance. On acceptance the requester gains READ access to the full beneficiary
  record (FR-OWN-07); ownership/edit rights never move. This is distinct from a Referral (FR-REF,
  outbound). Log every request and decision (FR-AUD-01).
- **Activity-first upload.** An activity must exist before beneficiaries are uploaded to it; every
  upload is bound to a selected registered activity, and the resulting intervention is recorded
  under it (FR-REG-10, FR-PRG-05). Beneficiaries still enter the shared registry under first-importer
  ownership (FR-OWN-01).

---

## 10. Locked decisions — Programme catalog & activity ownership (revises FR-PRG-01/02)

- **A Programme is a GLOBAL catalog entry, created only by the System Administrator** (optionally SP
  Coordination per PRD §4). It is a shared service *type* (e.g. Cash Transfer, Skills Training),
  **not owned by any MDA**, and is readable by all MDAs so they can select it. It holds type-level
  attributes only: name, objective, type (HH/individual), benefit category, standard eligibility.
  **MDAs cannot create, edit, or delete programmes** — remove programme create/edit from the MDA
  officer AND MDA admin views.
- **An Activity is MDA-owned** (`owner_mda_id`, `ScopedToMda`) and is created by MDA officers/admins.
  Activity creation begins by **selecting a programme from a dropdown of available catalog
  programmes**, then captures the MDA-specific execution details: location (LGA/Ward), schedule,
  budget, funding source, period, targets, and any activity-level eligibility.
- **One programme, many MDAs, separate activities.** The same catalog programme (e.g. Cash Transfer)
  may be run by multiple MDAs, each through its own activity. Budget/funding live on the Activity, not
  the Programme.
- **Interventions are programme-typed, delivered via an activity.** A benefit/intervention references
  the beneficiary, the (catalog) programme, the (MDA-owned) activity, and the delivering MDA. When a
  duplicate is found and a serving MDA offers an intervention via request-to-serve, the "service
  offered" is a programme, delivered under that MDA's own activity.
- **Beneficiary data ownership is UNCHANGED** (first-importer owns the beneficiary; MDA isolation
  intact). Only *programme* ownership changed.
- **Never** expose programme creation/editing to an MDA role, and never make a programme MDA-scoped —
  it is a shared catalog.