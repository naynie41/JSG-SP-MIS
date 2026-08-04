# System Administrator console

The governance surface for the platform: nine sections plus a Settings page, all behind
a single role.

**It is a composition layer, not a module.** Every section renders an EXISTING feature
module and drives an EXISTING endpoint. The console owns no registry logic, no report
engine, no notification path, no permission store. Where a source module does not exist
yet, the section says so rather than inventing one.

**It is governance, not operations.** No uptime, CPU, memory, disk, queue-depth or
other infrastructure widget appears anywhere in it. Those belong to the deployment's
monitoring, not to a social-protection MIS. Nor does it run programme delivery —
registering beneficiaries, paying benefits and handling grievances stay in their own
modules, where MDA scoping applies.

## Access

Gated by ROLE, server-side: `role:system_administrator` (`Http/Middleware/CheckRole`).
Permission gating would not work here — a System Administrator implicitly holds *every*
registered permission, so no permission is exclusive to them. The web shell mirrors the
same check for navigation, but the server is the authority.

## Sections → source phases

| # | Section | Composes | Source phase |
| --- | --- | --- | --- |
| 1 | **Overview** | `AdminSummaryService` (governance KPIs, adoption trend, registry snapshot, alerts, recent activity) + Quick Actions that navigate into existing flows | 1–6 (read-only roll-up) |
| 2 | **User & Access** | `UserListPage`, `RolesPage`, `PermissionsPage`, `LoginActivityService` (projected from the audit log) | Phase 1 (FR-UAM-01/03/05) |
| 3 | **Organization** | `MdaListPage`, partner users, `AdminOrganizationService` roll-up, activities by organization | Phase 1 + Phase 4 |
| 4 | **Programme Catalog** | `ProgrammeListPage` + `usageCounts()` on the existing `/programmes` endpoint | Phase 4 / v1.3 (§10 — global, unowned catalogue) |
| 5 | **Registry & Data Quality** | `ImportListPage` (read-only), registry statistics, duplicate statistics, `BeneficiaryRules` | Phase 2 + Phase 3 (READ-ONLY) |
| 6 | **Integrations** | `/sync/*` connectors, runs and manual trigger when the sync engine answers; a pending state when it does not. Import logs reuse Phase 2 history | Phase 7 (runtime-detected) + Phase 2 |
| 7 | **Matching Rules & Registry Config** | `MatchingConfigPage` — the existing versioned, audited matching engine | Phase 3 (FR-REG-05) |
| 8 | **Audit & Security** | `AuditQueryService` over the immutable hash-chained log; export via the Phase 6 exporters | Phase 1 (FR-AUD-01) READ-ONLY |
| 9 | **Reports** | The Phase 6 ad-hoc engine with ADMINISTRATIVE datasets added to its whitelist; schedules, runs and downloads unchanged | Phase 6 (FR-RPT-03/04) |
| — | **Settings** (gear, not a nav link) | Effective configuration (read-only) + the permission matrix editor + system broadcast | Phase 1 RBAC + Phase 5 notifier |

## Settings

Reached from the gear affordance in the top bar. It is deliberately **not** a navigation
link — the rail is the nine governance sections.

Four panels are read-only projections of configuration that already exists:

- **General** — app name, environment, timezone, locale, debug, audit retention.
- **User & security** — MFA policy, lockout thresholds, session lifetimes, export rate
  limit, and which roles require MFA.
- **Registry** — the LOCKED identity-field ruleset (CLAUDE.md §9 — never
  administrator-editable), privacy/retention flags and consent purposes (DPO-owned).
- **Notifications** — channel availability, asked of each registered channel, so a
  stubbed SMS/WhatsApp provider reports itself unavailable instead of the console
  claiming a delivery path that does not exist.

Each row names the config key or env var that sets it, so an administrator can see what
is in force and where to change it. **There is no console settings store** — nothing on
this page can drift from the running configuration.

Two things an administrator genuinely changes here:

### Permission matrix editor

`PUT /roles/{role}/permissions` → `Access/Services/RolePermissionService` writes the
existing `role_permission` pivot that `User::permissionKeys()` reads, so a saved change
is in force on the next request. Audited as `role.permissions_updated` with the granted
and revoked keys.

Two SECURITY.md invariants are enforced **server-side**, and travel with the matrix so
the UI renders the rule rather than restating it:

1. The **System Administrator role is not editable** — it holds every permission
   implicitly, and editing it would let an administrator lock every administrator out.
2. **`export.reveal_pii` is never bundled into a role.** Unmasking NIN/BVN stays a
   System-Administrator-only capability; granting it to a role is a DPO decision under
   NDPA/NDPR.

Grants that carry a DPO obligation (`beneficiary.export`, `beneficiary.access_request`,
`cross-mda.view`) are permitted but flagged in the UI and recorded separately in the
audit entry, so a periodic export-permission review can find them.

### System broadcast

`POST /notifications/broadcast` → `Notification/Services/BroadcastService` builds one
`NotificationMessage` and hands it to the existing `Notifier`, so channel availability
and each recipient's preferences apply exactly as they do for a domain event. Audience
is active users, optionally narrowed by role or MDA; the audit entry records the filters
and a recipient COUNT, never a recipient list.

## Demo data

`Database\Seeders\AdminConsoleDemoSeeder` — reuses the existing seeders (roles, admin,
MDAs, staff, programmes, registry, partners) and adds only what none of them cover:
import batches across sources and outcomes with duplicate-review rows, plus a suspended
and an MFA-less account so the governance KPIs and alerts are not uniformly green. Never
runs in production; idempotent.

## Tests

| Concern | Test |
| --- | --- |
| Role gating, nav, Quick Action routing, KPI accuracy, no infra widgets | `AdminConsole.test.tsx`, `AdminConsoleTest` |
| Section composition (each renders its source module) | `AdminAccessPage.test`, `AdminOrganizationPage.test`, `AdminCatalogPage.test`, `AdminRegistryPage.test`, `AdminMatchingPage.test`, `AdminAuditPage.test` |
| Reports reuse the Phase 6 engine; admin datasets gated to governance scope | `AdminReportsPage.test.tsx`, `AdminReportTest` |
| Integrations wired + stubbed paths | `AdminIntegrationsPage.test.tsx`, `DataSyncTest` |
| Settings projection, permission matrix editor, broadcast | `AdminSettingsPage.test.tsx`, `ConsoleSettingsTest` |
| Demo seeder | `ConsoleSeederTest` |
