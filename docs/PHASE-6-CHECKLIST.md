# Phase 6 — Completion checklist (Dashboards, Reporting & GIS)

Maps each delivered item to its PRD requirement ID. **Status: complete.**
Source of truth: `docs/jigawa-SP-MIS.md` (PRD), `docs/CLAUDE.md §5` (phases).
Module docs: [api/app/Domain/Reporting/README.md](../api/app/Domain/Reporting/README.md),
[api/app/Domain/Reporting/Gis/README.md](../api/app/Domain/Reporting/Gis/README.md).

## Dashboards + aggregation (FR-RPT-01/02, FR-DSH-01)

| Requirement | Delivered | Where | Tests |
| --- | --- | --- | --- |
| **FR-RPT-01** — executive dashboard, real-time KPIs (beneficiaries, programmes, benefits, coverage by LGA) | Cached aggregation layer + forest-KPI executive view (read-only) | `Reporting/Services/DashboardMetricsService`, `DashboardSnapshotService`, `DashboardService`; `web/features/dashboard/ExecutiveDashboardPage` | `DashboardMetricsTest`, `ExecutiveDashboardPage.test` |
| **FR-RPT-02** — MDA + Partner dashboards scoped to permitted data | Same layer, scope-resolved; MDA + Partner views; partner limited to funded programmes | `DashboardScope(Resolver)`, `Programme/Models/ProgrammeFunder`; `MdaDashboardPage`, `PartnerDashboardPage` | `DashboardMetricsTest` (scope + reconciliation + cross-scope denial), `Mda/PartnerDashboardPage.test` |
| **FR-DSH-01** — scoping enforced server-side; snapshots not raw scans | Explicit scope on every query; request path reads one `dashboard_snapshots` row | `MdaScope` bypass + explicit scope; `RefreshDashboardSnapshots` (15-min) | `DashboardMetricsTest::reads_come_from_the_summary` |

## Reports + export (FR-RPT-03)

| Requirement | Delivered | Where | Tests |
| --- | --- | --- | --- |
| **FR-RPT-03** — standard + ad-hoc reports; export CSV/Excel/PDF | One `ReportData` → `Csv/Excel/Pdf` exporters (branded PDF); standard catalogue + ad-hoc builder | `Reporting/Export/*`, `Reports/ReportCatalogue`+`ReportBuilder`, `Reports/AdHoc/*` | `ReportExportTest`, `AdHocReportTest` |
| **FR-RPT-03 (queue)** — heavy generation off the request; ready notification | Queued `GenerateReport` → `ReportReady` → Phase 5 notify (in-app + email) | `Jobs/GenerateReport`, `NotificationSubscriber::handleReportReady` | `ReportExportTest::generation_is_queued/notifies` |
| **FR-RPT-03 (scope/PII/audit)** — in-scope only, PII masked, audited | Scope captured on the run; `ReportData::cell()` masks sensitive columns; `report.generated`/`report.downloaded` audited | `Export/SensitiveMasker`, `ReportController` | `ReportExportTest` (masking/scope/audit), `AdHocReportTest` (whitelist non-bypass) |
| **FR-RPT-03 (ad-hoc)** — compose dataset/columns/filters/grouping within scope; save for reuse | Whitelisted `AdHocDatasetRegistry`; scope applied before filters; saved `report_definitions` | `Reports/AdHoc/AdHocReportBuilder`, `ReportDefinitionController` | `AdHocReportTest` |

## Scheduled reports (FR-RPT-04)

| Requirement | Delivered | Where | Tests |
| --- | --- | --- | --- |
| **FR-RPT-04** — scheduled, automated generation + distribution | `report_schedules` (freq/format/delivery/recipients) + daily sweep; deliver via Phase 5 (link or attachment) | `Models/ReportSchedule`, `Services/ReportScheduleService`, `Jobs/RunDueReportSchedules`, `Listeners/DeliverScheduledReport`, `Mail/ScheduledReportMail` | `ReportScheduleTest` |
| **FR-RPT-04 (scope)** — cannot deliver out-of-scope data | Recipients must `covers()` the report scope — validated at create/edit **and** delivery | `DashboardScope::covers`, `ReportScheduleService::assertRecipientsCoverScope` | `ReportScheduleTest::recipients_must_cover_the_reports_scope` |

## GIS (FR-GIS-01; FR-GIS-02 stubbed)

| Requirement | Delivered | Where | Tests |
| --- | --- | --- | --- |
| **FR-GIS-01** — map beneficiaries/programmes/coverage by LGA/Ward | Choropleth (Leaflet) from scope-aware coverage; PostGIS boundaries + documented GeoJSON loader; **graceful table fallback** when none loaded | `Gis/{GeoBoundary,BoundaryLoader,LoadGeoBoundaries,GisCoverageService}`, `GisController`; `web/features/gis` | `GisCoverageTest`, `GisDashboardPage.test` |
| **FR-GIS-02** — heat maps | **Not built** — clean extension point on the PostGIS `geom` column, documented | `Gis/README.md`, `geo_boundaries.geom` (pgsql) | — |

## Acceptance criteria (all met)

- **Dashboards accurate + scope-correct + read-only** — `DashboardMetricsTest` (state-wide/MDA/partner totals, reconciliation, cross-scope denial); frontend read-only + role-guard tests.
- **Reports export CSV/Excel/branded-PDF within scope, PII masked + audited; scheduled delivery to right recipients** — `ReportExportTest`, `AdHocReportTest`, `ReportScheduleTest`.
- **GIS renders choropleth when boundaries loaded, degrades gracefully otherwise** — `GisCoverageTest`, `GisDashboardPage.test`.
- **No PII in any aggregate/dashboard/export** — datasets are aggregate-only (no identifier columns); sensitive columns masked; enforced by whitelist + `SensitiveMasker`, covered by tests.
- **Quality gates** — backend `php artisan test` green; Pint + Larastan (lvl 5) clean; frontend `typecheck` + `oxlint` + Vitest green; UI derives from `docs/DESIGN-SYSTEM.md`.

## SLA / snapshot / schedule cadence

- Dashboard snapshots refresh **every 15 min** (`RefreshDashboardSnapshots`).
- Report schedules swept **daily** (`RunDueReportSchedules`), honouring per-schedule
  daily/weekly/monthly frequency; paused schedules never run.

## New dependencies

- `phpoffice/phpspreadsheet` (Excel), `dompdf/dompdf` (branded PDF) — backend.
- `leaflet` + `react-leaflet` (map) — frontend; tiles configurable via `VITE_MAP_TILE_URL`.

## Local sample data (in `DatabaseSeeder`)

```bash
# Funded partner + synthetic LGA boundaries + warmed dashboard snapshots:
docker compose exec api php artisan db:seed --class="Database\Seeders\ReportingSampleSeeder"
# Real Jigawa boundaries (see Gis/README.md for the GeoJSON format + sources):
docker compose exec api php artisan gis:load-boundaries lga  storage/app/boundaries/jigawa-lga.geojson
docker compose exec api php artisan gis:load-boundaries ward storage/app/boundaries/jigawa-ward.geojson
# (or a full rebuild) migrate:fresh --seed
```
