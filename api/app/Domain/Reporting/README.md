# Reporting domain — dashboards, reports & GIS (FR-RPT-01→04, FR-GIS-01/02)

Everything decision-makers see: scope-aware dashboards, standard + ad-hoc reports
(CSV/Excel/PDF), scheduled delivery, and the GIS coverage map. **Status: Phase 6 —
complete.** All output is **de-identified aggregate data** — no beneficiary PII.

## Scope — the one rule everything obeys

`Support/DashboardScope` + `Services/DashboardScopeResolver` resolve a caller to one of:

| Kind | Who | Sees |
| --- | --- | --- |
| `state_wide` | Executive, SP Coordination, M&E (`cross-mda.view`) | all MDAs |
| `mda` | MDA users | their own MDA (+ active cross-MDA grants) |
| `partner` | Development Partner | their **funded programmes** only |

Every dashboard/report/map query applies the scope **explicitly** (bypassing the
request-time `MdaScope`) so it is identical in a request or on the scheduler/queue.
`DashboardScope::covers()` decides whether a recipient may receive a scoped report.

**Governance is a second, independent axis** (`DashboardScope::$governance`). The three
kinds above answer *how much PROGRAMME data may you see*; governance answers *may you
see who did what*. Only a **System Administrator** carries it, so an Executive is
state-wide yet can never reach — or be sent — a user/audit/import report. It is
captured on the run and the schedule (`scope_governance`), so a queued or unattended
generation rehydrates it, and `covers()` rejects a governance report for any
non-governance recipient. It is deliberately **not** part of `key()`: it gates
administrative datasets, not dashboard metrics, so an admin shares the state-wide
snapshot.

**Partner funding scope (6.0 → 6P):** a partner's scope is the set of programmes they
fund. **Phase 6P made the queryable source of truth `activities.funding_partner_id`**
(a Development-Partner user), so every partner figure is **activity-precise** — the
budget/delivery/reach of the activities the partner actually funds, never a co-funder's.
`programme_funders` (`Programme\Models\ProgrammeFunder`) is retained for the executive
coordination "partners" list. A partner sees only their funded data; coordination
datasets (referrals/grievances) don't apply.

## 1. Aggregation layer + dashboards (FR-RPT-01/02)

- `Services/DashboardMetricsService` computes every metric family (registry,
  programmes, duplicates, benefits + budget, referrals, grievances, coverage) for a
  scope, reusing the Phase 4 `Benefit\Services\LedgerAggregator` (scoped methods).
- **Summary, not raw scans:** `Services/DashboardSnapshotService` precomputes one
  `dashboard_snapshots` row per scope (`Jobs/RefreshDashboardSnapshots`, every 15 min);
  `DashboardService` reads that single indexed row (warms on cold miss).
- `GET /dashboard` (`dashboard.view`) returns the caller's scoped snapshot. The web
  app renders Executive / MDA / Partner variants from it (forest KPI panels + bars),
  read-only.

## 2. Export service (FR-RPT-03)

`Export/` — one `ReportData` (format-agnostic) rendered by three `ReportExporter`s:

- **CSV** (native), **Excel** (PhpSpreadsheet), **PDF** (Dompdf, branded
  `resources/views/reports/pdf.blade.php` — forest letterhead, crest slot).
- **PII masking is centralised**: `ReportData::cell()` masks any column flagged
  `sensitive` (`SensitiveMasker`, last-4 rule) before any exporter sees it. The
  standard/ad-hoc catalogues are aggregate-only, so there are no PII columns to begin
  with — masking is defence in depth.
- Generation is **queued** (`Jobs/GenerateReport`): builds under the captured scope,
  stores the file, fires `ReportReady`; the requester is notified (in-app + email) via
  the Phase 5 notifier. Every generation + download is **audited** (`report.generated`,
  `report.downloaded`).

### Standard report catalogue (`Reports/ReportCatalogue`)

`beneficiaries_by_lga`, `benefits_by_programme`, `benefits_by_mda`, `benefits_by_lga`,
`budget_utilization`, `referral_completion`, `grievance_sla` — each scope-aware
(coordination reports hidden for partners). `Reports/ReportBuilder` projects the
aggregation layer into tabular data, so a report reconciles with its dashboard.

### Ad-hoc builder (`Reports/AdHoc`)

`AdHocDatasetRegistry` whitelists datasets → dimensions / measures / filters (LGA/Ward,
programme, MDA, date range, status). `AdHocReportBuilder` validates every
dataset/column/filter against the whitelist and applies scope **before** the user's
filters, so a filter can only narrow within scope and a PII/unlisted column can never
be selected. Preview, export, and **saved definitions** (`report_definitions`, reusable
+ the basis for scheduling).

