# Phase Admin — Completion checklist (System Administrator Console)

Maps each delivered item to its source phase and tests. **Status: complete.**
Source of truth: `docs/jigawa-SP-MIS.md` (PRD), `docs/CLAUDE.md §5` (phases),
`docs/SECURITY.md` (access + export matrix).
Module doc: [web/src/features/admin/README.md](../web/src/features/admin/README.md).

The console is a **composition layer**, not a new module. Every section renders an
existing feature module and drives an existing endpoint; it introduces no second
registry, report engine, notification path or permission store. It is **governance, not
operations** — no infrastructure/system-health widget appears anywhere — and it does not
run programme delivery.

## Access & shell

| Requirement | Delivered | Where | Tests |
| --- | --- | --- | --- |
| Console gated to System Administrator (server-side) | `role:system_administrator` middleware on every console endpoint. Gated by ROLE, not permission — a SysAdmin implicitly holds *every* permission, so none is exclusive to them | `Http/Middleware/CheckRole`, `bootstrap/app.php`, `routes/api.php` | `AdminConsoleTest`, `ConsoleSettingsTest` |
| 9-link nav; Settings NOT a nav link | Nav section built by `navSectionsFor()`; Settings reached from the gear affordance in the top bar | `web/src/app/nav.ts`, `AdminLayout.tsx`, `TopBar.tsx` | `AdminConsole.test`, `nav.test` |
| Overview = governance KPIs, no infra widgets | Users/roles/MDAs/programmes/registry/duplicates/adoption/alerts. No uptime, CPU, memory, disk or queue metric exists in the payload | `Reporting/Services/AdminSummaryService`, `AdminOverviewPage.tsx` | `AdminConsoleTest` (no-infra assertion), `AdminConsole.test` |
| Quick Actions launch EXISTING flows (no parallel forms) | 9 launchers, each navigating to a real destination — including manual sync → Integrations and broadcast → Settings | `AdminOverviewPage.tsx` | `AdminConsole.test` (routing per action, none pending) |

## The nine sections → source phases

| # | Section | Delivered | Source phase | Tests |
| --- | --- | --- | --- | --- |
| 1 | **Overview** | Governance KPI band, adoption trend, registry snapshot, alerts, recent activity, Quick Actions | 1–6 roll-up | `AdminConsoleTest`, `AdminConsole.test` |
| 2 | **User & Access** | Users (create/edit/suspend/deactivate, MDA + role), roles, permissions, MFA state, account status, login activity from the audit log | **Phase 1** (FR-UAM-01/03/05) | `AdminAccessPage.test`, `AdminConsoleTest` |
| 3 | **Organization** | MDAs + development partners, org status, activities by organization, user allocation per org | **Phase 1 + 4** | `AdminOrganizationPage.test`, `AdminConsoleTest` |
| 4 | **Programme Catalog** | Catalogue CRUD (admin-only), categories, standard eligibility, benefit categories, status, cross-MDA USAGE | **Phase 4 / v1.3** (§10) | `AdminCatalogPage.test`, `AdminConsoleTest` |
| 5 | **Registry & Data Quality** | Registry + household statistics, import history, validation results, duplicate statistics, data-source monitoring, quality indicators. READ-ONLY, aggregate, PII-safe | **Phase 2 + 3** | `AdminRegistryPage.test`, `AdminConsoleTest` |
| 6 | **Integrations** | Connected systems, sync status + history, manual synchronization, import logs. Composes `/sync/*` when the engine answers; a clean pending state when it does not (404/501). A 403 is a real error, never mistaken for "not built" | **Phase 7** (runtime-detected) **+ Phase 2** | `AdminIntegrationsPage.test` (wired + stubbed), `DataSyncTest` |
| 7 | **Matching Rules & Registry Config** | Rules, thresholds, confidence, cascade order, duplicate statistics, validation rules — through the existing versioned, audited engine | **Phase 3** (FR-REG-05) | `AdminMatchingPage.test`, `MatchingConfigTest` |
| 8 | **Audit & Security** | Audit log, user activity, permission changes, login history, security events, request-to-serve, data-access/export logs. Filter/search + export. READ-ONLY over the immutable hash-chained log | **Phase 1** (FR-AUD-01) | `AdminAuditPage.test`, `AuditQueryTest` |
| 9 | **Reports** | Administrative report catalogue over the **Phase 6 engine**: 6 admin datasets added to the existing ad-hoc whitelist (`users`, `organizations`, `programme_catalogue`, `duplicates`, `audit`, `imports`); registry reporting reuses the existing `beneficiaries` dataset. Preview, export (PDF/Excel/CSV), saved definitions, schedules, runs and downloads all unchanged | **Phase 6** (FR-RPT-03/04) | `AdminReportsPage.test`, `AdminReportTest` |

## Settings (gear affordance — not a nav link)

