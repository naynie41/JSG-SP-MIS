# Programme domain — programmes, activities & enrollment

Programme/activity management and beneficiary enrollment (PRD **FR-PRG-01/02/03**),
plus **FR-OWN-04 auto-route**. An MDA configures the programmes it runs and the
activities under them, then enrolls already-registered beneficiaries/households;
ownership and scoping mirror the rest of the platform (register/own once,
coordinate across MDAs). The benefit ledger + budget (FR-BEN, FR-PRG-04) live in
the sibling [Benefit domain](../Benefit/README.md). **Status: Phase 4 complete.**

## Phase 4 requirement map

| Req | What | Where |
| --- | --- | --- |
| **FR-PRG-01** | Configure programmes (name, objective, type, eligibility, funding, budget, period) | `Models/Programme`, `ProgrammeController`; `web/features/programmes` |
| **FR-PRG-02** | Activities under a programme (target, location, schedule, budget) | `Models/Activity`, `ActivityController`; `ActivityFormModal` |
| **FR-PRG-03** | Enroll/assign beneficiaries + households (single + bulk, type-aware) | `Models/Enrollment`, `EnrollmentService`, `EnrollmentController`; `EnrollModal` |
| **FR-PRG-04** | Budget allocated-vs-utilized per programme/activity | `Benefit/Services/LedgerAggregator`; `GET /{programmes,activities}/{id}/budget` |
| **FR-OWN-04** | Auto-route: suggest-then-confirm programme matching | `Services/ProgrammeMatcher` + `ProgrammeMatchingRouter`; `GET .../routing-suggestions`, `POST .../routing-assignments` |
| **FR-BEN-01→05** | Benefit ledger, recording, verification, aggregation, double-dipping | see [Benefit domain](../Benefit/README.md) |
| **FR-DUP-04** | Match reveal now shows real programmes + benefits | `Benefit/Services/BeneficiaryRevealPresenter` |

> **Boundary — records delivery, NOT payment (§2.3):** SP-MIS records that a benefit
> was *delivered*; it never moves money. Every monetary value (programme/activity
> budget, benefit value) is descriptive data in integer minor units (kobo, NGN).
> There is **no disbursement/wallet/transaction logic anywhere**.

## Pieces

| Piece | Role |
| --- | --- |
| `Models/Programme` | A programme owned by an MDA (`owner_mda_id`). `ScopedToMda` + `Auditable`, soft-deletable. `enforce_eligibility` toggles advisory→blocking eligibility. |
| `Models/Activity` | A unit of work under a programme. `owner_mda_id` is **denormalised from the programme** so the same `MdaScope` applies directly. |
| `Models/Enrollment` | Links a beneficiary (individual programme) or household (household programme) to a programme/activity. Scoped on **`mda_id`** — the ENROLLING MDA. |
| `Enums/ProgrammeType` | `household` \| `individual` — drives which target may enroll. |
| `Enums/ProgrammeStatus` / `ActivityStatus` / `EnrollmentStatus` | Lifecycle enums (enrollment: `enrolled`/`suspended`/`exited`/`graduated`). |
| `Services/EnrollmentService` | Enroll one target: the serve-seam check, eligibility, and the single-open-enrollment rule. |
| `Services/EligibilityEvaluator` | Evaluates a programme's structured criteria against a target (advisory flag or enforced block). |
| `Policies/*` | **Owner-MDA-only mutation**; oversight (`cross-mda.view`) reads across MDAs but never mutates. |
| `ProgrammeServiceProvider` | Registers the module permissions and binds the policies. |

## Ownership & scoping

- **List/show** are MDA-scoped by the model's global `MdaScope` — an MDA sees its
  own; oversight roles (`cross-mda.view`) see all. A different MDA gets **404** on
  a record it can't see.
- **Create/update/archive** are **owner-MDA only** (policy). A non-owner attempting
  a mutation gets **403** (the record is resolved without the owner scope so the
  policy — not a 404 — is the boundary, consistent with the registry).
- Activities inherit `owner_mda_id` from their programme on create, so an activity
  is always scoped to (and mutable only by) the programme's owner MDA. Creating an
  activity is authorized against the **parent programme's** ownership.
- Programmes/activities are **archived** (a reversible status), never deleted.

## Data notes

- **Money** (`budget_amount`) is stored as **integer minor units (kobo, NGN)** to
  stay exact.
- `eligibility` is structured JSON (list of criteria) — used by programme-matching
  (FR-OWN-04) in a later Phase 4 step; validated as an array here.
### Activity location — a SET, not a single place

An activity declares **many LGAs, and many wards within each**, in `activity_locations`
(`activity_id`, `lga_id`, nullable `ward_id`). One row per selected ward; a row with
`ward_id = NULL` means **the whole LGA**. This replaced the old single
`activities.lga` / `.ward` free-text pair, which could express only one place and joined
to nothing.

- **Real foreign keys** into the GEO.1 lookups, so coverage/GIS aggregations group by
  `lga_id` / `ward_id` instead of matching strings.
- **DESCRIPTIVE ONLY.** It states where the activity plans to operate. Nothing checks
  the beneficiaries uploaded under it against these places, deliberately: the declared
  set is a plan and the uploaded people are the fact, so a mismatch means the plan
  changed, not that the data is wrong. `ActivityLocationSetTest` has a test asserting
  no such check exists.
- **Validation** (`ValidatesLocationSet`, shared by all three entry points): every LGA
  and ward must exist, an LGA may appear once, `whole_lga` and `ward_ids` are mutually
  exclusive, and **each ward must belong to the LGA it was submitted under** — ward codes
  repeat across Jigawa, so a ward id filed under the wrong LGA is a silent way to
  mis-target an activity.