**Administrative datasets** (`admin => true`) back the System Administrator console's
Reports section: `users`, `organizations`, `programme_catalogue`, `duplicates`, `audit`
and `imports` (registry reporting reuses the existing `beneficiaries` dataset). They
are released only to a **governance** scope — never to state-wide oversight — and, like
every other dataset, expose group-by dimensions and count/sum measures only: a user
report groups by role or status but never by name or email, and an audit report tallies
actions without touching the before/after payload. The console adds datasets to this
registry; it does not add a second reporting engine.

### Scheduled reports (FR-RPT-04)

`report_schedules` pair a standard key or a saved definition with frequency / format /
delivery / recipients. `Jobs/RunDueReportSchedules` (daily) generates due schedules;
`Listeners/DeliverScheduledReport` delivers to **scope-validated recipients** —
**secure link** (in-app + email link; nothing leaves the system) or **attachment**
(`Mail/ScheduledReportMail`). Recipients are validated to `covers()` the report scope
at create/edit **and** re-validated at delivery, so a schedule can never send
out-of-scope data. Manage (list/edit/pause/delete) is audited.

## 3. GIS (FR-GIS-01; FR-GIS-02 stubbed)

LGA (admin 2) / Ward (admin 3) coverage. See [`Gis/README.md`](Gis/README.md) for the
boundary loader, the expected **GeoJSON format**, and where to source Jigawa
boundaries. `GET /gis/coverage` returns a **choropleth FeatureCollection** when
boundaries are loaded, else a ranked-**table fallback** (`mode: table`) — the page
never breaks. Heat maps (FR-GIS-02) are a documented extension point on the PostGIS
`geom` column; not built.

## Permissions

`dashboard.view` (dashboards + GIS coverage), `reporting.view` (catalogue/runs/
schedules), `reporting.export` (generate/download/schedule). Boundary loading is a CLI
command (ops), not a runtime permission.

## Endpoints (`/api/v1`, Sanctum + `permission:` gated)

`GET /dashboard` · `GET /gis/coverage` · `GET /reports/catalogue` ·
`GET|POST /reports` · `GET /reports/{id}` · `GET /reports/{id}/download` ·
`GET /reports/adhoc/datasets` · `POST /reports/adhoc/preview|/reports/adhoc` ·
`report-definitions` (CRUD + `/run`) · `report-schedules` (CRUD).

Tests: `tests/Feature/Reporting/*` (dashboard metrics + scope, exports + masking +
audit, ad-hoc constraints, schedule delivery + scope validation, GIS loader + fallback);
`web/src/features/{dashboard,gis}/*`.

---

## Phase 6E — Executive Reporting Suite (FR-RPT-01/02/03)

A 5-tab Governor's/Executive view (`web/src/features/dashboard/*`) built **on** the
Phase 6 aggregation layer + GIS map. Read-only, de-identified **aggregates only**;
**net-unique** (distinct persons served) is the headline everywhere — deliberately
distinct from the **gross** delivery count.

**Aggregation extensions** (`Services/DashboardMetricsService`, additive to the
snapshot): `population` (households, individuals, **net-unique served**, new
registrations, LGAs/wards covered), `demographics` (gender / age bands / household vs
individual — **captured fields only**), `household_size`, `programme_performance`
(target vs net-unique reached, budget, cost/beneficiary, configurable **traffic-light**,
activity-level drill-down), `registry_quality`, `coordination` (agencies, joint
programmes, cross-MDA, referral/request throughput, **per-partner** contributions, sync
health), `coverage_bands` (**absolute** green/yellow/red/grey — never a population %),
`trends` (12-month periodised), `programme_scoring`, and inert `deferred` slots.

**The 5 tabs** — Overview (KPIs, insights, alerts, trends, **projections**, programme
share, demographics), Programmes (cards + comparison + financials + traffic-light +
activity drill-down), Registry (KPIs + data quality + gender/age/household-size
breakdowns), Coordination (agencies, per-partner funding, cross-agency, data sharing;
the **meetings module is out of scope** — future/external slot only), Coverage Map
(absolute-count band choropleth with click-through detail + a pluggable overlay-layer
framework).

