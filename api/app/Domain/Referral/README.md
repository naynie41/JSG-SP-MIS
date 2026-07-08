# Referral domain — cross-MDA referrals (FR-REF-01/02/04, §8.2)

An originating MDA refers a beneficiary to another MDA for an **identified need**;
the receiving MDA works it through a guarded lifecycle. **Status: Phase 5 — complete
(lifecycle + serve authorization + ledger + SLAs + notifications).**

## Lifecycle

```
Created ──► Accepted ──► InProgress ──► Completed | Closed
   │           └────────────────────────► Closed
   ├──► Rejected                (terminal, reason required)
   └──► MoreInfoRequested ──► Created   (originating MDA responds)
```

`ReferralStatus::canTransitionTo()` is the single source of truth; `ReferralService`
guards every transition, stamps a per-state timestamp, and records an explicit audit
entry (`referral.accepted`, `referral.rejected`, …). Invalid transitions →
`422 INVALID_TRANSITION`.

## Who does what

| Action | Party | Transition |
| --- | --- | --- |
| create | originating (`from_mda`) | → Created |
| accept / reject / request-info | **receiving** (`to_mda`) | Created → Accepted / Rejected / MoreInfoRequested |
| respond-info | **originating** | MoreInfoRequested → Created |
| start / complete / close | **receiving** | Accepted/InProgress → InProgress / Completed / Closed |

`ReferralPolicy` gates the party: `receive` (to_mda) for the receiving actions,
`originate` (from_mda) for responding; wrong party → **403**. `reject` requires a
`reason`.

## Acceptance authorizes delivery (FR-REF-02/03, FR-BEN-06)

An **accepted** (or in-progress) referral *is* the authorization for the receiving
MDA to serve the beneficiary — the owner already consented by referring, so **no
Service Request is created or needed**, and this stays **distinct** from the
request-to-serve path:

- `Referral::authorizesDelivery(beneficiaryId, mdaId)` is the single check. It feeds
  **both** Phase 4 gates: the enrollment gate (`EnrollmentService::canServe` — a
  separate OR-branch from the Service Request grant) and the benefit-record gate
  (`Authorization/ReferralAuthorizer`, a `DeliveryAuthorizer` registered under the
  shared `DeliveryAuthorization` tag). The recorder is unchanged; delivery is
  authorized with basis `referral` (audited `benefit.delivery_authorized`).
- **Ownership never moves** — the referral authorizes serving only.
- **Ledger reconciliation** (`ReferralService::reconciliation`): the benefits the
  receiving MDA delivered to the referred beneficiary since acceptance — surfaced on
  `GET /referrals/{id}` and the `complete` response as a `ledger` block
  (`benefit_count`, `benefit_value_total`, `benefit_ids`). Recording an `outcome`
  moves the referral to Completed; the ledger link is the concrete FR-REF-03 outcome.

## Scoping & audit

- **Two-party MDA scope** (`ReferralScope`): a referral is visible when the user's
  accessible MDAs include **either** `from_mda_id` **or** `to_mda_id` — so both
  parties see it; oversight (`cross-mda.view`) sees all; unrelated MDAs get 404.
- **Auditable**: `referral.created` on create; each transition audited explicitly
  with before/after status, actor, and timestamp.

## SLAs + notifications (FR-REF-04/05)

- **Admin-editable SLA windows** — `referral_sla_policies` (one `hours` window per
  status), seeded from `config/sla.php` and editable via `GET/PUT
  /referral-sla-policies/{status}` (gated by `referral-sla.edit`, audited). Absent
  row = no SLA for that status.
- **Scheduled sweep** — `Jobs/EscalateOverdueReferrals` runs **hourly** (wired in
  `bootstrap/app.php` `withSchedule`). For each active referral it measures time in
  the current status against its window; when overdue it bumps `escalation_level`
  (one tier per elapsed window, capped at the `config('sla.referral.escalation_chain')`
  length), stamps `sla_breached_at`, audits `referral.sla_breached`, and dispatches
  `ReferralSlaBreached`. It **never changes status — no auto-close**.
- **Notifications** (Phase 5.1) — `ReferralStatusChanged` (on create + every
  transition) notifies **both** MDAs' referral staff; `ReferralSlaBreached` notifies
  both MDAs **plus** the escalation-tier role for that level. In-app + queued email.

## Permissions (RBAC)

`referral.view` (both parties + oversight), `referral.create` (originating),
`referral.edit` (lifecycle actions). MDA Admin & Officer get all three; SP
Coordination, M&E and Executive get **view**.

## Endpoints (`/api/v1`, Sanctum + `permission:` gated)

`GET /referrals` (`filter[direction]=incoming|outgoing`, `filter[status]`) ·
`POST /referrals` · `GET /referrals/{id}` ·
`POST /referrals/{id}/{accept|reject|request-info|respond-info|start|complete|close}`.

Tests: `tests/Feature/Referral/ReferralTest` (lifecycle + party guards),
`ReferralAuthorizationTest` (FR-BEN-06 serve authorization + ownership invariance +
ledger), `ReferralSlaTest` (SLA config, escalation, both-party notifications). Sample
data: `Database\Seeders\ReferralSampleSeeder`.
