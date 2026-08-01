# Phase 6P — Completion checklist (Funding Partner Reporting Suite)

Maps each delivered item to its PRD requirement ID. **Status: complete.**
Source of truth: `docs/jigawa-SP-MIS.md` (PRD), `docs/CLAUDE.md §5` (phases).
Module docs: [api/app/Domain/Reporting/README.md](../api/app/Domain/Reporting/README.md).

The Development-Partner suite is the partner-facing analog of Phase 6E (FR-RPT-02). Every
figure is **read-only, de-identified aggregate data**, **ACTIVITY-PRECISE** (scoped to the
activities the partner funds via `activities.funding_partner_id`), and money is always
**DELIVERY VALUE** on an **Allocated → Delivered → Remaining** lifecycle — never treasury
expenditure, and never committed/disbursed/grant/audit data.

## Prerequisite — partner→activity funding attribution

| Requirement | Delivered | Where | Tests |
| --- | --- | --- | --- |
| Queryable funding-partner attribution on activities (audited, validated, settable on create/edit by SysAdmin/owning MDA) | `activities.funding_partner_id` (FK users), `IsFundingPartner` rule, Store/Update/Import requests + resource | `Programme/Models/Activity`, `Programme/Rules/IsFundingPartner`, migration `..._add_funding_partner_to_activities` | `PartnerFundingTest` |
| Partner scope derives from attribution (server-side) | Resolver → funded programmes + `partnerId`; every partner block activity-precise | `Reporting/Services/DashboardScopeResolver`, `DashboardMetricsService::partnerFunding()` | `PartnerFundingTest` |

## The 5 tabs (FR-RPT-02)

| Tab | Delivered | Where | Tests |
| --- | --- | --- | --- |
| **Overview** | Funded-scope KPI band (delivery value led), Allocated→Delivered→Remaining lifecycle, results/reach (households/women/children — captured only), results-framework snapshot with greyed **external** Outcomes→Impact, alerts | `FundingPartnerOverviewTab.tsx` | `PartnerDashboardPage.test`, `PartnerFundingTest` |
| **Programmes & Results** | Per-funded-programme cards (budget→delivered→remaining, target/reached, **absolute** coverage, completion, delivery-rate chart, cost/beneficiary), 4-state status, **OUTPUT indicators** (interventions × type × captured demographic), Funding→Activities→Outputs framework (Outcomes→Impact external), activity drill-down | `FundingPartnerProgrammesTab.tsx` | `FundingPartnerProgrammesTab.test`, `PartnerFundingTest` |
| **Registry** | Funded-cohort KPIs, **reduced funnel** Registered→Enrolled→Receiving (eligible→selected = inert slot), captured demographics (gender/age/location/household size), data quality (verification/duplicate/completeness/NIN) | `FundingPartnerRegistryTab.tsx` | `FundingPartnerRegistryTab.test`, `PartnerFundingTest` |
| **Coordination** | Partner landscape (funders/MDAs/implementing), funding-by-partner (**amounts for self only**), MDA landscape, **PROGRAMME OVERLAP** (table + LGA map indicator), data sharing/sync; meetings + reporting-compliance = inert slots | `FundingPartnerCoordinationTab.tsx` | `FundingPartnerCoordinationTab.test`, `PartnerFundingTest` |
| **Investment Map** | Funding-density choropleth (attributed budget, green/yellow/red/grey) + table fallback, toggleable layers (funding/beneficiaries/coverage/programmes; poverty/vulnerability = slots), **coverage-vs-funding quadrants**, per-LGA drill-down | `FundingPartnerInvestmentTab.tsx`, `gis/investment.ts`, `GisCoverageService` | `FundingPartnerInvestmentTab.test`, `investment.test`, `GisCoverageTest` |
| Read-only + funded-scoped + no PII (all tabs) | Aggregates only; no edit/mutating controls; a partner sees ONLY their funded data | (per-tab) + shell | each tab test + `PartnerFundingTest` (no-PII, funded-scope) |

## Cross-cutting (FR-RPT-02/03)

