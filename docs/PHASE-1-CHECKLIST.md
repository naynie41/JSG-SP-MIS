# Phase 1 — Completion checklist (Foundation & Access Control)

Maps each delivered item to its PRD requirement ID. **Status: complete.**
Source of truth: `docs/jigawa-SP-MIS.md` (PRD), `docs/CLAUDE.md §5` (phases).

## Foundation / infrastructure

| Requirement | Delivered | Where |
| --- | --- | --- |
| Tech stack — Laravel 12, React+TS, PostgreSQL 16 + PostGIS, Redis, RabbitMQ (PRD §12) | Monorepo `api/` + `web/`; PostGIS enabled by first migration | `docker-compose.yml`, `api/`, `web/` |
| NFR-MAINT-01 — containerised, `docker compose up` from fresh clone | Full stack (api, nginx, web, postgres, redis, rabbitmq, worker) with healthchecks + auto setup | `docker-compose.yml`, `docker/` |
| NFR-INT-01 — API-first | Versioned REST under `/api/v1`, standard success/error envelopes | `api/routes/api.php`, `app/Support/ApiResponse.php` |
| Health/readiness | `GET /api/v1/health` (DB + PostGIS) | `HealthController` |

## Access control

| Requirement | Delivered | Where | Tests |
| --- | --- | --- | --- |
| **FR-UAM-01** — RBAC with 7 predefined roles | 7 roles seeded as data | `RolesAndPermissionsSeeder`, `Enums/RoleKey` | `RbacTest`, `SeederTest` |
| **FR-UAM-02** — admin create/edit/suspend/deactivate users; assign MDA + role | `/api/v1/users` (+ MDAs) admin screens & API | `Access/UserController`, `web/features/users` | `UserManagementTest`, `UserListPage.test` |
| **FR-UAM-03** — users scoped to their MDA unless granted | Central global query scope + cross-MDA grants | `Scopes/MdaScope`, `Concerns/ScopedToMda`, `MdaAccessGrant` | `MdaScopingTest` |
| **FR-UAM-04** — strong passwords + MFA for admin/executive | Password policy (min 12 + breached check); TOTP MFA, mandatory for privileged roles | `Support/PasswordRules`, `Services/MfaService`, `MfaController` | `AuthTest`, `MfaTest` |
| **FR-UAM-05** — permissions at module + action level | `module.action` permissions, registry + `permission` middleware, deny-by-default | `Support/PermissionRegistry`, `Middleware/CheckPermission` | `RbacTest` |
| **FR-UAM-06** — session timeout + lockout after failed logins | Exponential-backoff lockout (default 5); idle + absolute token lifetimes; login rate-limit | `User::registerFailedAttempt`, `Middleware/EnforceIdleTimeout`, `config/security.php` | `LockoutTest` |
| **FR-DSH-01** — controlled cross-MDA data sharing by ownership | Admin-managed, audited cross-MDA grants gate the scope bypass | `Access/MdaAccessGrantController` | `MdaScopingTest` |

## Audit & security

| Requirement | Delivered | Where | Tests |
| --- | --- | --- | --- |
| **FR-AUD-01** — immutable audit log with actor, timestamp, before/after | `audit_log` via `Auditable` trait + `AuditLogger`; create/edit + auth/MFA/lockout/grant events | `Domain/Audit/*` | `AuditLogTest` |
| **NFR-AUD-01** — tamper-evident | Postgres triggers reject UPDATE/DELETE/TRUNCATE; model append-only guard | `…_make_audit_log_append_only` migration, `Models/AuditLog` | `AuditLogTest` |
| **NFR-SEC-01** — hashing, encryption at rest, OWASP practices | bcrypt passwords; MFA secret + recovery codes encrypted; parameterised queries; strict CORS; security headers | `User` casts, `Middleware/SecurityHeaders`, `config/cors.php` | `AuthTest`, `MfaTest` |
| **NFR-SEC-02** — MFA for privileged roles, least privilege | MFA enforced for SysAdmin/Executive; no-privilege-escalation on role assignment | `roles.requires_mfa`, `ValidatesUserAssignment` | `MfaTest`, `UserManagementTest` |
| **NFR-PRV-01** — PII minimisation | No secret/PII leakage in the audit log (redact/mask) | `Support/AuditScrubber` | `AuditLogTest` |

## Frontend

| Requirement | Delivered | Where |
| --- | --- | --- |
| NFR-USE-01 — responsive, accessible UI | Design-system component library + app shell on `docs/DESIGN-SYSTEM.md`; WCAG-AA floor, keyboard focus, reduced-motion | `web/src/components`, `web/src/styles/theme.css` |
| Auth UI (FR-UAM-04) | Login + MFA challenge + recovery-code + first-time enrolment; token/expiry handling; protected routing with permission-based nav | `web/src/features/auth`, `web/src/lib`, `web/src/app` |
| Admin UI (FR-UAM-02/03) | MDA + user management screens (scoped list, create/edit, status, reset) | `web/src/features/{mdas,users}` |

## Quality gates (all green)

- Backend: **71 tests**, Pint (lint) clean, Larastan level 5 clean.
- Frontend: **11 tests**, oxlint clean, `tsc -b` clean, production build succeeds.

## Deliberately deferred (later phases)

- Beneficiary consent capture & data-retention/anonymisation flows (NFR-PRV-01) — arrive with the
  beneficiary registry (Phase 2+); no beneficiary PII exists yet.
- TLS termination and at-rest disk/volume encryption — deployment concerns (dev is localhost HTTP);
  the app is TLS-ready (HSTS emitted over HTTPS).
- Audit of "access"/"approval" of records — hooks exist; wired as those features land.
- Password-reset email delivery / public reset endpoint — admin force-reset revokes sessions today.
