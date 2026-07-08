# Phase 5 — Completion checklist (Referrals, Grievances & Notifications)

Maps each delivered item to its PRD requirement ID. **Status: complete.**
Source of truth: `docs/jigawa-SP-MIS.md` (PRD), `docs/CLAUDE.md §5` (phases).
Module docs: [api/app/Domain/Referral/README.md](../api/app/Domain/Referral/README.md),
[api/app/Domain/Grievance/README.md](../api/app/Domain/Grievance/README.md),
[api/app/Domain/Notification/README.md](../api/app/Domain/Notification/README.md).

## Referrals (FR-REF)

| Requirement | Delivered | Where | Tests |
| --- | --- | --- | --- |
| **FR-REF-01** — refer a beneficiary's need to another MDA | `ReferralService::create` (originating MDA), two-party record; `RaiseReferralModal` (beneficiary + receiving MDA + need) | `Referral/Services/ReferralService`, `ReferralController@store`; `web/features/referrals` | `ReferralTest` |
| **FR-REF-02** — receiving MDA accept / reject / request-info + working states | Guarded lifecycle enum + party policy (`receive`/`originate`); UI action set on the detail page | `Referral/Enums/ReferralStatus`, `ReferralPolicy`; `ReferralDetailPage` | `ReferralTest` |
| **FR-REF-03** — outcome recorded; reconciles with the ledger | `complete` records an `outcome`; `reconciliation()` returns benefits delivered since acceptance, surfaced as a `ledger` block | `ReferralService::reconciliation`, `ReferralController::withLedger`; detail "Ledger reconciliation" card | `ReferralAuthorizationTest` |
| **FR-REF (serve)** — acceptance authorizes delivery, no ownership move, **not** a Service Request | `Referral::authorizesDelivery` feeds the Phase-4 enroll + benefit gates via `ReferralAuthorizer` (tagged `DeliveryAuthorization`); ownership never changes; distinct from request-to-serve | `Referral/Authorization/ReferralAuthorizer`, `EnrollmentService::canServe` | `ReferralAuthorizationTest` |
| **FR-REF-04** — configurable SLA windows; scheduled overdue flag + escalation; never auto-closes | `referral_sla_policies` (admin-editable), hourly `EscalateOverdueReferrals` bumps `escalation_level`/`sla_breached_at`, audits `referral.sla_breached` | `config/sla.php`, `Referral/Jobs/EscalateOverdueReferrals`, `ReferralSlaPolicyController`; SLA/overdue badges in UI | `ReferralSlaTest` |
| **FR-REF-05** — both parties notified at each step + on breach | `ReferralStatusChanged` → both MDAs; `ReferralSlaBreached` → both + escalation tier | `Notification/Listeners/NotificationSubscriber` | `ReferralSlaTest` |

## Grievances (FR-GRM)

