# Phase 2 — Beneficiary Registry & Ownership — Completion Checklist

Status: **complete**. Every item below is implemented, tested (backend + frontend),
and passes lint + static analysis. Requirement IDs trace to the PRD.

## FR-REG — Registration & hybrid registry

- [x] **FR-REG-01** Manual registration of an **individual** and a **household** (with members)
  — API (`POST /beneficiaries`, `POST /households`) + UI forms. Owned by the caller's MDA,
  provenance `manual`, audited.
- [x] **FR-REG-02** Hybrid sources through one pipeline:
  - [x] Excel/CSV upload (queued parse → preview → confirm)
  - [x] Kobo Collect + ODK exports (source adapters map columns → canonical schema)
  - [x] Inbound REST API intake (`POST /beneficiaries/intake`, authenticated + rate-limited)
  - [x] Clean **adapter interface** so government systems / future sources plug in with no core changes
- [x] **FR-REG-03** Provenance on every record (`registration_source`, `import_batch_id`,
  `original_record_id`); surfaced in the beneficiary detail.
- [x] **FR-REG-04** Mandatory fields + formats (NIN/BVN 11-digit + normalization, DOB sanity,
  gender/LGA enums) — shared `BeneficiaryRules`; zod schema mirrors backend.
- [x] **FR-REG-05** Invalid input rejected/flagged with the standard error envelope; import rows
  reported per-row and never silently dropped.
- [x] **FR-REG-06** Bulk import **preview with validation summary + row-level errors before any
  commit**; nothing persisted pre-confirm; batch status tracked; idempotent, retry-safe commit.
- [x] **FR-REG-07** Supporting-document attachment: validated (type/size/content), stored outside
  the web root on a private disk, owner-scoped access, audited (incl. download).
- [x] **FR-REG-08** Offline-capture **groundwork only**: idempotent intake via client-supplied
  `idempotency_key` / `original_record_id` (documented). Offline client + sync engine deferred.

## FR-OWN — Ownership model

- [x] **FR-OWN-01** First MDA to register owns the core profile (`owner_mda_id`).
- [x] **FR-OWN-02** Owner-only edit — server-enforced policy; UI reflects it (non-owner sees no edit).
- [x] **FR-OWN-03** Non-owner **lookup/serve** path returns reveal fields only (name+id, owner MDA,
  source, registration date, LGA/Ward, status) — readies Phase 3's request-to-serve.
- [x] **FR-OWN-04** Auto-route/assign extension point stubbed (`BeneficiaryRouter`, Phase 4).
- [x] **FR-OWN-05** Ownership transfer: request → owner approval → transfer, fully audited.

## Cross-cutting

- [x] MDA data-scoping enforced on every list/read/mutation; a user cannot see another MDA's records.
- [x] Household membership keeps history; **single open membership** enforced at the service layer
  (DB partial-unique backstop).
- [x] Every create/edit/delete + document access written to the append-only audit log.
- [x] Synthetic seeders: beneficiaries + households across ≥2 MDAs (never real PII, prod-guarded).
- [x] UI built strictly on the design system (Data Table, Field set, Badge status map, Modal, Toast,
  forest side-rail); typed forms with zod validation and inline API-error rendering.
- [x] Backend: full test suite green, Pint clean, Larastan level 5 clean.
- [x] Frontend: Vitest green, oxlint clean, `tsc` typecheck clean.