| Requirement | Delivered | Where | Tests |
| --- | --- | --- | --- |
| General settings | App name, environment, timezone, locale, debug, audit retention — each row naming the env var/config key that sets it | `Reporting/Services/AdminSettingsService` | `ConsoleSettingsTest`, `AdminSettingsPage.test` |
| User & security settings | MFA enforcement + issuer + recovery codes, lockout thresholds, session lifetimes, export rate limit, and per-role MFA requirement | same | `ConsoleSettingsTest`, `AdminSettingsPage.test` |
| Registry settings | LOCKED identity-field ruleset (CLAUDE.md §9 — never administrator-editable), privacy/retention flags, consent purposes (DPO-owned) | same + `Registry/Support/BeneficiaryRules` | `ConsoleSettingsTest`, `AdminSettingsPage.test` |
| Notification settings | Channel availability asked of each registered channel — a stubbed SMS/WhatsApp provider reports itself unavailable rather than the console claiming a delivery path that does not exist | same + the shared `notification.channels` binding | `ConsoleSettingsTest` (SMS stub unavailable) |
| **No shadow config store** | Settings is a read-only projection of the EFFECTIVE configuration; the only writes reachable from it are the RBAC pivot and the caller's own notification preferences | `AdminSettingsService`, `AdminSettingsController` | `AdminSettingsPage.test` (no inputs/save on read-only panels) |
| **Permission matrix editor** (role × module × action) | `PUT /roles/{role}/permissions` syncs the EXISTING `role_permission` pivot that `User::permissionKeys()` reads — a change is in force on the next request. Validated (unknown key → 422) and audited as `role.permissions_updated` with granted/revoked keys | `Access/Services/RolePermissionService`, `AccessController::updatePermissions` | `ConsoleSettingsTest` (grant/revoke take effect, audit content), `AdminSettingsPage.test` |
| `export` + `export.reveal_pii` in the matrix (SECURITY.md) | Both appear. `export.reveal_pii` is marked `role_grantable: false` by the server and **can never be granted to any role** — it stays a System-Administrator capability. `beneficiary.export` etc. are permitted but flagged `sensitive` (DPO sign-off under NDPA/NDPR) and called out separately in the audit entry | `RolePermissionService::NEVER_ROLE_GRANTABLE` / `::SENSITIVE` | `ConsoleSettingsTest` (reveal_pii rejected), `AdminSettingsPage.test` (checkbox disabled + badge) |
| System Administrator role locked | Not editable — it holds every permission implicitly, and editing it would let an administrator remove their own administration rights | `RolePermissionService::isEditable()` | `ConsoleSettingsTest`, `AdminSettingsPage.test` |

## Broadcast notification

| Requirement | Delivered | Where | Tests |
| --- | --- | --- | --- |
| Broadcast wired to the Phase 5 notification system | One `NotificationMessage` handed to the existing `Notifier` — channel availability and recipient preferences apply exactly as for a domain event. No second delivery path | `Notification/Services/BroadcastService` | `ConsoleSettingsTest` (in-app rows created), `AdminSettingsPage.test` |
| Audience + safety | Active users only, optionally narrowed by role/MDA; audience preview before sending; audited with filters + recipient COUNT, never a recipient list | same | `ConsoleSettingsTest` (inactive skipped, role narrowing, audit shape) |

## Seeders

| Requirement | Delivered | Where | Tests |
| --- | --- | --- | --- |
| Admin account + representative users/MDAs/partners/programmes/imports | `AdminConsoleDemoSeeder` reuses the existing seeders and adds only what none cover: import batches across 3 sources and 3 outcomes with duplicate-review rows, plus a suspended and an MFA-less account so KPIs/alerts are not uniformly green | `Database\Seeders\AdminConsoleDemoSeeder` | `ConsoleSeederTest` (coverage, account states, outcomes, idempotency, production guard) |

## Cross-cutting

| Requirement | Delivered | Tests |
| --- | --- | --- |
| One source of truth — no duplicated logic | Every section renders an existing feature component and drives an existing API layer; tests mock the SOURCE module's api to prove reuse | each section test |
| Reports reuse the Phase 6 engine | Datasets added to the existing whitelist; no parallel engine. Governance scope (`DashboardScope::$governance`) keeps admin datasets from state-wide Executives at request, schedule-creation and delivery time | `AdminReportTest` |
| Integrations honest about Phase 7 | Real when the engine answers; a clean "available when synchronization (Phase 7) is enabled" state otherwise. Never fabricated sync data | `AdminIntegrationsPage.test` |
| PII safety | Audit projection carries changed field NAMES only; admin report datasets expose aggregate dimensions only (no name/email/NIN/payload); registry oversight is aggregate | `AuditQueryTest`, `AdminReportTest`, `AdminConsoleTest` |
| Design system + §5.12 | Forest/lime/mint tokens, console shell, KPI band, section craft, inert/locked states rendered as explicit slots | visual + `AdminConsole.test` |

## Deliberately omitted (and why)

| Item | Why |
| --- | --- |
| Infrastructure / system-health widgets (uptime, CPU, memory, disk, queue depth) | The console is governance. Infrastructure belongs to deployment monitoring — asserted absent in `ConsoleSettingsTest` and `AdminConsoleTest` |
| Programme-delivery operations (register a beneficiary, pay a benefit, resolve a grievance) | Those stay in their own modules where MDA scoping applies |
| Editable general/security/registry settings | They are environment/config-owned. Making them console-editable would create the shadow store this phase explicitly forbids |
| Editable identity-field validation | Locked decision (CLAUDE.md §9) — it governs matching and masking everywhere |
| Granting `export.reveal_pii` to a role | SECURITY.md: never bundled into a role; a DPO decision |
| Per-USER permission grants | The matrix is role-level, per the phase scope. SECURITY.md's note that `export` "may be granted per user" would need a user-level override store — not built; role-level grants are flagged for DPO review instead |