| Requirement | Delivered | Where | Tests |
| --- | --- | --- | --- |
| **FR-GRM-01** — staff log grievances on behalf of beneficiaries (category, channel, optional link, description) | `GrievanceService::create` (handling MDA = staff's; no self-service); `LogGrievanceModal` | `Grievance/Services/GrievanceService`, `GrievanceController@store`; `web/features/grievances` | `GrievanceTest` |
| **FR-GRM-02** — assign + track to resolution with notes + timestamps; guarded transitions | Lifecycle enum + `assign`/`start`/`resolve`/`close` (resolve requires notes); MDA-scoped policy; queue + detail UI | `GrievanceStatus`, `GrievanceService`, `GrievancePolicy`; `GrievanceDeskPage`, `GrievanceDetailPage` | `GrievanceTest` |
| **FR-GRM-03** — per-category SLA + escalation chain; scheduled overdue flag; never auto-closes | `grievance_sla_policies` (admin-editable), hourly `EscalateOverdueGrievances`, audits `grievance.sla_breached`; SLA/overdue badges | `config/sla.php`, `Grievance/Jobs/EscalateOverdueGrievances`, `GrievanceSlaPolicyController` | `GrievanceSlaTest` |
| **FR-GRM (notify)** — assignment + resolution + breach notify the right parties | `GrievanceAssigned`/`GrievanceResolved`/`GrievanceSlaBreached` handlers | `NotificationSubscriber` | `GrievanceTest`, `GrievanceSlaTest` |

## Notifications (FR-NOT)

| Requirement | Delivered | Where | Tests |
| --- | --- | --- | --- |
| **FR-NOT-01** — event-driven in-app + email to the right users; no PII in bodies | `NotificationSubscriber` → `Notifier` → channels; `InAppChannel` (persist) + queued `EmailChannel`; bodies carry only entity references | `Notification/*`; consumers for Service Request, Referral, Grievance events | `NotificationTest` |
| **FR-NOT-02** — inbox, unread count, mark-read/all-read, per-user prefs | Personal, per-user scoped endpoints; `NotificationBell` (count + panel + deep links + email toggle) | `NotificationController`; `web/features/notifications/NotificationBell` | `NotificationTest`, `NotificationBell.test` |
| **Stubbed channels inert** | `SmsChannel` + `WhatsAppChannel` report `isAvailable() = false` and `send()` throws — never fabricated (CLAUDE §8) | `Notification/Channels/*` | `NotificationTest` |

## Acceptance criteria (all met)

- **Referral end-to-end** — owner MDA refers; receiving MDA accepts/rejects/requests-info; on
  acceptance the **referral itself** authorizes delivery + outcome recording — **no ownership move,
  no Service Request**; referral and request-to-serve remain distinct + separately tracked; both
  parties notified; SLAs flag overdue. (`ReferralTest`, `ReferralAuthorizationTest`, `ReferralSlaTest`)
- **Grievance end-to-end** — staff log → assign → resolve with SLAs + escalation; parties notified.
  (`GrievanceTest`, `GrievanceSlaTest`)
- **Notifications** — in-app + email via the event system incl. Service Request events; SMS/WhatsApp
  stubbed + inert. (`NotificationTest`, `NotificationBell.test`)
- **Quality gates** — backend `php artisan test` green; Pint + Larastan (lvl 5) clean; frontend
  `typecheck` + `oxlint` + Vitest green; UI derives from `docs/DESIGN-SYSTEM.md`.

## SLA / escalation configuration

- **Defaults** live in `api/config/sla.php` (`referral.windows` per status, `grievance.windows` per
  category, plus each `escalation_chain` of roles). Seeded into the admin-editable
  `referral_sla_policies` / `grievance_sla_policies` tables (runtime source of truth).
- **Edit at runtime:** `GET|PUT /api/v1/referral-sla-policies/{status}` (`referral-sla.edit`) and
  `GET|PUT /api/v1/grievance-sla-policies/{category}` (`grievance-sla.edit`). Absent row = no SLA.
- **Sweep:** `EscalateOverdueReferrals` + `EscalateOverdueGrievances` run **hourly**
  (`bootstrap/app.php` `withSchedule`, `withoutOverlapping`). They escalate one tier per elapsed
  window (capped at the chain length) and **never change status / auto-close**.

## Verification

- Backend: `php artisan test` — **all green** (referral lifecycle + party guards + serve
  authorization + ownership invariance + ledger + SLAs; grievance capture→resolution + SLAs +
  escalation; notification dispatch in-app + email + stubbed-inert + preferences + scoping; seeder
  baseline + Phase 5 sample data).
- Backend: Pint (style) + Larastan level 5 — **clean**.
- Frontend: `typecheck` + `lint` (oxlint) + `test` (Vitest) — **all green**.
- Docs: module READMEs (FR maps, SLA/escalation config, deep-link map), root README Phase 5 section,
  this checklist.

## Local sample data (in `DatabaseSeeder`)

```bash
# All Phase 5 sample data (users, referrals incl. rejected + overdue, grievances incl.
# an escalated one, synthetic notifications):
docker compose exec api php artisan db:seed --class="Database\Seeders\SampleMdaUserSeeder"
docker compose exec api php artisan db:seed --class="Database\Seeders\ReferralSampleSeeder"
docker compose exec api php artisan db:seed --class="Database\Seeders\GrievanceSampleSeeder"
docker compose exec api php artisan db:seed --class="Database\Seeders\NotificationSampleSeeder"
# (or a full rebuild) migrate:fresh --seed
```
