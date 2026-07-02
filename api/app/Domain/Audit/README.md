# Audit domain

An immutable, append-only audit log of security-relevant and data-changing actions.

**Implements:** FR-AUD-01 (immutable log of create/edit/delete/access + approvals with actor,
timestamp, before/after) and NFR-AUD-01 (tamper-evident). Rules per `docs/SECURITY.md §6`.

## What gets recorded

Every entry (`audit_log` table) captures: actor (`actor_id` + `actor_mda_id`), `action`
(e.g. `user.created`, `mda.updated`, `auth.login`), entity type + id, `before`/`after` snapshots,
IP, user-agent, and a per-request `correlation_id`. Only `created_at` — rows are never updated.

## How it's wired

| Piece | Role |
| --- | --- |
| `Models/AuditLog` | Append-only model; blocks `updating`/`deleting` in app code |
| `Services/AuditLogger` | Single `record(...)` entry point; captures actor + request context |
| `Concerns/Auditable` | Trait: model observers (`created`/`updated`/`deleted`) → scrubbed before/after. Applied to `User`, `Mda`, `Role` |
| `Support/AuditScrubber` | Redacts secrets, masks PII (config in `config/audit.php`) |
| `Listeners/AuditEventSubscriber` | Maps Access-domain events (lockout, MFA, cross-MDA grants) to audit entries |

Auth actions with no model write (`auth.login`, `auth.logout`, `auth.login_failed`) and the
token/MFA admin actions (`user.password_reset_forced`, `user.mfa_reset`) call `AuditLogger` directly.

## Tamper-evidence (NFR-AUD-01)

Migration `…_make_audit_log_append_only` installs PostgreSQL triggers that **reject UPDATE, DELETE
and TRUNCATE** on `audit_log` for any role — so even the app's own DB user cannot alter history
(INSERT/SELECT only). The model-level guard is the defence-in-depth twin (and covers the sqlite
test DB where triggers don't apply).

## No secret / PII leakage (SECURITY.md §6)

- **Secrets** (`password`, `mfa_secret`, `mfa_recovery_codes`, tokens…) → stored as `[redacted]`.
- **PII** (email, phone, NIN/BVN, address, contacts, and a user's `name`) → masked (e.g. `jo***`).
- Non-PII identifiers (MDA/role names, ids) stay readable. Add per-model exceptions via
  `Auditable::auditOmit()` / `auditMask()`; operational columns are excluded via `auditExcluded()`.

## Making a new model auditable

`use App\Domain\Audit\Concerns\Auditable;` on the model. Override `auditExcluded()` for noisy
operational columns and `auditMask()`/`auditOmit()` for any model-specific PII/secret fields.
Never make `AuditLog` itself auditable.

## Tests

`api/tests/Feature/Audit/AuditLogTest.php` — CRUD before/after, auth events, the append-only guard,
and no-PII/secret-leakage assertions.
