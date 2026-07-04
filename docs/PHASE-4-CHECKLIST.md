# Phase 4 — Completion checklist (Programmes, Activities & Benefit Ledger)

Maps each delivered item to its PRD requirement ID. **Status: complete.**
Source of truth: `docs/jigawa-SP-MIS.md` (PRD), `docs/CLAUDE.md §5` (phases).
Module docs: [api/app/Domain/Programme/README.md](../api/app/Domain/Programme/README.md),
[api/app/Domain/Benefit/README.md](../api/app/Domain/Benefit/README.md).

## Programmes, activities & enrollment

| Requirement | Delivered | Where | Tests |
| --- | --- | --- | --- |
| **FR-PRG-01** — configure programmes (name, objective, type, eligibility, funding, budget, period) | Versioned `Programme` (individual/household), owner-only mutation, `enforce_eligibility` | `Programme/Models/Programme`, `ProgrammeController`; `web/features/programmes/ProgrammeFormModal` | `ProgrammeManagementTest`, `ProgrammeFormModal.test` |
| **FR-PRG-02** — activities under a programme (target, location, schedule, budget) | `Activity` (owner denormalised from programme, PostGIS-ready location) | `Programme/Models/Activity`, `ActivityController`; `ActivityFormModal` | `ActivityManagementTest` |
| **FR-PRG-03** — enroll/assign beneficiaries + households | Type-aware single + bulk; serve-seam (non-owner enroll); advisory/enforced eligibility flags | `EnrollmentService`, `EnrollmentController`; `EnrollModal` | `EnrollmentTest`, `EnrollModal.test` |
| **FR-PRG-04** — budget allocated-vs-utilized per programme/activity | Derived from the ledger (reversed excluded); forest KPI headline | `Benefit/Services/LedgerAggregator`; `GET /{programmes,activities}/{id}/budget`; `ProgrammeDetailPage` | `BudgetAndAggregationTest` |

## Benefit ledger & delivery

| Requirement | Delivered | Where | Tests |
| --- | --- | --- | --- |
| **FR-BEN-01/02** — complete benefit history; delivery fields | `Benefit` ledger (delivering `mda_id`), manual recording (§8.3), enrollment-gated | `Benefit/Services/BenefitRecorder`, `BenefitController`; `RecordBenefitPage` | `BenefitLedgerTest`, `RecordBenefitPage.test` |
| **FR-BEN-03** — ledger per beneficiary; aggregatable by programme/MDA/LGA/Ward | Cross-MDA per-beneficiary ledger (visibility-gated); `LedgerAggregator` (join-free LGA via denormalised location) | `BenefitController::ledger`/`aggregate`; `BenefitLedgerPage`, `BenefitsPanel` | `BudgetAndAggregationTest`, `BenefitsPanel.test` |
| **FR-BEN-04** — verification (field/OTP/signature/biometric) | `BenefitVerifier` interface: field-confirmation + signature implemented; OTP + biometric **stubbed unavailable** | `Benefit/Services/Verifiers/*`, `VerifierRegistry` | `BenefitLedgerTest` |
| **FR-BEN-05** — flag double-dipping (configurable period + types) | `DoubleDippingDetector` (in `BenefitRecorder`) raises flags; admin-editable rules; **never blocks** | `Benefit/Services/DoubleDippingDetector`, `double_dipping_rules`, `benefit_flags` | `DoubleDippingTest` |
| Bulk delivery (4.4) | Delivery-list import (upload → preview → idempotent commit) reusing the recorder | `Benefit/Jobs/*`, `BenefitImportController`; `BulkDeliveryPage` | `BenefitImportTest` |

## Ownership, reveal & routing

| Requirement | Delivered | Where | Tests |
| --- | --- | --- | --- |
| **FR-OWN-04** — auto-route (suggest-then-confirm) | `ProgrammeMatcher` suggests by type + eligibility + need; explicit, audited assignment; ownership never changes | `Programme/Services/ProgrammeMatcher`/`ProgrammeMatchingRouter`; routing endpoints | `AutoRouteTest` |
| **FR-DUP-04** — reveal shows real programmes + benefits | `BeneficiaryRevealPresenter` fills the reveal; monetary masked from non-owners | `Benefit/Services/BeneficiaryRevealPresenter` | `RevealPopulationTest` |

## Money-movement boundary (§2.3)

- **No disbursement / wallet / transaction logic anywhere.** SP-MIS records benefit
  **delivery** only; every monetary value (budget, benefit value) is descriptive data
  in integer minor units (kobo, NGN). The UI collects Naira and converts at the edge
  and shows a "records delivery, not payment" affordance on the recording screen.

## Verification

- Backend: `php artisan test` — **all green** (programme/activity CRUD + scoping,
  enrollment incl. household + serving-MDA, benefit recording + verification, budget
  math, aggregation, double-dipping, reveal population, auto-route).
- Backend: Pint (style) + Larastan level 5 — **clean**.
- Frontend: `typecheck` + `lint` (oxlint) + `test` — **all green**.
- Docs: module READMEs (FR maps + delivery boundary + double-dipping/verification
  config), root README Phase 4 section, this checklist.

## Local sample data

```bash
# Programmes/activities/enrolments + cross-MDA benefits incl. a double-dipping case:
docker compose exec api php artisan db:seed --class="Database\Seeders\ProgrammeSampleSeeder"
# (or a full rebuild) migrate:fresh --seed
```