**Cross-cutting (FR-RPT-01/02):**
- **Filters** — `Support/DashboardFilter` (year/quarter/month, programme, LGA, ward,
  MDA). Applied **on top of** the scope in every query, so a filter can only ever
  narrow (out-of-scope MDA/programme → empty intersection). Unfiltered → snapshot;
  filtered → live recompute (`DashboardService`).
- **Drill-down** — clicking a KPI/chart/segment sets a scoped filter + switches tab
  (same filter machinery = same enforcement).
- **Forecasting** — `web/.../forecast.ts`: **simple linear** projections (budget
  runway from burn rate; beneficiary + registration growth by least-squares), each
  **clearly labelled "projection · based on current trend"** with its assumption.
  **Not ML.**
- **Export (FR-RPT-03)** — `GET /dashboard/export?format=csv|xlsx|pdf` →
  `Export/ExecutiveExportBuilder` renders the **aggregate** current view via the shared
  exporters. Gated by `reporting.export` (**not** `beneficiary.export`/`reveal_pii`),
  audited (`dashboard.exported`); no PII column exists to begin with.
- **Role tiering** — `DashboardScope::tier()` (statewide / operational / partner),
  enforced by the existing scope resolver + RBAC; the filter can never escape it.

**Demo data** — `Database\Seeders\ExecutiveDemoSeeder` (chained from `LocalDevSeeder`)
gives a rising 10-month history (trends + projections), sync health, and an
import-matched duplicate so every panel renders.

### Deferred / omitted — and the switch-on condition

| Item | Why omitted | Switch-on condition |
| --- | --- | --- |
| Population penetration, coverage % | No population denominator held | Load an LGA/ward **population baseline**; fill `deferred.population_penetration`, then the coverage map/KPIs can show % (today: **absolute bands only**) |
| Targeting accuracy | No poverty register / PMT denominator | Integrate a **poverty register / PMT**; fill `deferred.targeting_accuracy` |
| Vulnerability / disability / PWD / IDP / poverty / occupation breakdowns | **No such field is captured** | Add the registry **field(s)**; extend `demographics` (panels are **absent**, not empty) |
| Outcome / M&E indicators | Needs survey/outcome integration | Wire **M&E outcomes**; fill `deferred.outcome_indicators` |
| Identity verification rate | No explicit identity-verification field (today: review-status proxy) | Add an **identity-verification field**; fill `deferred.identity_verification` |
| Map overlay layers (schools, health, IDP camps, flood) | Data supplied later | `registerMapLayer()` an **external GeoJSON** source (framework + example: `web/.../gis/mapLayers.ts`) |
| Meetings / attendance / action items | **Not part of SP-MIS** | Track in an **external coordination tool** (noted as a slot; not built here) |
| Heat maps (FR-GIS-02) | Extension point | Query the PostGIS `geom` column |

Phase 6E tests: `tests/Feature/Reporting/{ExecutiveMetrics,ExecutiveFilter,Dashboard-
Export,ExecutiveDemoSeeder,GisCoverage}Test.php`; `web/src/features/dashboard/*` +
`web/src/features/gis/*`. Completion checklist: [`docs/PHASE-6E-CHECKLIST.md`](../../../../docs/PHASE-6E-CHECKLIST.md).

---

## Phase 6P — Funding Partner Reporting Suite (FR-RPT-02/03)

A 5-tab **Development-Partner** view (`web/src/features/dashboard/FundingPartner*`),
the partner-facing analog of 6E, built on the same aggregation layer + GIS map.
Read-only, de-identified **aggregates only**, and **ACTIVITY-PRECISE**: every figure is
scoped to the activities the partner funds (`activities.funding_partner_id`) — a partner
sees **only their own funded data**, enforced server-side by `DashboardScopeResolver`.

**Labelling (non-negotiable):** money is **DELIVERY VALUE** — the recorded value of
benefits delivered under funded activities — on an **Allocated → Delivered → Remaining**
lifecycle. It is **never** treasury expenditure; the words *spent / disbursed /
expenditure / committed-vs-disbursed / grant / audit* are never shown or faked. *SP-MIS
records value as data; it never moves money.*

**Aggregation** (`DashboardMetricsService::partnerFunding()`, computed only for a partner
scope, nested under `metrics.partner_funding`):

