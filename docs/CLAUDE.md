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
    └── PHASE-1-BUILD-PROMPTS.md # the Phase 1 task prompts
```

---

## 5. Build phases

We build from scratch in **7 phases**. Each phase ends in something runnable and testable.
**Current phase: Phase 1.** Do not start a later phase until I confirm the current one is done.

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