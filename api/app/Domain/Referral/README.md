# Referral domain — cross-MDA referrals (FR-REF-01/02/04, §8.2)

An originating MDA refers a beneficiary to another MDA for an **identified need**;
the receiving MDA works it through a guarded lifecycle. **Status: Phase 5 —
referral creation + lifecycle.**

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

## Scoping & audit

- **Two-party MDA scope** (`ReferralScope`): a referral is visible when the user's
  accessible MDAs include **either** `from_mda_id` **or** `to_mda_id` — so both
  parties see it; oversight (`cross-mda.view`) sees all; unrelated MDAs get 404.
- **Auditable**: `referral.created` on create; each transition audited explicitly
  with before/after status, actor, and timestamp.

## Permissions (RBAC)

`referral.view` (both parties + oversight), `referral.create` (originating),
`referral.edit` (lifecycle actions). MDA Admin & Officer get all three; SP
Coordination, M&E and Executive get **view**.

## Endpoints (`/api/v1`, Sanctum + `permission:` gated)

`GET /referrals` (`filter[direction]=incoming|outgoing`, `filter[status]`) ·
`POST /referrals` · `GET /referrals/{id}` ·
`POST /referrals/{id}/{accept|reject|request-info|respond-info|start|complete|close}`.

Tests: `tests/Feature/Referral/ReferralTest`. Notifications on referral events and
the FR-BEN-06 serving authorization from an accepted referral are separate steps.
