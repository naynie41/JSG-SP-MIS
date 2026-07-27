# Phase 6E — Completion checklist (Executive Reporting Suite)

Maps each delivered item to its PRD requirement ID. **Status: complete.**
Source of truth: `docs/jigawa-SP-MIS.md` (PRD), `docs/CLAUDE.md §5` (phases).
Module docs: [api/app/Domain/Reporting/README.md](../api/app/Domain/Reporting/README.md).

All executive output is **read-only, de-identified aggregate data** — no beneficiary
PII anywhere. **Net-unique** (distinct persons served) is the headline, deliberately
distinct from the **gross** delivery count.

## Aggregation layer (FR-RPT-01/02)

| Requirement | Delivered | Where | Tests |
| --- | --- | --- | --- |
| Scoped executive metrics from the summary layer (not raw scans) | `population`, `demographics`, `household_size`, `programme_performance` (+ activity drill-down), `registry_quality`, `coordination` (+ per-partner), `coverage_bands`, `trends`, `programme_scoring`, inert `deferred` slots | `Reporting/Services/DashboardMetricsService`, `Benefit/Services/LedgerAggregator` | `ExecutiveMetricsTest` |
| **Net-unique ≠ gross** | `scopedDistinctBeneficiaries` (distinct `beneficiary_id`) vs `benefit_count` | `LedgerAggregator` | `ExecutiveMetricsTest`, `ExecutiveDemoSeederTest` |

## The 5 tabs

| Tab | Delivered | Where | Tests |
| --- | --- | --- | --- |
| **Overview** | KPI band (net-unique headline), rule-based insights + alerts, trends, **projections**, programme-share donut, demographics | `web/.../ExecutiveOverviewTab.tsx`, `executiveInsights.ts`, `forecast.ts` | `ExecutiveOverviewTab.test`, `executiveInsights.test`, `forecast.test` |
| **Programmes** | Performance cards, cross-programme comparison, financials, configurable **traffic-light**, **activity drill-down** (where permitted) | `ProgrammesTab.tsx` | `ProgrammesTab.test` |
| **Registry** | KPIs, data-quality panel, breakdowns by **captured fields only** (gender/age/household size); omitted panels **absent** | `RegistryTab.tsx` | `RegistryTab.test` |
| **Coordination** | Agencies, **per-partner** funding (funded-programme scope), cross-agency throughput, data-sharing/sync; meetings module noted as external slot | `CoordinationTab.tsx` | `CoordinationTab.test` |
| **Coverage Map** | **Absolute-count** band choropleth (green/yellow/red/grey — no %/index), click-through detail, table fallback, **pluggable overlay-layer framework** + example | `CoverageMapTab.tsx`, `BandChoroplethMap.tsx`, `gis/mapLayers.ts` | `CoverageMapTab.test`, `mapLayers.test`, `GisCoverageTest` |
| Read-only + scoped + no PII (all tabs) | Every tab renders aggregates only; no edit/mutating controls | (per-tab) | each tab test asserts read-only |

## Cross-cutting

| Requirement | Delivered | Where | Tests |
| --- | --- | --- | --- |
| **Filters** (year/quarter/month, programme, LGA, ward, MDA) applied across every metric/chart/map | `DashboardFilter` pushed into every query **on top of** the scope; live recompute when filtered, snapshot when not | `Reporting/Support/DashboardFilter`, `DashboardMetricsService`, `GisCoverageService`, `web/.../FilterBar.tsx` | `ExecutiveFilterTest`, `FilterBar.test` |
| **Drill-down** — click a KPI/chart/segment → detailed, still-scoped view | Click sets a scoped filter + switches tab (controllable `Tabs`) | `ExecutiveDashboardPage.tsx`, `ExecutiveOverviewTab`/`ProgrammesTab` | `ExecutiveDashboardPage.test` |
| **Forecasting** — labelled linear projections (budget exhaustion, beneficiary + registration growth); assumptions shown; **not ML** | Least-squares trend + burn-rate runway, each tagged "projection · based on current trend" | `web/.../forecast.ts`, `ExecutiveOverviewTab` | `forecast.test`, `ExecutiveOverviewTab.test` |
| **Export** — PDF/Excel/CSV of the current view; aggregate-only; permission matrix | `GET /dashboard/export` via shared exporters, gated by `reporting.export`, audited; no PII | `Reporting/Export/ExecutiveExportBuilder`, `DashboardExportController`, `web/.../ExportMenu.tsx` | `DashboardExportTest`, `ExportMenu.test` |
| **Role tiering** — Governor/Executive statewide · MDA operational · partner scope; server-side | `DashboardScope::tier()` + scope resolver + RBAC; filter can never escape scope | `Reporting/Support/DashboardScope`, `DashboardScopeResolver` | `ExecutiveFilterTest` |
| Demo data — every panel renders meaningfully | Chained sample seeders + rising 10-month history, sync health, import-matched duplicate | `Database\Seeders\ExecutiveDemoSeeder` (via `LocalDevSeeder`) | `ExecutiveDemoSeederTest` |

## Deferred / omitted (inert slots exist) — switch-on conditions

| Item | Switch-on condition |
| --- | --- |
| Population penetration / coverage % | Load an LGA/ward **population baseline** → fill `deferred.population_penetration` (today: **absolute bands only**) |
| Targeting accuracy | Integrate a **poverty register / PMT** denominator → `deferred.targeting_accuracy` |
| Vulnerability / disability / PWD / IDP / poverty / occupation | Add the registry **field(s)** → extend `demographics` (panels are **absent**, not empty) |
| Outcome / M&E indicators | Wire **M&E outcome** integration → `deferred.outcome_indicators` |
| Identity-verification rate | Add an explicit **identity-verification field** → `deferred.identity_verification` |
| Map overlay layers (schools, health, IDP, flood) | `registerMapLayer()` an external **GeoJSON** source (framework + example shipped) |
| Meetings / attendance / action items | **Not part of SP-MIS** — external coordination tool (noted, not built) |
| Heat maps (FR-GIS-02) | Query the PostGIS `geom` column (documented extension point) |

## Acceptance

- ✅ 5-tab suite renders accurate, scoped, read-only, aggregate-only views; **net-unique** headline; no raw PII.
- ✅ Coverage is **absolute-count banded** (no %/index); only captured demographics shown; omitted panels absent; `deferred` slots exist.
- ✅ Forecasting shows **labelled** projections with assumptions; filters / drill-down / export / tiering all work within permissions.
- ✅ Backend + frontend suites pass; `pint` + `tsc` + `oxlint` clean; UI on the forest/lime/mint design system; docs updated.
