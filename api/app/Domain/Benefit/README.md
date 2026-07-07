# Benefit domain — the benefit ledger

The benefit ledger and manual delivery recording (PRD **FR-BEN-01**, **FR-BEN-02**,
**FR-BEN-04**, §8.3). It records **delivery**, not disbursement — **SP-MIS does not
move money** (§2.3); `monetary_value` is descriptive data (integer minor units,
kobo/NGN). **Status: Phase 4 — recording + verification + per-beneficiary ledger.**
Aggregation/budget (FR-BEN-03/FR-PRG-04) and double-dipping (FR-BEN-05) build on it.

## Pieces

| Piece | Role |
| --- | --- |
| `Models/Benefit` | One ledger entry. Scoped on **`mda_id`** — the DELIVERING MDA (independent of the beneficiary's owner). `Auditable`. |
| `Enums/BenefitType` | Starter taxonomy: `cash`, `food`, `agricultural_input`, `training`, `health`, `education`, `cash_for_work`, `other` (can be made configurable later). |
| `Enums/BenefitStatus` | `recorded` · `verified` · `reversed`. |
| `Enums/VerificationMethod` | `none` · `field_confirmation` · `signature` · `otp` · `biometric`. |
| `Contracts/BenefitVerifier` + `Services/Verifiers/*` | Verification strategies (FR-BEN-04): **field-confirmation** + **signature** implemented; **OTP** + **biometric** stubbed as *unavailable*. |
| `Services/VerifierRegistry` | Resolves a verifier by method; lists the ones that can actually run. |
| `Services/BenefitRecorder` | Records a delivery (enrollment-gated) and applies verification, atomically. |
| `Policies/BenefitPolicy` | Record/verify = delivering MDA; reads for delivering MDA + beneficiary owner + oversight. |

## Recording (§8.3)

Select a beneficiary + programme/activity, record `benefit_type`, quantity/unit,
`monetary_value`, funding source, delivery date, and (optionally) verify inline.

- The **delivering MDA** is the programme owner (recording is authorized against
  programme ownership); `mda_id` is set to it.
- **Non-owner authorization (FR-BEN-06).** When the delivering MDA does **not** own
  the beneficiary, recording is allowed **only** with an explicit accepted
  authorization — an accepted **Service Request** (R2.3) or an accepted **Referral**
  (Phase 5) — never a generic serve seam. `Services/DeliveryAuthorization` checks the
  `Authorization/DeliveryAuthorizer`s registered under its container tag: the
  `ServiceRequestAuthorizer` (Benefit domain) and the `ReferralAuthorizer` (Referral
  domain self-registers) — **no recorder change**. Unauthorized →
  `409 DELIVERY_NOT_AUTHORIZED`; the granted basis (`service_request` | `referral`)
  is audited (`benefit.delivery_authorized`). **Ownership never changes.**
- The beneficiary must also have an **open enrollment** in the programme (§8.3).
  Not enrolled → `422 NOT_ENROLLED`.
- **No money is moved.** The ledger entry is the only artifact — it accumulates in the
  beneficiary's single cross-MDA ledger, attributed to the delivering MDA + activity.

## Verification (FR-BEN-04)

`field_confirmation` and `signature` work now (signature requires a
`verification_reference`). `otp` and `biometric` are recognised but **stubbed as
unavailable** — they need external access (SMS/OTP gateway; biometric/NIMC) that is
not configured, so they return `422 VERIFICATION_UNAVAILABLE` with a message naming
the access required, and **never fabricate** a result. When verification is
requested at record time and fails, the whole record is rolled back (nothing
persisted unverified). Enabling a stub later = binding a real implementation in
`BenefitServiceProvider` — no caller changes.

> ⚠️ **External access required** to enable OTP/biometric verification: an SMS/OTP
> gateway and biometric/NIMC integration. Ask the platform owner — do not fabricate.

## Ledger visibility (FR-BEN-01/03)

- `GET /beneficiaries/{id}/benefits` — the **complete cross-MDA history** for a
  beneficiary, readable by the beneficiary's **owner MDA**, any **delivering MDA**,
  or **oversight** (`cross-mda.view`); others get `403`.
- `GET /benefits` — an MDA's **own** deliveries (scoped to `mda_id`); oversight sees all.

## Double-dipping detection (FR-BEN-05)

Configurable rules flag when the **same benefit type** is delivered to the **same
beneficiary** by **different MDAs** within a rule's window. It **flags, never
blocks** — the delivery always succeeds.

- `Services/DoubleDippingDetector` runs inside `BenefitRecorder` after each
  delivery (so manual and bulk recording are both covered), scans the ledger
  scope-bypassed, and raises a `benefit_flags` row (audited, deduped per pair).
- Rules (`double_dipping_rules`) are **admin-editable + audited**:
  `GET/POST /double-dipping-rules`, `PUT/DELETE /double-dipping-rules/{id}`
  (`double-dipping.view`/`.edit` — System Admin + SP Coordination). A default rule
  (30 days, all types) is seeded; tune `period_days` and `benefit_types` (empty =
  all).
- Flags (`GET /benefit-flags`, `POST /benefit-flags/{id}/review`) are visible to
  either involved MDA or oversight; review confirms/dismisses (`benefit.view` /
  `benefit.approve`).

## Match reveal population (FR-DUP-04)

`Services/BeneficiaryRevealPresenter` fills the FR-DUP-04 reveal's **programmes**
(from enrolments) and **benefits-received** (from the ledger) sections with real
data — used by import screening, duplicate search, and the cross-MDA lookup. It
reads across MDAs (the reveal is a coordination signal) but **masks exact monetary
values** from anyone other than the beneficiary's owner MDA or oversight; programme
names, benefit types/dates and the delivering MDA are shown to all reveal viewers.

## Aggregation & budget (FR-BEN-03, FR-PRG-04)

`Services/LedgerAggregator` is the **single aggregation seam** — every dashboard
figure goes through it (so the backing can later become materialised rollups
without touching callers). All queries run through the scoped `Benefit` model, so
they **respect MDA scoping/visibility** automatically; **reversed** entries are
excluded from utilised/totals.

- `GET /benefits/aggregate?group_by=…` — grouped totals (count, `total_value` in
  kobo, `total_quantity`) by **programme · activity · mda · lga · ward · beneficiary
  · benefit_type**, plus grand totals. Optional filters: `programme_id`,
  `activity_id`, `mda_id`, `benefit_type`, `status`, `lga`, `ward`, `date_from`,
  `date_to`.
- `GET /programmes/{id}/budget` and `GET /activities/{id}/budget` — **allocated vs
  utilised** (`allocated`, `utilized_value`, `utilized_quantity`, `remaining`,
  `utilization_rate`), utilised summed from the ledger. Owner/oversight only.

LGA/Ward coverage is **join-free**: the recipient's `lga`/`ward` are denormalised
onto each benefit at record time and indexed, so grouping by location is a plain
indexed `GROUP BY` — efficient and cache-friendly for the Phase 6 dashboards.

## Bulk delivery (distribution list, §8.3)

For an activity, upload an Excel/CSV **delivery list** and commit many benefits at
once — **reusing the Phase 2 import lifecycle** (upload → queued parse → preview →
confirm → queued commit) and the same `BenefitRecorder` per row. This creates
**benefits, not beneficiaries** — rows reference *existing* beneficiaries by
`beneficiary_id`, `nin`, or `bvn`.

- **Preview** validates each row: unmatched or not-enrolled beneficiaries are
  **errors** (reported, not committed); ineligible ones are an advisory **flag**
  (or an error when the programme enforces eligibility). `otp`/`biometric`
  verification is rejected for bulk (stubbed unavailable).
- **Commit** records only the valid rows via the manual-recording logic, so the
  **delivering MDA is the importer** and the same enrollment/verification rules
  apply. **Idempotent per batch + row**: each committed row is stamped with
  `benefit_id`, so re-running never double-records; invalid rows are left in place.
- Pieces: `Jobs/ParseBenefitImport`, `Jobs/CommitBenefitImport`,
  `Imports/BenefitDeliveryRowValidator`, `Models/BenefitImportBatch` + `…Row`.
  Delivery-list `monetary_value` is in **kobo** (minor units), matching the ledger.

## Permissions (RBAC) & endpoints

`benefit.view` / `benefit.create` (record + bulk upload/confirm) / `benefit.approve`
(verify). MDA Admin & MDA Officer get all three; oversight + partner get **view**.

| Method | Path | Permission |
| --- | --- | --- |
| GET | `/benefits` (`filter[programme_id]`/`beneficiary_id`/`status`/`benefit_type`) | `benefit.view` |
| POST | `/benefits` (record) | `benefit.create` |
| GET | `/benefits/{id}` | `benefit.view` |
| POST | `/benefits/{id}/verify` | `benefit.approve` |
| GET | `/beneficiaries/{id}/benefits` (ledger) | `benefit.view` |
| GET | `/benefit-imports` (`filter[activity_id]`) | `benefit.view` |
| POST | `/benefit-imports` (upload, `activity_id` + `file`) | `benefit.create` |
| GET | `/benefit-imports/{id}` (preview) | `benefit.view` |
| POST | `/benefit-imports/{id}/confirm` (commit) | `benefit.create` |

Validated Form Requests, standard envelopes, and every record/verify is audited.
Tests: `tests/Feature/Benefit/BenefitLedgerTest`, `BenefitImportTest`,
`BudgetAndAggregationTest`.