| Block | Feeds tab | Notes |
| --- | --- | --- |
| allocated / delivered_value / remaining / utilization_rate, funded programmes/activities, implementing MDAs, net-unique reached, target, cost/beneficiary, `reach` (households/women/children), `coverage_bands` | **Overview** | delivery value; captured demographics only; coverage **absolute** |
| `programmes[]` (activity-precise per funded programme: budget→delivered→remaining, target/reached, **absolute** coverage, completion, interventions, avg benefit value, delivery-rate series, 4-state `status_light`, `output_indicators`, activity drill-down) + rolled-up `output_indicators` | **Programmes & Results** | absorbs M&E; **OUTPUTS ONLY** (interventions × benefit type × captured demographic) |
| `registry` (funded-cohort KPIs, reduced funnel **Registered→Enrolled→Receiving**, captured demographics, data quality) | **Registry** | cohort = enrolled ∪ served via funded activities |
| `coordination` (landscape, funding-by-partner **amounts for self only**, MDA landscape, data sharing/sync) + `programme_overlap` | **Coordination** | overlap = same programme × LGA, different funder/MDA; a co-funder's money never leaks |
| `GisCoverageService` `funding_allocated` per area (activity-precise) + coverage | **Investment Map** | funding-density choropleth + quadrant analysis + LGA drill-down; table fallback |

**Status model** (`programme_status` config): On Track / At Risk / Delayed / Completed
from completion (reached ÷ target) + timeline (delivery end date) — configurable, never a
fabricated %.

**Cross-cutting (FR-RPT-02/03):**
- **Filters** — the shared `DashboardFilter` (year/quarter/month, programme, LGA, ward,
  MDA), applied across all five tabs; scoped options come from `filterOptions()`, and a
  filter can only ever **narrow within funded scope** (a non-funded programme → empty).
- **Drill-down** — KPI → Programmes/Registry; programme-overlap LGA → Investment Map;
  map area → Registry/Programmes — all via the same scoped filter machinery.
- **Export (FR-RPT-03)** — `GET /dashboard/export` renders the **aggregate** current
  view; gated by `reporting.export` (the partner has it) — **never**
  `beneficiary.export` / `export.reveal_pii`, so no raw registry PII can be exported.
- **Role tiering** — `DashboardScope::tier() === 'partner'`; the resolver + RBAC keep a
  partner to their funded programmes; the filter can never escape it.

**Demo data** — `Database\Seeders\PartnerDemoSeeder`: two partners (World Bank + UNICEF)
funding **overlapping** programmes in a **shared LGA** through **different MDAs**, with
committed budgets, delivered benefits across historical periods, varied demographics, and
enrolled-but-not-served beneficiaries — so every tab, the overlap detector and the map
render meaningfully (never real PII). Run: `php artisan db:seed --class=PartnerDemoSeeder`.

### Deferred / omitted — and the switch-on condition

| Item | Why omitted | Switch-on condition |
| --- | --- | --- |
| Committed vs **disbursed**, **grant** lifecycle | SP-MIS is not a grants ledger; it holds committed budget + delivery value only | Add a **grant module** (award → disbursement schedule); then a Committed→Disbursed lifecycle can sit beside Allocated→Delivered |
| Treasury **expenditure** + audit | No expenditure/audit data is held (delivery value ≠ money moved) | Integrate **treasury expenditure + audit** feeds; only then show spend (still labelled distinctly from delivery value) |
| Eligible → Selected funnel steps | No eligible-population **denominator** / selection model | Load an **eligible-population denominator** + a selection/PMT model; the reduced funnel then extends upstream (today: inert slot) |
| Outcome indicators (poverty ↓, income, attendance, food security, employment) & Outcomes→Impact | Need external evaluation data | Wire **outcome M&E** / evaluation data; fill the greyed external slot (today: **outputs only**) |
| PWD / vulnerability / poverty demographics | **No such field captured** | Add registry field(s); extend `registry.demographics` (panels **absent**, not empty) |
| Coordination meetings / action items, reporting-compliance | **Not part of SP-MIS** (no meetings/reporting-workflow module) | Track in an **external coordination tool** (inert slots only) |
| Map overlay layers (schools, health, IDP camps, flood) | External data supplied later | `registerMapLayer()` an **external GeoJSON** (framework: `web/.../gis/mapLayers.ts`) |

Phase 6P tests: `tests/Feature/Reporting/{PartnerFunding,PartnerDemoSeeder,GisCoverage}Test.php`
(attribution + funded-scope enforcement, allocated/delivered/remaining + labelling,
programme overlap, reduced funnel, output indicators, status model, filters, export
permission, no-raw-PII, investment-map funding density); `web/src/features/dashboard/FundingPartner*`
+ `web/src/features/gis/investment.test.ts`. Completion checklist:
[`docs/PHASE-6P-CHECKLIST.md`](../../../../docs/PHASE-6P-CHECKLIST.md).
