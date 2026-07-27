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

**Partner funding scope (6.0):** a partner's scope is the set of programmes linked to
them via `programme_funders` (`Programme\Models\ProgrammeFunder`). A partner sees
coverage/budget/benefits for those programmes and beneficiaries served by them —
nothing else; coordination datasets (referrals/grievances) don't apply.

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