- **Writes go through `ActivityLocationService::sync()`**, which REPLACES the whole set.
  Submitting `locations` replaces it; omitting it leaves it untouched, so editing a
  budget cannot wipe an activity's declared coverage.
- An LGA chosen with **no wards ticked is stored as whole-LGA** — the same claim, kept
  as one representation rather than two.
- `Activity::scopeDeclaredIn()` is the single area filter for dashboards/GIS. A **ward
  filter also matches an activity that declared the whole LGA** containing it: declaring
  a whole LGA is a claim to cover all of it.
- **Per-area figures do not partition a total.** An activity declaring three LGAs
  appears under each, with its **full** budget — the system has no basis for splitting it
  between areas, and inventing one would put a fabricated figure on a map. Never sum
  these cells to get a state total.
- `location_description` is kept: it is free prose ("along the Hadejia road"), not an
  admin area.
- A PostGIS `geom` point column exists **on PostgreSQL only** (skipped on the sqlite
  test DB) for later GIS work and is not surfaced in the API.

**Migration:** `2026_08_15_100000` backfills each activity's old single LGA/Ward into one
row (resolving the ward *within* its LGA), records anything unresolved in the audit log
so the raw values survive, and **refuses to run** if activities have an LGA while the
`lgas` lookup is empty — otherwise a migrate-before-loading-reference-data mistake would
become permanent data loss when `2026_08_15_100001` drops the columns.

> **Deploying this to an existing database needs an ordered pre-step** — reference data must
> be populated between the first and second migration, or the refusal above stops the `api`
> container from booting (it migrates on start). Runbook:
> [DEPLOY.md §3.1](../../../../docs/DEPLOY.md). Fresh installs are unaffected: with no
> activities there is nothing to backfill.

## Enrollment (FR-PRG-03)

- Enrollment is performed by the programme's **owner MDA** (the *enrolling* MDA);
  the enrollment's `mda_id` (and scope) is that MDA, **independent of the
  beneficiary's owner MDA**.
- **Serve seam** — a non-owner MDA may enroll a beneficiary it does not own when it
  owns/has a cross-MDA grant for it, or holds one of two **distinct** authorizations:
  an active read-access grant from an **accepted Service Request** (§12, FR-OWN-06/07)
  **or** an **accepted Referral** to it (FR-REF-02, FR-BEN-06). Ownership never
  changes. Otherwise → `409 SERVICE_REQUEST_REQUIRED`.
- **Type match** — an individual programme enrolls beneficiaries, a household
  programme enrolls households; a mismatch is `422 TYPE_MISMATCH`.
- **Eligibility** — the programme's `eligibility` criteria are evaluated per target.
  Advisory by default: the enrollment is created but `eligibility_flagged` with the
  unmet attributes in `eligibility_notes`. When `enforce_eligibility` is set,
  ineligible targets are rejected (`422 INELIGIBLE`).
- One **open** enrollment per (programme, target) — a duplicate is `409
  ENROLLMENT_EXISTS` (single) or `skipped` (bulk). Bulk returns per-target outcomes.

## Auto-route / programme matching (FR-OWN-04)

`Services/ProgrammeMatcher` suggests active programmes (across MDAs) whose **type +
eligibility** fit a beneficiary and an optional **need**, ranked eligible-first.
`Services/ProgrammeMatchingRouter` implements the Phase-2 `BeneficiaryRouter` hook
using it (top eligible MDA). It is **suggest-then-confirm**: suggestions are
read-only (`GET /beneficiaries/{id}/routing-suggestions`); assigning is an
explicit, audited enrolment (`POST /beneficiaries/{id}/routing-assignments`,
`beneficiary.routed`) that reuses the enrolment serve-gate and **never transfers
ownership or assigns silently**.

## Permissions (RBAC)

`programme.*`, `activity.*` and `enrollment.view/create/edit` (`edit` also gates
archive / enrollment lifecycle). MDA Admin & MDA Officer get the full sets (manage
programmes/activities and enroll); SP Coordination, M&E, Executive and Development
Partner get **view** only.

## Endpoints (all under `/api/v1`, Sanctum + `permission:` gated)

| Method | Path | Permission |
| --- | --- | --- |
| GET | `/programmes` (search + `filter[status]`/`filter[type]`) | `programme.view` |
| POST | `/programmes` | `programme.create` |
| GET | `/programmes/{id}` | `programme.view` |
| PUT/PATCH | `/programmes/{id}` | `programme.edit` |
| POST | `/programmes/{id}/archive` | `programme.edit` |
| GET | `/programmes/{id}/budget` — allocated vs utilised (FR-PRG-04) | `programme.view` |
| GET | `/activities` (`filter[programme_id]`/`filter[status]`) | `activity.view` |
| POST | `/activities` | `activity.create` |
| GET | `/activities/{id}` | `activity.view` |
| PUT/PATCH | `/activities/{id}` | `activity.edit` |
| POST | `/activities/{id}/archive` | `activity.edit` |
| GET | `/activities/{id}/budget` — allocated vs utilised (FR-PRG-04) | `activity.view` |
| GET | `/enrollments` (`filter[programme_id]`/`status`/`beneficiary_id`/`eligibility_flagged`) | `enrollment.view` |
| POST | `/programmes/{id}/enrollments` (single) | `enrollment.create` |
| POST | `/programmes/{id}/enrollments/bulk` (`beneficiary_ids[]` or `household_ids[]`) | `enrollment.create` |
| PUT/PATCH | `/enrollments/{id}` (exit/suspend/graduate) | `enrollment.edit` |

Requests use Form Requests (validated), responses use the standard envelopes, and
every create/update is written to the append-only audit log. Tests:
`tests/Feature/Programme/ProgrammeManagementTest`, `ActivityManagementTest`,
`EnrollmentTest`.
