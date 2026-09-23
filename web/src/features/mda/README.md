# MDA console

The delivery workspace for an MDA: six task-based modules plus a header, serving both
MDA roles from **one navigation**.

**It is a composition layer, not a module.** Every module arranges screens that already
exist in their source feature and drives existing endpoints. The console owns no registry
logic, no report engine, no matching engine and no notification path. The one endpoint it
genuinely adds is `GET /mda/action-required` — the live "awaiting me" counters, which no
other feature provides (see [Why one new endpoint](#why-one-new-endpoint)).

**Everything is MDA-scoped on the server.** `MdaScope` and the resolved `DashboardScope`
bound every query to the caller's MDA. The client renders what it is given; it never
decides scope. A filter can narrow within that scope, never widen it.

## Access

Gated by **permission**, not by role — one rail, each item conditional. `MdaLayout`
additionally checks the role is `mda_admin` (the single MDA role since FR-UAM-01, which
absorbed MDA Officer), which is a UX guard only: every endpoint behind these pages
carries its own `permission:` middleware and the `MdaScope` global scope
(SECURITY.md §3 — the server is the security boundary).

User administration is **not** part of this console — creating, editing or suspending
accounts is centralised with the System Administrator. The MDA role keeps `user.view`
so it can see who belongs to it, and nothing more.

## Modules → source phases

| # | Module | Composes | Source phase |
| --- | --- | --- | --- |
| 1 | **Overview** | Phase 6 `/dashboard` aggregation (MDA-scoped) for KPIs; `GET /mda/action-required` for the live queue; Phase 5 notification feed for recent activity; Quick Actions that navigate into existing flows | Phase 6 + Phase 5 |
| 2 | **Programmes** | `useProgrammes` with server-side `filter[participating]`; `ActivityFormModal` — the same conditional wizard `/activities` uses | Phase 4 (§10 — central catalogue + the MDA’s own, approved centrally) |
| 3 | **Beneficiaries** | `BeneficiaryListPage`, `HouseholdListPage`, `ImportListPage` — all `embedded` | Phase 2 (FR-REG) + Phase 3 screening |
| 4 | **Service Delivery** | `RecordBenefitPage` (§8.3), `DeliveriesTab` / `AggregateTab` / `FlagsTab` from `BenefitLedgerPage`, `BenefitsPanel`, `ReferralTable` both directions, `ServiceRequestsPage` | Phase 4 benefits + Phase 5 referrals + Phase 2/3 request-to-serve |
| 5 | **Duplicate Resolution** | `ResolveRowControls` (the FR-DUP-09 gate lives there, not here), `MatchComparison`, `MatchRevealPanel`, `MatchStrengthBand`, `DuplicateSearchPage` | Phase 3 (FR-DUP) |
| 6 | **Reports** | `ReportPanels` — the shared builder / catalogue / schedules / runs the admin console also composes — over the Phase 6 ad-hoc engine, plus the beneficiary list export | Phase 6 (FR-RPT-03/04) |
| — | **Settings** (gear, not a nav link) | `/auth/me`, `/auth/password`, `/auth/mfa/disable`, `/notifications/preferences` | Phase 1 auth + Phase 5 notifier |
| — | **Notifications** (header bell) | Phase 5 `Notifier`; role-aware deep-links via `linkFor` | Phase 5 (FR-NOT-01) |

## One role, and why the rail is still permission-gated

There is exactly **one** role here: `mda_admin`. MDA Officer was merged into it (PRD v1.6,
FR-UAM-01) — its permissions were already a strict subset — and it is gone from `RoleKey`
and from the seeded roles, asserted by `MdaRoleMatrixTest::test_mda_officer_no_longer_exists`.

The rail nevertheless gates each item on **permission, not role name**. That was the right
shape when there were two roles and it is what let the merge land without touching
navigation; it also means permissions can be re-granted per role without a code change.
Never reintroduce a role-name branch here.

## Two kinds of organisation, one workspace

A government MDA and a **partner organisation** (a development partner that implements —
PRD §6.6, FR-UAM-08) both work in this console. A partner's staff hold the same
`mda_admin` role against an organisation of `type = partner`, own records through the same
`owner_mda_id`, and are scoped, duplicate-screened and audited identically. Nothing in this
feature branches on it.

What does differ is **wording**, and it comes from one place: `workspaceIdentity.ts` reads
the signed-in user's organisation type and returns the workspace name, the role label and
the noun for "your …". Screens ask it instead of writing "MDA", so a partner reads *Partner
workspace / Partner Admin / scoped to your organisation* while a ministry sees exactly what
it always saw.

Three rules when adding copy here:

- **Never hard-code "MDA"** for the signed-in user's own organisation — use `identity.org`
  / `identity.orgLabel` / `identity.orgPossessive`.
- **Say "agency" for a third party.** "Another MDA referred a beneficiary to you" is wrong
  once the other party can be an NGO.
- **This is display only.** The role key and permissions are untouched; the partner role
  label is a mapping keyed on role key, because one `Role` row is shared by every holder
  and renaming it would relabel every government admin too.

`export.reveal_pii` is held by **neither** role and is in
`RolePermissionService::NEVER_ROLE_GRANTABLE`, so identifiers are masked in every export
either role can run.

Pinned by `MdaRoleMatrixTest` (server) and `MdaGating.test.tsx` (UI).

## Rules this console must not break

- **No manual beneficiary creation.** There is no `POST /beneficiaries` route, no create
  form, and no "add beneficiary" affordance anywhere. Records enter only through an
  activity-bound upload, which is what lets every record carry provenance and be screened
  (CLAUDE.md §9, FR-REG-10).
- **One flow, multiple entry points.** Create Activity opens the same
  `ActivityFormModal` from Quick Actions and from a programme; a file reaches the same
  `ParseImportBatch` → screening → `ImportCommitter` pipeline whether it arrives inline in
  the wizard (`/activity-imports`) or through the Import Center
  (`/beneficiaries/imports`). Proven by `OnePipelineTest`.
- **Adjudication is probable-only.** An exact identifier match is a settled duplicate:
  discard-or-serve only, never a same-person question. The rule lives in
  `ResolveRowControls` and is enforced server-side with `ADJUDICATION_NOT_ALLOWED`
  (FR-DUP-09).
- **Ownership never transfers.** A referral routes a need; an accepted request-to-serve
  opens read access. Both leave `owner_mda_id` untouched (FR-OWN-02).
- **The state catalogue is never edited here.** It is state-wide and unowned, and an MDA
  delivers through its own activities. What an MDA *can* create here is a programme of its
  own: invisible to every other MDA, and unusable until the System Administrator approves
  it (CLAUDE.md §10, revised 2026-09-17).
- **Delivery value is not expenditure.** A recorded benefit is programme data — SP-MIS
  does not move money. Never "spent", "disbursed" or "expenditure".

## Why one new endpoint

`GET /mda/action-required` exists because the Phase 6 dashboard cannot answer it. An
unfiltered `/dashboard` serves a 15-minute snapshot, and a work queue that reports
cleared work is worse than none; and the dashboard's referral block counts both
directions, whereas "awaiting me" is specifically inbound. It returns **counts only** —
reading the underlying lists would pull PII onto a page that renders a number.

The decision mutations (`useDecideServiceRequest`, `useReferralAction`) invalidate its
query key, so clearing an item updates the Overview and the module together.

## Demo data

`MdaConsoleDemoSeeder` (local/staging only, idempotent) makes all six modules render for
both roles. Run it explicitly, after the normal seed:

```
php artisan db:seed --class="Database\\Seeders\\MdaConsoleDemoSeeder"
```

It reuses the existing sample seeders and adds only what they leave missing: an activity
that registers no beneficiaries, request-to-serve in both directions, an inbound referral
still open, and a duplicate case with real `MatchBand` / `ImportRowResolution` values
whose candidate resolves to an actual record. Synthetic only — no real PII, ever.
