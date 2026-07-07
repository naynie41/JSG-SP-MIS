# Grievance domain — GRM (FR-GRM-01/02/03, §8.4)

Staff log grievances **on behalf of** beneficiaries (no citizen self-service) and
handle them within one MDA through a guarded lifecycle, with per-category SLAs and
scheduled escalation. **Status: Phase 5 — grievance capture + lifecycle + SLAs.**

## Lifecycle

```
Open ──► Assigned ──► InProgress ──► Resolved ──► Closed
  │          └──────────────┴───────────► Closed
  └──► Closed
```

`GrievanceStatus::canTransitionTo()` is the single source of truth; `GrievanceService`
guards every transition, stamps a per-state timestamp, and audits it explicitly
(`grievance.assigned`, `grievance.resolved`, …). Invalid transitions → `422
INVALID_TRANSITION`. **Resolve requires resolution notes.**

## Capture & handling

- **Capture** (`POST /grievances`, `grievance.create`): staff supply `category`,
  `channel` (walk-in / phone / …), an **optional** `beneficiary_id`, and a
  description. `handling_mda_id` = the staff member's MDA; `submitted_by` = the staff.
- **Assign** (`/assign`): to a user **in the handling MDA** (else 422 / the user is
  invisible → 404); Open → Assigned; the assignee is notified.
- **Track**: `/start` (→ InProgress), `/resolve` (notes required → Resolved), `/close`.

## Scoping, audit & notifications

- **MDA-scoped** on `handling_mda_id` (`ScopedToMda`): only the handling MDA +
  oversight (`cross-mda.view`) see a grievance; other MDAs get 404. Acting is
  restricted to the handling MDA's staff (`GrievancePolicy`).
- **Auditable**: `grievance.created` on capture; each transition audited explicitly.
- **Notifications** (Phase 5.1): `GrievanceAssigned` → the **assignee**;
  `GrievanceResolved` → the handling MDA's grievance team **and** the staff member
  who logged it. In-app + queued email.

## SLAs & escalation (FR-GRM-03)

- **Per-category windows** (`grievance_sla_policies`, admin-editable, gated by
  `grievance-sla.edit`): hours from when a grievance is **logged** until it must be
  resolved. Defaults seeded from `config/sla.php`; the DB table is the runtime source
  of truth. Absent category = no SLA.
- **Scheduled sweep** `EscalateOverdueGrievances` (hourly, mirrors the referral job):
  flags unresolved grievances (Open/Assigned/InProgress) past their window and
  escalates **one tier per elapsed window** (capped at the chain length), bumping
  `escalation_level` and stamping `sla_breached_at`. It **never auto-closes** —
  status is untouched.
- **Escalation chain** (`config/sla.grievance.escalation_chain`, ordered roles): the
  handling MDA's admins first, then state coordination. On each new breach level,
  `GrievanceSlaBreached` notifies the handling MDA's grievance team **and** that
  tier (resolved within the handling MDA, falling back to the org-wide role).

## Permissions (RBAC)

`grievance.view` (handling MDA + oversight), `grievance.create`, `grievance.edit`
(assign + lifecycle), `grievance-sla.edit` (configure SLA windows). MDA Admin &
Officer get view/create/edit; SP Coordination, M&E and Executive get **view**; SP
Coordination also gets `grievance-sla.edit`.

## Endpoints (`/api/v1`, Sanctum + `permission:` gated)

`GET /grievances` (`filter[status]`/`filter[category]`/`filter[assignee]=me`) ·
`POST /grievances` · `GET /grievances/{id}` ·
`POST /grievances/{id}/{assign|start|resolve|close}`.

SLA config (`grievance-sla.edit`): `GET /grievance-sla-policies` ·
`PUT|PATCH /grievance-sla-policies/{category}` (`{ "hours": N }`).

Tests: `tests/Feature/Grievance/GrievanceTest`, `tests/Feature/Grievance/GrievanceSlaTest`.