| Requirement | Delivered | Where | Tests |
| --- | --- | --- | --- |
| **Filters** (year/quarter/month, programme, LGA, ward, MDA) across all tabs, within funded scope | Shared `DashboardFilter` + scoped `filterOptions()`; a filter can only NARROW (non-funded programme → empty) | `Support/DashboardFilter`, `DashboardMetricsService`, `GisCoverageService`, `web/.../FilterBar.tsx` + shell | `PartnerFundingTest` (filter narrows), `PartnerDashboardPage.test` |
| **Drill-down** KPI/chart/map → detail (still scoped, no PII) | KPI → Programmes/Registry; overlap LGA → Investment; map area → Registry/Programmes — all via the scoped filter machinery | `PartnerDashboardPage.tsx` (`DrillFn`), tabs | `PartnerDashboardPage.test`, `FundingPartnerInvestmentTab.test` |
| **Export** PDF/Excel/CSV of the current view; aggregate-only; export matrix | `GET /dashboard/export` via shared exporters, gated by `reporting.export` — **never** `beneficiary.export`/`reveal_pii`; audited | `DashboardExportController`, `Export/ExecutiveExportBuilder`, `web/.../ExportMenu.tsx` | `PartnerFundingTest` (aggregate export allowed, PII export forbidden) |
| **Role tiering** — Development Partner = own funded programmes only (server-side) | `DashboardScope::tier() === 'partner'` + resolver + RBAC; the filter can never escape it | `Support/DashboardScope`, `DashboardScopeResolver` | `PartnerFundingTest` (funded-scope only) |
| Demo data — every tab, overlap detector + map render meaningfully (never real PII) | 2 partners funding overlapping programmes in a shared LGA via different MDAs; budgets + delivered benefits across historical periods; varied demographics; enrolled-but-not-served | `Database\Seeders\PartnerDemoSeeder` | `PartnerDemoSeederTest` |

## Labelling & data-integrity guarantees

| Guarantee | How |
| --- | --- |
| Money is **delivery value**, never expenditure | Keys `allocated`/`delivered_value`/`remaining`; UI says "delivery value, not treasury expenditure"; no spent/disbursed/expenditure strings — asserted in tests |
| No committed-vs-disbursed / grant / audit shown or faked | Not computed; deferred with switch-on conditions (below) |
| Coverage is **absolute**, never a population % | `coverage_bands.basis = 'absolute'`; investment density is relative-to-max, not a % of population |
| **Outputs-only** M&E; outcomes external | Output indicators = interventions × benefit type × captured demographic; Outcomes→Impact is a greyed external slot |
| Programme overlap works | Same catalog programme × same LGA across different funders/MDAs — `programmeOverlap()`; count + cells; exposes existence only, never another funder's money |

## Deferred / omitted (inert slots exist) — switch-on conditions

| Item | Switch-on condition |
| --- | --- |
| Committed vs **disbursed** / **grant** lifecycle | Add a **grant module** (award → disbursement schedule) |
| Treasury **expenditure** + audit | Integrate **treasury expenditure + audit** feeds |
| Eligible → Selected funnel steps | Load an **eligible-population denominator** + selection/PMT model |
| Outcome indicators & Outcomes→Impact | Wire **outcome M&E** / evaluation data → fill the external slot |
| PWD / vulnerability / poverty demographics | Add the registry **field(s)** (panels absent, not empty) |
| Coordination meetings / action items, reporting-compliance | Track in an **external coordination tool** |
| Map overlay layers (schools, health, IDP, flood) | `registerMapLayer()` an **external GeoJSON** source |

## Verification

- **Backend:** `php vendor/bin/pint`, `php vendor/bin/phpunit` (Reporting suite green), `php vendor/bin/phpstan analyse` (no new errors).
- **Frontend:** `npx tsc --noEmit`, `npx oxlint src`, `npx vitest run` (all green).
- Tests: `tests/Feature/Reporting/{PartnerFunding,PartnerDemoSeeder,GisCoverage}Test.php`;
  `web/src/features/dashboard/FundingPartner*.test.tsx`, `web/src/features/gis/investment.test.ts`.
