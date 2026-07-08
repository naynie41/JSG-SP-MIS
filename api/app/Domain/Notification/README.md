# Notification domain — in-app + email (FR-NOT-01/02)

Event-driven notifications so any module can alert the right users without knowing
how delivery works. **Status: Phase 5 — complete (Service Request, Referral & Grievance
events wired).**

## Flow

```
domain event ──► NotificationSubscriber ──► Notifier ──► channels
(e.g. ServiceRequestRaised)   (resolve recipients +      (in_app, email real;
                               build NotificationMessage) sms, whatsapp stubbed)
```

- **Events → notifications.** `Listeners/NotificationSubscriber` mirrors the
  `AuditEventSubscriber` pattern (a `subscribe()` map wired via `Event::subscribe`
  in `NotificationServiceProvider`). It resolves recipients (approvers in an MDA,
  or the original requester) and hands a channel-agnostic `NotificationMessage` to
  the Notifier. **Bodies carry no beneficiary PII** — only a reference to the
  originating entity (`related_type` + `related_id`).
- **Channels** (`Contracts/NotificationChannel`): `InAppChannel` persists a
  `Notification` (the inbox / system of record, always on); `EmailChannel` sends a
  **queued** `NotificationMail` via the SMTP config (toggle in
  `config/notifications.php`); `SmsChannel` + `WhatsAppChannel` are **stubbed** —
  `isAvailable()` is `false` and `send()` throws, so nothing is ever fabricated
  (CLAUDE §8). Wiring a real client + flipping `isAvailable()` is the only change.
- **Notifier** fans a message to recipients across every **available** channel the
  recipient's **preferences** allow (in-app always; email per `email_enabled`).
  Recipients are de-duplicated.

## Consumers (all wired)

| Event | Recipients | Type |
| --- | --- | --- |
| `ServiceRequestRaised` | owner MDA approvers (`to_mda_id`, `beneficiary.approve`) | `service_request.created` |
| `ServiceRequestAccepted` | the requester | `service_request.accepted` |
| `ServiceRequestDeclined` | the requester | `service_request.declined` |
| `OwnershipTransferRequested` | current owner approvers (`from_mda_id`) | `ownership_transfer.requested` |
| `ReferralStatusChanged` | **both** MDAs' referral staff (`referral.edit`) | `referral.<action>` |
| `ReferralSlaBreached` | both MDAs + the escalation-tier role for the level | `referral.sla_breached` |
| `GrievanceAssigned` | the assignee | `grievance.assigned` |
| `GrievanceResolved` | handling-MDA grievance team + whoever logged it | `grievance.resolved` |
| `GrievanceSlaBreached` | handling-MDA team + the escalation tier | `grievance.sla_breached` |

Adding a new consumer only needs a domain event + a subscriber handler.

## Deep links (`related`)

`related_type` is the model's **morph alias** — `Str::snake(class_basename($model))`,
e.g. `referral`, `grievance`, `service_request`, `ownership_transfer_request` — and
`related_id` its UUID. The web notification bell maps these to routes (`referral` →
`/referrals/{id}`, `grievance` → `/grievances/{id}`, service-request/transfer →
`/service-requests`).

## Data

- `notifications` — per-user (`recipient_user_id`), **MDA-scoped** on
  `recipient_mda_id`; `type`, `subject`, `body`, `payload`, `related_*`, `read_at`.
- `notification_preferences` — per-user `email_enabled` (in-app is always delivered).

## Endpoints (`/api/v1`, Sanctum; personal — no permission gate)

| Method | Path | Purpose |
| --- | --- | --- |
| GET | `/notifications` (`filter[status]=unread`) | my notifications (paginated; `meta.unread`) |
| GET | `/notifications/unread-count` | bell count |
| POST | `/notifications/{id}/read` | mark one read |
| POST | `/notifications/read-all` | mark all read |
| GET / PUT | `/notifications/preferences` | channel prefs (`email_enabled`) |

Every query is scoped to `recipient_user_id = the authenticated user`, so a user
only ever sees/reads their own. Tests: `tests/Feature/Notification/NotificationTest`.
